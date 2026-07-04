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
use craft\helpers\Json;
use lindemannrock\logginglibrary\helpers\RuntimeCategoryOptionsHelper;
use lindemannrock\logginglibrary\helpers\UserLabelHelper;
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
    /**
     * @since 5.14.0
     */
    public const MAX_BYTES_LIMIT = 65536;

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
                if (!Craft::$app->getCache()->set(self::CACHE_KEY, $records, $ttl)) {
                    Craft::$app->getCache()->delete(self::CACHE_KEY);
                }
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
     *
     * @return array{entries: array, total: int, storedTotal: int, category: string, categoryLabel: string, categoryOptions: array}
     */
    public function getLogPage(string $level, string $category, string $search, string $sort, string $dir, int $page, int $limit, ?int $ttl = null): array
    {
        $records = $this->_getRecords();
        if ($ttl !== null) {
            $records = $this->_filterByTtl($records, $ttl);
        }
        $storedTotal = count($records);

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

        $groupedCategoryOptions = RuntimeCategoryOptionsHelper::groupedOptions($categoryCounts);
        $category = RuntimeCategoryOptionsHelper::resolveSelectedValue($category, $groupedCategoryOptions);

        if ($category !== 'all' && !isset($groupedCategoryOptions['rawCategoriesByValue'][$category])) {
            $category = 'all';
        }

        if ($category !== 'all') {
            $selectedRawCategories = $groupedCategoryOptions['rawCategoriesByValue'][$category] ?? [];
            $records = array_values(array_filter($records, function(array $record) use ($selectedRawCategories): bool {
                return in_array((string)($record['category'] ?? ''), $selectedRawCategories, true);
            }));
        }

        $sort = in_array($sort, ['timestamp', 'level', 'category', 'user', 'message'], true) ? $sort : 'timestamp';
        $records = $this->_sortRecords($records, $sort, $dir);

        $total = count($records);
        $offset = max(0, ($page - 1) * $limit);
        $entries = array_slice($records, $offset, $limit);

        return [
            'entries' => UserLabelHelper::withUserLabels(RuntimeCategoryOptionsHelper::withRecordLabels($entries, $groupedCategoryOptions)),
            'total' => $total,
            'storedTotal' => $storedTotal,
            'category' => $category,
            'categoryLabel' => $groupedCategoryOptions['labelsByValue'][$category] ?? Craft::t('logging-library', 'Source'),
            'categoryOptions' => $groupedCategoryOptions['options'],
        ];
    }

    /**
     * Clear the runtime log store.
     */
    public function clear(): bool
    {
        $mutex = Craft::$app->getMutex();
        if (!$mutex->acquire(self::LOCK_KEY, 5)) {
            return false;
        }

        try {
            Craft::$app->getCache()->delete(self::CACHE_KEY);
            return true;
        } catch (\Throwable) {
            return false;
        } finally {
            $mutex->release(self::LOCK_KEY);
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

        $isoTimestamp = $this->_formatTimestamp((float)$timestamp);

        return [
            'id' => sha1($isoTimestamp . '|' . $canonicalLevel . '|' . $category . '|' . $messageText . '|' . microtime(true)),
            'timestamp' => $isoTimestamp,
            'level' => $canonicalLevel,
            'canonicalLevel' => $canonicalLevel,
            'levelClass' => $canonicalLevel !== '' ? 'lr-level-' . $canonicalLevel : '',
            'category' => (string)$category,
            'message' => $messageText,
            'context' => $contextText,
            'user' => $user,
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

    /**
     * Sort records using the same stable behavior as file-backed log pages.
     */
    private function _sortRecords(array $records, string $sort, string $dir): array
    {
        $direction = $dir === 'asc' ? 1 : -1;

        foreach ($records as $index => &$record) {
            $record['_seq'] = $index;
        }
        unset($record);

        if ($sort === 'level') {
            $levelOrder = [
                'error' => 1,
                'warning' => 2,
                'info' => 3,
                'debug' => 4,
                'unknown' => 5,
            ];

            usort($records, static function(array $a, array $b) use ($direction, $levelOrder): int {
                $aLevel = (string)($a['canonicalLevel'] ?? $a['level'] ?? 'unknown');
                $bLevel = (string)($b['canonicalLevel'] ?? $b['level'] ?? 'unknown');
                $comparison = (($levelOrder[$aLevel] ?? 99) <=> ($levelOrder[$bLevel] ?? 99));

                if ($comparison === 0) {
                    $comparison = (($a['_seq'] ?? 0) <=> ($b['_seq'] ?? 0));
                }

                return $comparison * $direction;
            });
        } else {
            usort($records, static function(array $a, array $b) use ($sort, $direction): int {
                $aValue = $a[$sort] ?? '';
                $bValue = $b[$sort] ?? '';

                if ($sort === 'timestamp') {
                    $aValue = strtotime((string)$aValue) ?: 0;
                    $bValue = strtotime((string)$bValue) ?: 0;
                }

                $comparison = ($aValue <=> $bValue);

                if ($comparison === 0) {
                    $comparison = (($a['_seq'] ?? 0) <=> ($b['_seq'] ?? 0));
                }

                return $comparison * $direction;
            });
        }

        foreach ($records as &$record) {
            unset($record['_seq']);
        }
        unset($record);

        return $records;
    }

    /**
     * Remove records older than the configured retention window.
     */
    private function _filterByTtl(array $records, int $ttl): array
    {
        $cutoff = time() - max(1, $ttl);

        return array_values(array_filter($records, static function(array $record) use ($cutoff): bool {
            $timestamp = strtotime((string)($record['timestamp'] ?? ''));

            return $timestamp !== false && $timestamp >= $cutoff;
        }));
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
        $maxBytes = min(self::MAX_BYTES_LIMIT, max(1, $maxBytes));

        if (strlen($value) <= $maxBytes) {
            return $value;
        }

        return mb_strcut($value, 0, $maxBytes) . '...';
    }

    private function _formatTimestamp(float $timestamp): string
    {
        $date = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $timestamp));
        if (!$date instanceof \DateTimeImmutable) {
            return date('c', (int)$timestamp);
        }

        return $date
            ->setTimezone(new \DateTimeZone(date_default_timezone_get()))
            ->format('Y-m-d\TH:i:s.uP');
    }
}
