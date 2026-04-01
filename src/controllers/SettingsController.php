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
        if ($settings instanceof Settings && !$settings->getStandaloneViewerAvailable()) {
            return $this->redirect('logging-library/settings/general');
        }

        return $this->renderTemplate('logging-library/settings/interface', [
            'settings' => $settings,
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
        $integerAssignmentErrors = [];

        foreach ($settingsData as $key => $value) {
            if ($settings->isOverriddenByConfig($key) || !property_exists($settings, $key)) {
                continue;
            }

            if ($key === 'itemsPerPage') {
                $normalized = $this->_normalizeIntegerSettingValue($value);
                if ($normalized === null) {
                    $integerAssignmentErrors[$key] = Craft::t('logging-library', 'Value must be a whole number.');
                    continue;
                }
                $value = $normalized;
            }

            $settings->$key = $value;
        }

        $section = $this->_validSettingsSection($this->request->getBodyParam('section', 'general'));
        if ($section === 'interface' && !$settings->getStandaloneViewerAvailable()) {
            return $this->redirect('logging-library/settings/general');
        }

        $attributesToValidate = match ($section) {
            'general' => ['pluginName', 'showCpSection', 'forceEnableLogViewer'],
            'interface' => ['itemsPerPage'],
            default => ['pluginName'],
        };
        $attributesToValidate = array_values(array_filter(
            $attributesToValidate,
            fn(string $attribute): bool => !$settings->isOverriddenByConfig($attribute),
        ));

        $isValid = $settings->validate($attributesToValidate);
        foreach ($integerAssignmentErrors as $attribute => $message) {
            if (in_array($attribute, $attributesToValidate, true)) {
                $settings->addError($attribute, $message);
            }
        }

        if (!$isValid || $settings->hasErrors()) {
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
     * Normalize an integer setting from posted form input.
     */
    private function _normalizeIntegerSettingValue(mixed $value): ?int
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_string($value)) {
            $value = trim($value);
            if ($value === '') {
                return null;
            }
        }

        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            return null;
        }

        return (int)$value;
    }

    /**
     * Restrict settings sections to known templates.
     */
    private function _validSettingsSection(string $section): string
    {
        return in_array($section, ['general', 'interface'], true) ? $section : 'general';
    }
}
