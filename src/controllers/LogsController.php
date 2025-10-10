<?php
/**
 * Logging Library for Craft CMS
 *
 * Generic controller for viewing plugin logs
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2025 LindemannRock
 */

namespace lindemannrock\logginglibrary\controllers;

use Craft;
use craft\web\Controller;
use craft\web\Response;
use lindemannrock\logginglibrary\LoggingLibrary;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Generic Logs Controller
 * Provides log viewing functionality for any plugin using the logging library
 */
class LogsController extends Controller
{
    /**
     * @inheritdoc
     */
    protected array|int|bool $allowAnonymous = false;

    /**
     * Display logs with pagination
     */
    public function actionIndex(): Response
    {
        $request = Craft::$app->getRequest();

        // Get plugin handle from the URL segment
        $pluginHandle = $this->_getPluginHandleFromUrl();
        $config = LoggingLibrary::getConfig($pluginHandle);

        if (!$config) {
            throw new NotFoundHttpException('Plugin logging not configured');
        }

        // Check if log viewer is enabled
        if (!($config['enableLogViewer'] ?? false)) {
            throw new NotFoundHttpException('Log viewer is disabled for this plugin');
        }

        // Check permissions if specified
        $this->_checkPermissions($config['permissions'] ?? []);

        // Get filter parameters
        $level = $request->getParam('level', 'all');
        $search = $request->getParam('search', '');
        $page = (int) $request->getParam('page', 1);
        $limit = 50; // Entries per page

        // Get available log files
        $logFiles = LoggingLibrary::getLogFiles($pluginHandle);

        // Smart date selection: use most recent log file if no date specified or if specified date doesn't exist
        $requestedDate = $request->getParam('date');
        if ($requestedDate) {
            $date = $requestedDate;
        } elseif (!empty($logFiles)) {
            // Default to the most recent log file (first in the list)
            $date = $logFiles[0]['date'];
        } else {
            // No log files exist, use today as fallback
            $date = (new \DateTime())->format('Y-m-d');
        }

        // Read and parse log entries
        $logEntries = $this->_getLogEntries($pluginHandle, $date, $level, $search, $page, $limit);

        // Get total count for pagination
        $totalEntries = $this->_getLogEntriesCount($pluginHandle, $date, $level, $search);

        // Calculate pagination info
        $totalPages = ceil($totalEntries / $limit);

        return $this->renderTemplate('logging-library/logs/index', [
            'pluginHandle' => $pluginHandle,
            'pluginName' => $config['pluginName'],
            'logFiles' => $logFiles,
            'logEntries' => $logEntries,
            'filters' => [
                'level' => $level,
                'date' => $date,
                'search' => $search,
                'page' => $page,
            ],
            'pagination' => [
                'total' => $totalEntries,
                'perPage' => $limit,
                'currentPage' => $page,
                'totalPages' => $totalPages,
            ],
            'levels' => [
                'all' => 'All Levels',
                'error' => 'Error',
                'warning' => 'Warning',
                'info' => 'Info',
                'debug' => 'Debug',
            ],
            'config' => $config,
        ]);
    }

    /**
     * Download a log file
     */
    public function actionDownload(): Response
    {
        $request = Craft::$app->getRequest();
        $pluginHandle = $this->_getPluginHandleFromUrl();
        $config = LoggingLibrary::getConfig($pluginHandle);

        if (!$config) {
            throw new NotFoundHttpException('Plugin logging not configured');
        }

        // Check if log viewer is enabled
        if (!($config['enableLogViewer'] ?? false)) {
            throw new NotFoundHttpException('Log viewer is disabled for this plugin');
        }

        // Check permissions
        $this->_checkPermissions($config['permissions'] ?? []);

        $date = $request->getRequiredParam('date');

        // Validate date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            throw new \InvalidArgumentException('Invalid date format');
        }

        $logPath = Craft::$app->getPath()->getLogPath() . "/{$pluginHandle}-{$date}.log";

        if (!file_exists($logPath)) {
            throw new \Exception('Log file not found');
        }

        return Craft::$app->getResponse()->sendFile($logPath, "{$pluginHandle}-{$date}.log", [
            'mimeType' => 'text/plain',
            'inline' => false,
        ]);
    }

    /**
     * Get plugin handle from the current URL
     */
    private function _getPluginHandleFromUrl(): string
    {
        $segments = Craft::$app->getRequest()->getSegments();

        // The plugin handle should be the first segment before '/logs'
        foreach ($segments as $index => $segment) {
            if ($segment === 'logs' && $index > 0) {
                return $segments[$index - 1];
            }
        }

        throw new NotFoundHttpException('Unable to determine plugin handle from URL');
    }

    /**
     * Check permissions for accessing logs
     */
    private function _checkPermissions(array $permissions): void
    {
        if (empty($permissions)) {
            return; // No permissions required
        }

        $hasPermission = false;
        foreach ($permissions as $permission) {
            if (Craft::$app->getUser()->checkPermission($permission)) {
                $hasPermission = true;
                break;
            }
        }

        if (!$hasPermission) {
            throw new ForbiddenHttpException('User does not have permission to view logs');
        }
    }

    /**
     * Get log entries for a specific date with filtering and pagination
     */
    private function _getLogEntries(string $pluginHandle, string $date, string $level, string $search, int $page, int $limit): array
    {
        $logPath = Craft::$app->getPath()->getLogPath() . "/{$pluginHandle}-{$date}.log";

        if (!file_exists($logPath)) {
            return [];
        }

        $entries = [];
        $lineNumber = 0;
        $filteredEntries = [];

        // First pass: collect all filtered entries
        if (($handle = fopen($logPath, 'r')) !== false) {
            while (($line = fgets($handle)) !== false) {
                $lineNumber++;
                $entry = $this->_parseLogEntry(trim($line), $lineNumber);

                if (!$entry) {
                    continue;
                }

                // Filter by level
                if ($level !== 'all' && $entry['level'] !== $level) {
                    continue;
                }

                // Filter by search
                if ($search && stripos($entry['message'] . ' ' . $entry['context'], $search) === false) {
                    continue;
                }

                $filteredEntries[] = $entry;
            }
            fclose($handle);
        }

        // Reverse to show newest first
        $filteredEntries = array_reverse($filteredEntries);

        // Apply pagination
        $offset = ($page - 1) * $limit;
        return array_slice($filteredEntries, $offset, $limit);
    }

    /**
     * Get total count of log entries for pagination
     */
    private function _getLogEntriesCount(string $pluginHandle, string $date, string $level, string $search): int
    {
        $logPath = Craft::$app->getPath()->getLogPath() . "/{$pluginHandle}-{$date}.log";

        if (!file_exists($logPath)) {
            return 0;
        }

        $count = 0;

        if (($handle = fopen($logPath, 'r')) !== false) {
            while (($line = fgets($handle)) !== false) {
                $entry = $this->_parseLogEntry(trim($line));

                if (!$entry) {
                    continue;
                }

                // Filter by level
                if ($level !== 'all' && $entry['level'] !== $level) {
                    continue;
                }

                // Filter by search
                if ($search && stripos($entry['message'] . ' ' . $entry['context'], $search) === false) {
                    continue;
                }

                $count++;
            }
            fclose($handle);
        }

        return $count;
    }

    /**
     * Parse a log entry line
     */
    private function _parseLogEntry(string $line, int $lineNumber = 0): ?array
    {
        // Skip empty lines
        if (empty($line)) {
            return null;
        }

        // Parse log format: timestamp [user:id][level][category] message | context
        // Also handle format without context (message only)
        if (preg_match('/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\s+\[(.*?)\]\[(.*?)\]\[(.*?)\]\s+(.*?)(?:\s+\|\s+(.*))?$/', $line, $matches)) {
            return [
                'timestamp' => $matches[1],
                'user' => $matches[2],
                'level' => strtolower(str_replace('.', '', $matches[3])),
                'category' => $matches[4],
                'message' => $matches[5],
                'context' => isset($matches[6]) ? $matches[6] : '',
                'lineNumber' => $lineNumber,
                'raw' => $line,
            ];
        }

        // Fallback for non-standard format
        return [
            'timestamp' => '',
            'user' => '',
            'level' => 'unknown',
            'category' => '',
            'message' => $line,
            'context' => '',
            'lineNumber' => $lineNumber,
            'raw' => $line,
        ];
    }
}