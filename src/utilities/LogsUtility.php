<?php
/**
 * Logging Library for Craft CMS
 *
 * Logs utility for viewing all system logs
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2025 LindemannRock
 */

namespace lindemannrock\logginglibrary\utilities;

use Craft;
use craft\base\Utility;

/**
 * Logs Utility
 * Provides access to system-wide log viewer from Utilities menu
 */
class LogsUtility extends Utility
{
    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('app', 'Logs');
    }

    /**
     * @inheritdoc
     */
    public static function id(): string
    {
        return 'logging-library-logs';
    }

    /**
     * @inheritdoc
     */
    public static function iconPath(): ?string
    {
        return Craft::getAlias('@appicons/file-text.svg');
    }

    /**
     * @inheritdoc
     */
    public static function contentHtml(): string
    {
        // Redirect to the standalone logs viewer
        Craft::$app->getResponse()->redirect('logging-library/logs')->send();
        Craft::$app->end();
    }
}
