<?php
/**
 * Logging Library settings for Craft CMS 5.x
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\models;

use craft\base\Model;
use lindemannrock\base\traits\SettingsConfigTrait;
use lindemannrock\base\traits\SettingsDisplayNameTrait;
use lindemannrock\base\traits\SettingsPersistenceTrait;
use lindemannrock\logginglibrary\LoggingLibrary;

/**
 * Logging Library Settings Model
 *
 * @since 5.8.0
 */
class Settings extends Model
{
    use SettingsConfigTrait;
    use SettingsDisplayNameTrait;
    use SettingsPersistenceTrait;

    /**
     * @var string Plugin display name shown in the control panel
     */
    public string $pluginName = 'Logging Library';

    /**
     * @var int Number of log entries to show per page
     */
    public int $itemsPerPage = 50;

    /**
     * @var bool Whether to show Logging Library in the main control panel menu
     */
    public bool $showCpSection = true;

    /**
     * @var bool Whether to force-enable file-based log viewers on edge/ephemeral environments
     */
    public bool $forceEnableLogViewer = false;

    /**
     * Database table name for settings persistence
     */
    protected static function tableName(): string
    {
        return 'logginglibrary_settings';
    }

    /**
     * Plugin handle for config file lookup
     */
    protected static function pluginHandle(): string
    {
        return 'logging-library';
    }

    /**
     * Integer fields for type casting from database
     */
    protected static function integerFields(): array
    {
        return ['itemsPerPage'];
    }

    /**
     * Boolean fields for type casting from database
     */
    protected static function booleanFields(): array
    {
        return ['showCpSection', 'forceEnableLogViewer'];
    }

    /**
     * Whether the current environment matches Logging Library's edge detection.
     */
    public function getEdgeEnvironmentDetected(): bool
    {
        return LoggingLibrary::isEdgeEnvironmentDetected();
    }

    /**
     * Whether file-based log viewers are available in the current environment.
     */
    public function getLogViewerAvailable(): bool
    {
        return LoggingLibrary::areLogViewersAvailable($this);
    }

    /**
     * Whether the standalone viewer is available as a surfaced CP feature.
     */
    public function getStandaloneViewerAvailable(): bool
    {
        return $this->showCpSection && $this->getLogViewerAvailable();
    }

    /**
     * Preserve the grammatically correct plural form.
     */
    public function getPluralDisplayName(): string
    {
        return 'Logging Libraries';
    }

    /**
     * Preserve the grammatically correct lowercase plural form.
     */
    public function getPluralLowerDisplayName(): string
    {
        return 'logging libraries';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['pluginName'], 'required'],
            [['pluginName'], 'string', 'max' => 255],
            [['itemsPerPage'], 'integer', 'min' => 10, 'max' => 500],
            [['itemsPerPage'], 'default', 'value' => 50],
            [['showCpSection'], 'boolean'],
            [['showCpSection'], 'default', 'value' => true],
            [['forceEnableLogViewer'], 'boolean'],
            [['forceEnableLogViewer'], 'default', 'value' => false],
        ];
    }
}
