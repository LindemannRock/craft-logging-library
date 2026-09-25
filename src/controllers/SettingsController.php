<?php
/**
 * Logging Library settings controller for Craft CMS 5.x
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

namespace lindemannrock\logginglibrary\controllers;

use Craft;
use craft\web\Controller;
use lindemannrock\base\helpers\SettingsPostHelper;
use lindemannrock\logginglibrary\helpers\RuntimeCategoryOptionsHelper;
use lindemannrock\logginglibrary\LoggingLibrary;
use lindemannrock\logginglibrary\models\Settings;
use yii\web\Response;

/**
 * Settings Controller
 *
 * @since 5.8.0
 */
class SettingsController extends Controller
{
    /**
     * Settings index
     */
    public function actionIndex(): Response
    {
        return $this->redirect('logging-library/settings/general');
    }

    /**
     * General settings
     */
    public function actionGeneral(): Response
    {
        $this->requirePermission(LoggingLibrary::PERMISSION_MANAGE_SETTINGS);

        return $this->renderTemplate('logging-library/settings/general', [
            'settings' => LoggingLibrary::getInstance()->getSettings(),
        ]);
    }

    /**
     * Interface settings
     */
    public function actionInterface(): Response
    {
        $this->requirePermission(LoggingLibrary::PERMISSION_MANAGE_SETTINGS);

        $settings = LoggingLibrary::getInstance()->getSettings();
        return $this->renderTemplate('logging-library/settings/interface', [
            'settings' => $settings,
        ]);
    }

    /**
     * Runtime capture preferences, independent of file-viewer availability.
     *
     * @since 5.19.0
     */
    public function actionRuntime(): Response
    {
        $this->requirePermission(LoggingLibrary::PERMISSION_MANAGE_SETTINGS);
        return $this->renderTemplate('logging-library/settings/runtime', [
            'settings' => LoggingLibrary::getInstance()->getSettings(),
        ]);
    }

    /**
     * File-viewer availability and the existing deployment override.
     *
     * @since 5.19.0
     */
    public function actionFiles(): Response
    {
        $this->requirePermission(LoggingLibrary::PERMISSION_MANAGE_SETTINGS);
        return $this->renderTemplate('logging-library/settings/files', [
            'settings' => LoggingLibrary::getInstance()->getSettings(),
        ]);
    }

    /**
     * Save settings
     */
    public function actionSave(): ?Response
    {
        $this->requirePostRequest();
        $this->requirePermission(LoggingLibrary::PERMISSION_MANAGE_SETTINGS);

        $settings = Settings::loadFromDatabase();
        $settingsData = $this->request->getBodyParam('settings', []);
        $section = $this->_validSettingsSection($this->request->getBodyParam('section', 'general'));
        $result = SettingsPostHelper::apply(
            model: $settings,
            postedValues: is_array($settingsData) ? $settingsData : [],
            allowedAttributes: $this->_validationAttributesForSection($section),
            shouldSkipAttribute: fn(string $attribute): bool => $settings->isOverriddenByConfig($attribute),
            adapters: [
                'runtimeLevels' => static fn(mixed $value): mixed => $value === '' ? [] : $value,
                'runtimeIncludeCategories' => RuntimeCategoryOptionsHelper::capturePatterns(...),
                'runtimeExcludeCategories' => RuntimeCategoryOptionsHelper::capturePatterns(...),
            ],
        );

        $attributesToValidate = $result->attributesToValidate;

        $isValid = $settings->validate($attributesToValidate, false);

        if (!$isValid || $result->hasErrors || $settings->hasErrors()) {
            Craft::$app->getSession()->setError(Craft::t('logging-library', 'Could not save settings.'));

            return $this->renderTemplate("logging-library/settings/{$section}", [
                'settings' => $settings,
            ]);
        }

        if (!$settings->saveToDatabase($attributesToValidate)) {
            Craft::$app->getSession()->setError(Craft::t('logging-library', 'Could not save settings.'));
            return null;
        }

        Craft::$app->getSession()->setNotice(Craft::t('logging-library', 'Settings saved.'));

        return $this->redirectToPostedUrl();
    }

    /**
     * Get validation attributes for a settings section.
     *
     * @return array<string>
     */
    private function _validationAttributesForSection(string $section): array
    {
        return match ($section) {
            'general' => ['pluginName', 'showCpSection'],
            'files' => ['forceEnableLogViewer'],
            'runtime' => array_keys(Settings::RUNTIME_FIELDS),
            'interface' => ['itemsPerPage', 'timeFormat', 'showSeconds'],
            default => ['pluginName'],
        };
    }

    /**
     * Restrict settings sections to known templates.
     */
    private function _validSettingsSection(string $section): string
    {
        return in_array($section, ['general', 'runtime', 'files', 'interface'], true) ? $section : 'general';
    }
}
