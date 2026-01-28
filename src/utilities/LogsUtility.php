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
use lindemannrock\logginglibrary\LoggingLibrary;
use yii\web\ForbiddenHttpException;

/**
 * Logs Utility
 * Provides access to system-wide log viewer from Utilities menu
 *
 * @since 1.0.0
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
        $user = Craft::$app->getUser();
        if (!$user->getIsAdmin() && !$user->checkPermission(LoggingLibrary::PERMISSION_VIEW_ALL_LOGS)) {
            throw new ForbiddenHttpException('User does not have permission to view logs');
        }

        $canDownload = $user->getIsAdmin() || $user->checkPermission(LoggingLibrary::PERMISSION_DOWNLOAD_ALL_LOGS);

        $viewModel = LoggingLibrary::getInstance()->logsView->buildViewModel(
            Craft::$app->getRequest(),
            'logging-library',
            'All Logs',
            true,
            50,
            $canDownload,
            null
        );

        return Craft::$app->getView()->renderTemplate('logging-library/logs/utility', $viewModel);
    }
}
