<?php
/**
 * Logging Library for Craft CMS
 *
 * Cache-backed recent runtime log store for edge/ephemeral environments.
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\services;

use Craft;
use craft\base\Component;
use craft\elements\User;
use craft\helpers\Json;
use yii\helpers\VarDumper;
use yii\log\Logger;

/**
 * Stores recent normalized runtime log records in Craft cache.
 *
 * @since 5.14.0
 */
class RuntimeLogStoreService extends Component
{
    private const CACHE_KEY = 'logging-library:runtime-log-store:v1';
    private const LOCK_KEY = 'logging-library:runtime-log-store:v1:lock';
    private const MAX_ENTRIES_LIMIT = 10000;

    private bool $_writing = false;

    /**
     * Append Yii log messages to the bounded runtime store.
     */
    public function appendMessages(array $messages, array $settings): void
    {
        if ($this->_writing || empty($messages) || !($settings['enabled'] ?? false)) {
            return;
        }

        $this->_writing = true;

        try {
            $records = [];
            foreach ($messages as $message) {
                $records[] = $this->normalizeMessage($message, $settings);
            }

            usort($records, fn(array $a, array $b) => strcmp((string)$b['timestamp'], (string)$a['timestamp']));

            $maxEntries = min(self::MAX_ENTRIES_LIMIT, max(1, (int)($settings['maxEntries'] ?? 1000)));
            $ttl = max(1, (int)($settings['ttl'] ?? 86400));

            $mutex = Craft::$app->getMutex();
            if (!$mutex->acquire(self::LOCK_KEY, 2)) {
                return;
            }

            try {
                $existing = $this->_getRecords();
                $records = array_slice(array_merge($records, $existing), 0, $maxEntries);
                Craft::$app->getCache()->set(self::CACHE_KEY, $records, $ttl);
            } finally {
                $mutex->release(self::LOCK_KEY);
            }
        } catch (\Throwable) {
            // Runtime logging must never break the request or recurse into logging.
        } finally {
            $this->_writing = false;
        }
    }

    /**
     * Return a filtered, sorted, paginated page of runtime records.
     */
    public function getLogPage(string $level, string $category, string $search, string $sort, string $dir, int $page, int $limit): array
    {
        $records = $this->_getRecords();
        $search = mb_strtolower($search);

        $records = array_values(array_filter($records, function(array $record) use ($level, $search): bool {
            if ($level !== 'all' && ($record['canonicalLevel'] ?? '') !== $level) {
                return false;
            }

            if ($search === '') {
                return true;
            }

            $haystack = mb_strtolower(implode(' ', [
                (string)($record['message'] ?? ''),
                (string)($record['context'] ?? ''),
                (string)($record['category'] ?? ''),
                (string)($record['user'] ?? ''),
            ]));

            return str_contains($haystack, $search);
        }));

        $categoryCounts = [];

        foreach ($records as $record) {
            $recordCategory = (string)($record['category'] ?? '');
            if ($recordCategory !== '') {
                $categoryCounts[$recordCategory] = ($categoryCounts[$recordCategory] ?? 0) + 1;
            }
        }

        if ($category !== 'all' && !isset($categoryCounts[$category])) {
            $category = 'all';
        }

        if ($category !== 'all') {
            $records = array_values(array_filter($records, function(array $record) use ($category): bool {
                return ($record['category'] ?? '') === $category;
            }));
        }

        $sort = in_array($sort, ['timestamp', 'level', 'category', 'user', 'message'], true) ? $sort : 'timestamp';
        $direction = $dir === 'asc' ? 1 : -1;

        usort($records, function(array $a, array $b) use ($sort, $direction): int {
            $aValue = $a[$sort] ?? '';
            $bValue = $b[$sort] ?? '';

            if ($sort === 'timestamp') {
                $aValue = strtotime((string)$aValue) ?: 0;
                $bValue = strtotime((string)$bValue) ?: 0;
            }

            return ($aValue <=> $bValue) * $direction;
        });

        $total = count($records);
        $offset = max(0, ($page - 1) * $limit);
        $entries = array_slice($records, $offset, $limit);

        return [
            'entries' => $this->_withUserLabels($entries),
            'total' => $total,
            'category' => $category,
            'categoryOptions' => $this->_categoryOptions($categoryCounts),
        ];
    }

    /**
     * Clear the runtime log store.
     */
    public function clear(): void
    {
        try {
            Craft::$app->getCache()->delete(self::CACHE_KEY);
        } catch (\Throwable) {
        }
    }

    /**
     * Normalize a Yii log message array into the CP viewer shape.
     */
    public function normalizeMessage(array $message, array $settings): array
    {
        [$text, $level, $category, $timestamp] = $message;

        $canonicalLevel = $this->_canonicalLevel((int)$level);
        $messageText = $this->_stringify($text);
        $messageText = LoggingService::sanitizeLogMessage($messageText);
        $messageText = $this->_truncate($messageText, (int)($settings['maxMessageBytes'] ?? 8000));

        $context = [];
        if (!empty($message[4]) && is_array($message[4])) {
            $context['trace'] = array_slice($message[4], 0, 5);
        }

        if (isset($message[5])) {
            $context['memory'] = $message[5];
        }

        $contextText = $context === []
            ? ''
            : $this->_truncate(Json::encode($context), (int)($settings['maxContextBytes'] ?? 8000));

        $user = '';
        if (($settings['privacy']['includeUserId'] ?? false) && Craft::$app->has('user', true)) {
            $identity = Craft::$app->getUser()->getIdentity(false);
            if ($identity) {
                $user = 'user:' . $identity->getId();
            }
        }

        $isoTimestamp = date('c', (int)$timestamp);

        return [
            'id' => sha1($isoTimestamp . '|' . $canonicalLevel . '|' . $category . '|' . $messageText . '|' . microtime(true)),
            'timestamp' => $isoTimestamp,
            'level' => $canonicalLevel,
            'canonicalLevel' => $canonicalLevel,
            'levelClass' => $canonicalLevel !== '' ? 'lr-level-' . $canonicalLevel : '',
            'category' => (string)$category,
            'message' => $messageText,
            'context' => $contextText,
            'source' => 'runtime-cache',
            'user' => $user,
            'raw' => $messageText . ($contextText !== '' ? ' ' . $contextText : ''),
        ];
    }

    private function _getRecords(): array
    {
        try {
            $records = Craft::$app->getCache()->get(self::CACHE_KEY);
        } catch (\Throwable) {
            return [];
        }

        if (!is_array($records)) {
            return [];
        }

        return array_values(array_map(function(array $record): array {
            $canonicalLevel = (string)($record['canonicalLevel'] ?? $record['level'] ?? '');
            $levelClass = (string)($record['levelClass'] ?? '');

            if ($canonicalLevel !== '' && !str_starts_with($levelClass, 'lr-level-')) {
                $record['levelClass'] = 'lr-level-' . $canonicalLevel;
            }

            return $record;
        }, array_filter($records, 'is_array')));
    }

    private function _categoryOptions(array $categoryCounts): array
    {
        ksort($categoryCounts);

        $options = [[
            'value' => 'all',
            'label' => Craft::t('logging-library', 'All Sources'),
            'status' => 'all',
        ]];

        foreach ($categoryCounts as $category => $count) {
            $options[] = [
                'value' => $category,
                'label' => sprintf('%s (%s)', $category, Craft::$app->getFormatter()->asDecimal($count)),
            ];
        }

        return $options;
    }

    /**
     * Attach display labels for optional user IDs without querying per row.
     */
    private function _withUserLabels(array $records): array
    {
        $ids = [];

        foreach ($records as $record) {
            $user = (string)($record['user'] ?? '');
            if (preg_match('/^user:(\d+)$/', $user, $matches)) {
                $ids[] = (int)$matches[1];
            }
        }

        $usernames = [];
        $ids = array_values(array_unique(array_filter($ids)));

        if ($ids !== []) {
            try {
                foreach (User::find()->id($ids)->status(null)->all() as $user) {
                    $usernames[(int)$user->id] = (string)$user->username;
                }
            } catch (\Throwable) {
                $usernames = [];
            }
        }

        foreach ($records as &$record) {
            $user = (string)($record['user'] ?? '');
            $record['userLabel'] = Craft::t('logging-library', 'System');

            if (preg_match('/^user:(\d+)$/', $user, $matches)) {
                $id = (int)$matches[1];
                $record['userLabel'] = $usernames[$id]
                    ?? Craft::t('logging-library', 'User #{id}', ['id' => $id]);
            } elseif ($user !== '') {
                $record['userLabel'] = $user;
            }
        }
        unset($record);

        return $records;
    }

    private function _canonicalLevel(int $level): string
    {
        return match ($level) {
            Logger::LEVEL_ERROR => 'error',
            Logger::LEVEL_WARNING => 'warning',
            Logger::LEVEL_INFO => 'info',
            Logger::LEVEL_TRACE => 'debug',
            default => 'unknown',
        };
    }

    private function _stringify(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if ($value instanceof \Throwable) {
            return (string)$value;
        }

        return VarDumper::export($value);
    }

    private function _truncate(string $value, int $maxBytes): string
    {
        if ($maxBytes <= 0 || strlen($value) <= $maxBytes) {
            return $value;
        }

        return mb_strcut($value, 0, $maxBytes) . '...';
    }
}
