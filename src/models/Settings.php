<?php
/**
 * Logging Library settings for Craft CMS 5.x
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\models;

use Craft;
use craft\base\Model;
use lindemannrock\base\traits\DateFormatSettingsTrait;
use lindemannrock\base\traits\ItemsPerPageSettingsTrait;
use lindemannrock\base\traits\PluginNameSettingsTrait;
use lindemannrock\base\traits\SettingsConfigTrait;
use lindemannrock\base\traits\SettingsDisplayNameTrait;
use lindemannrock\base\traits\SettingsPersistenceTrait;
use lindemannrock\logginglibrary\helpers\RuntimeCategoryOptionsHelper;
use lindemannrock\logginglibrary\LoggingLibrary;

/**
 * Logging Library Settings Model
 *
 * @since 5.8.0
 */
class Settings extends Model
{
    use DateFormatSettingsTrait;
    use ItemsPerPageSettingsTrait;
    use PluginNameSettingsTrait;
    use SettingsConfigTrait {
        isOverriddenByConfig as private configOverridesAttribute;
    }
    use SettingsDisplayNameTrait;
    use SettingsPersistenceTrait;

    /**
     * @var string Plugin display name shown in the control panel
     */
    public string $pluginName = 'Logging Library';

    /**
     * @var bool Whether to show Logging Library in the main control panel menu
     */
    public bool $showCpSection = true;

    /**
     * @var bool Whether to force-enable file-based log viewers on edge/ephemeral environments
     */
    public bool $forceEnableLogViewer = false;

    /**
     * @var bool Persisted Runtime Logs option; nested runtimeLogStore config takes precedence.
     * @since 5.19.0
     */
    public bool $runtimeEnabled = false;

    /**
     * @var bool Persisted Runtime Logs option; nested runtimeLogStore config takes precedence.
     * @since 5.19.0
     */
    public bool $runtimeSkipConsoleRequests = true;

    /**
     * @var bool Persisted Runtime Logs option; nested runtimeLogStore config takes precedence.
     * @since 5.19.0
     */
    public bool $runtimeSkipQueueRequests = true;

    /**
     * @var int Persisted Runtime Logs option; nested runtimeLogStore config takes precedence.
     * @since 5.19.0
     */
    public int $runtimeTtl = 86400;

    /**
     * @var int Persisted Runtime Logs option; nested runtimeLogStore config takes precedence.
     * @since 5.19.0
     */
    public int $runtimeMaxEntries = 1000;

    /**
     * @var int Persisted Runtime Logs option; nested runtimeLogStore config takes precedence.
     * @since 5.19.0
     */
    public int $runtimeRefreshInterval = 5;

    /**
     * @var int Persisted Runtime Logs option; nested runtimeLogStore config takes precedence.
     * @since 5.19.0
     */
    public int $runtimeMaxMessageBytes = 8000;

    /**
     * @var int Persisted Runtime Logs option; nested runtimeLogStore config takes precedence.
     * @since 5.19.0
     */
    public int $runtimeMaxContextBytes = 8000;

    /**
     * @var array Persisted Runtime Logs option; nested runtimeLogStore config takes precedence.
     * @since 5.19.0
     */
    public array $runtimeLevels = ['error', 'warning', 'info'];

    /**
     * @var array Persisted Runtime Logs option; nested runtimeLogStore config takes precedence.
     * @since 5.19.0
     */
    public array $runtimeIncludeCategories = [];

    /**
     * @var array Persisted Runtime Logs option; nested runtimeLogStore config takes precedence.
     * @since 5.19.0
     */
    public array $runtimeExcludeCategories = [];

    /**
     * @var bool Persisted Runtime Logs option; nested runtimeLogStore config takes precedence.
     * @since 5.19.0
     */
    public bool $runtimeIncludeUserId = false;

    /**
     * Persisted attributes mapped to the existing public configuration contract.
     *
     * @since 5.19.0
     */
    public const RUNTIME_FIELDS = [
        'runtimeEnabled' => 'enabled',
        'runtimeSkipConsoleRequests' => 'skipConsoleRequests',
        'runtimeSkipQueueRequests' => 'skipQueueRequests',
        'runtimeTtl' => 'ttl',
        'runtimeMaxEntries' => 'maxEntries',
        'runtimeRefreshInterval' => 'refreshInterval',
        'runtimeMaxMessageBytes' => 'maxMessageBytes',
        'runtimeMaxContextBytes' => 'maxContextBytes',
        'runtimeLevels' => 'levels',
        'runtimeIncludeCategories' => 'includeCategories',
        'runtimeExcludeCategories' => 'excludeCategories',
        'runtimeIncludeUserId' => 'privacy.includeUserId',
    ];

    /**
     * @inheritdoc
     */
    public function isOverriddenByConfig(string $attribute): bool
    {
        $path = self::RUNTIME_FIELDS[$attribute] ?? null;
        return $this->configOverridesAttribute($path === null ? $attribute : 'runtimeLogStore.' . $path);
    }

    /**
     * Database preferences before per-option configuration overrides.
     *
     * @since 5.19.0
     */
    public function getStoredRuntimeConfig(): array
    {
        $config = [];
        foreach (self::RUNTIME_FIELDS as $attribute => $path) {
            if ($path === 'privacy.includeUserId') {
                $config['privacy']['includeUserId'] = $this->$attribute;
            } else {
                $config[$path] = $this->$attribute;
            }
        }
        return $config;
    }

    /**
     * Effective values for the CP, including per-option nested overrides.
     *
     * @since 5.19.0
     */
    public function getRuntimeConfig(): array
    {
        return LoggingLibrary::getRuntimeLogStoreConfig($this);
    }

    /**
     * Readable source choices and preserved custom category patterns for the CP.
     *
     * @since 5.19.0
     */
    public function getRuntimeCategoryPicker(array $patterns): array
    {
        return RuntimeCategoryOptionsHelper::capturePicker($patterns);
    }

    /**
     * Configured storage metadata, not a connectivity or durability guarantee.
     *
     * @since 5.19.0
     */
    public function getRuntimeStorage(): array
    {
        return LoggingLibrary::getInstance()->runtimeLogStore->getStorageStatus($this->getRuntimeConfig());
    }

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
        return ['itemsPerPage', 'runtimeTtl', 'runtimeMaxEntries', 'runtimeRefreshInterval', 'runtimeMaxMessageBytes', 'runtimeMaxContextBytes'];
    }

    /**
     * Boolean fields for type casting from database
     */
    protected static function booleanFields(): array
    {
        return ['showCpSection', 'forceEnableLogViewer', 'showSeconds', 'runtimeEnabled', 'runtimeSkipConsoleRequests', 'runtimeSkipQueueRequests', 'runtimeIncludeUserId'];
    }

    /**
     * JSON list preferences retain their ordering and replace configured lists.
     */
    protected static function jsonFields(): array
    {
        return ['runtimeLevels', 'runtimeIncludeCategories', 'runtimeExcludeCategories'];
    }

    /**
     * Only these date-format fields have columns in this plugin's settings table.
     */
    protected static function excludeFromSave(): array
    {
        return ['monthFormat', 'dateOrder', 'dateSeparator'];
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
        return $this->showCpSection && ($this->getLogViewerAvailable() || (bool)$this->getRuntimeConfig()['enabled']);
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
        return array_merge([
            [['runtimeEnabled', 'runtimeSkipConsoleRequests', 'runtimeSkipQueueRequests', 'runtimeIncludeUserId'], 'boolean'],
            [['runtimeTtl'], 'integer', 'min' => 1, 'max' => 2592000],
            [['runtimeMaxEntries'], 'integer', 'min' => 1, 'max' => 10000],
            [['runtimeRefreshInterval'], 'integer', 'min' => 0, 'max' => 3600],
            [['runtimeMaxMessageBytes', 'runtimeMaxContextBytes'], 'integer', 'min' => 1, 'max' => \lindemannrock\logginglibrary\services\RuntimeLogStoreService::MAX_BYTES_LIMIT],
            [['runtimeLevels'], 'required'],
            [['runtimeLevels'], 'each', 'rule' => ['in', 'range' => ['error', 'warning', 'info', 'trace'], 'skipOnEmpty' => false]],
            [['runtimeIncludeCategories', 'runtimeExcludeCategories'], 'each', 'rule' => ['string', 'max' => 255, 'skipOnEmpty' => false]],
            [['showCpSection'], 'boolean'],
            [['showCpSection'], 'default', 'value' => true],
            [['forceEnableLogViewer'], 'boolean'],
            [['forceEnableLogViewer'], 'default', 'value' => false],
        ], $this->pluginNameSettingsRules(), $this->itemsPerPageSettingsRules(), $this->dateFormatSettingsRules());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return array_merge([
            'showCpSection' => Craft::t('logging-library', 'Show Main Menu'),
            'forceEnableLogViewer' => Craft::t('logging-library', 'Force Enable File Log Viewers'),
            'runtimeEnabled' => Craft::t('logging-library', 'Enable Runtime Logs'),
            'runtimeSkipConsoleRequests' => Craft::t('logging-library', 'Skip Console Requests'),
            'runtimeSkipQueueRequests' => Craft::t('logging-library', 'Skip Queue Requests'),
            'runtimeTtl' => Craft::t('logging-library', 'Retention (seconds)'),
            'runtimeMaxEntries' => Craft::t('logging-library', 'Maximum Entries'),
            'runtimeRefreshInterval' => Craft::t('logging-library', 'Refresh Interval (seconds)'),
            'runtimeMaxMessageBytes' => Craft::t('logging-library', 'Maximum Message Bytes'),
            'runtimeMaxContextBytes' => Craft::t('logging-library', 'Maximum Context Bytes'),
            'runtimeLevels' => Craft::t('logging-library', 'Captured Levels'),
            'runtimeIncludeCategories' => Craft::t('logging-library', 'Include Sources and Categories'),
            'runtimeExcludeCategories' => Craft::t('logging-library', 'Exclude Sources and Categories'),
            'runtimeIncludeUserId' => Craft::t('logging-library', 'Include Request User ID'),
        ], $this->pluginNameSettingsLabel(), $this->itemsPerPageSettingsLabel(), $this->dateFormatSettingsLabels());
    }
}
