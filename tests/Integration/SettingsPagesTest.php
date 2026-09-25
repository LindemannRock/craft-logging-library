<?php
/**
 * @link https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Integration;

use Craft;
use craft\console\Request;
use craft\console\User;
use craft\web\View;
use lindemannrock\logginglibrary\controllers\SettingsController;
use lindemannrock\logginglibrary\LoggingLibrary;
use lindemannrock\logginglibrary\models\Settings;
use lindemannrock\logginglibrary\tests\Support\RuntimePreferencesConfig;
use lindemannrock\logginglibrary\tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Settings permissions and actual Twig form rendering.
 *
 * @since 5.19.0
 */
final class SettingsPagesTest extends TestCase
{
    #[DataProvider('settingsActionProvider')]
    public function testSettingsPagesRequireManageSettings(string $action, string $template): void
    {
        $original = Craft::$app->getUser();
        $user = new SettingsPermissionUser();
        try {
            Craft::$app->set('user', $user);
            $controller = new SettingsPageController('settings', LoggingLibrary::getInstance());
            try {
                $controller->$action();
                self::fail('A viewer-only user must not access settings.');
            } catch (ForbiddenHttpException) {
                self::assertNull($controller->renderedTemplate);
            }
            $user->manageSettings = true;
            self::assertInstanceOf(Response::class, $controller->$action());
            self::assertSame($template, $controller->renderedTemplate);
        } finally {
            Craft::$app->set('user', $original);
        }
    }

    public static function settingsActionProvider(): iterable
    {
        yield 'runtime' => ['actionRuntime', 'logging-library/settings/runtime'];
        yield 'files' => ['actionFiles', 'logging-library/settings/files'];
        yield 'interface' => ['actionInterface', 'logging-library/settings/interface'];
        yield 'setup' => ['actionSetup', 'logging-library/setup'];
    }

    public function testRuntimeFormRendersPerOptionConfigLocksAndEditableStoredValues(): void
    {
        $view = Craft::$app->getView();
        $mode = $view->getTemplateMode();
        $request = Craft::$app->getRequest();
        $config = Craft::$app->getConfig();
        $twigCache = null;
        try {
            $view->setTemplateMode(View::TEMPLATE_MODE_CP);
            $twigCache = $view->getTwig()->getCache();
            $view->getTwig()->setCache($this->createTrackedTempDirectory('ll-settings-twig-'));
            Craft::$app->set('request', new SettingsFormRequest());
            Craft::$app->set('config', new RuntimePreferencesConfig([
                'runtimeLogStore' => ['levels' => ['warning'], 'maxEntries' => 8000],
            ]));
            $settings = new Settings(['runtimeTtl' => 321]);
            $html = $view->renderString(
                '{% import "logging-library/settings/runtime" as runtimeForms %}' .
                '{{ runtimeForms.runtimeField(settings, settings.runtimeConfig, "runtimeLevels", "levels", "levels") }}' .
                '{{ runtimeForms.runtimeField(settings, settings.runtimeConfig, "runtimeTtl", "ttl", "number") }}',
                ['settings' => $settings],
                View::TEMPLATE_MODE_CP,
            );
            self::assertSame(4, substr_count($html, 'type="checkbox"'));
            self::assertSame(4, preg_match_all('/<input[^>]*type="checkbox"[^>]*disabled/s', $html));
            self::assertStringContainsString('runtimeLogStore.levels', $html);
            self::assertStringContainsString('value="321"', $html);

            $template = $view->getTwig()->load('logging-library/settings/runtime');
            $html = $template->renderBlock('content', [
                'settings' => $settings,
                'runtime' => $settings->getRuntimeConfig(),
                'title' => 'Runtime Logs',
            ]);
            self::assertStringContainsString("id='runtime-settings' class='hidden'", $html);
            self::assertStringContainsString('data-target="runtime-settings"', $html);
            self::assertStringContainsString('Current: <strong id="runtimeTtl-human"></strong>', $html);
            self::assertStringContainsString('Min: 1 (1 second), Max: 2592000 (30 days)', $html);
            $document = new \DOMDocument();
            $document->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
            $xpath = new \DOMXPath($document);
            foreach ([
                'runtimeMaxEntries' => [1, 10000],
                'runtimeMaxMessageBytes' => [1, 65536],
                'runtimeMaxContextBytes' => [1, 65536],
            ] as $attribute => [$minimum, $maximum]) {
                self::assertSame((string)$minimum, $xpath->evaluate('string(//*[@id="' . $attribute . '"]/@min)'));
                self::assertSame((string)$maximum, $xpath->evaluate('string(//*[@id="' . $attribute . '"]/@max)'));
                self::assertStringContainsString(
                    'Min: ' . $minimum . ', Max: ' . $maximum,
                    $xpath->evaluate('string(//*[@id="' . $attribute . '-field"])'),
                );
            }
            self::assertSame('0', $xpath->evaluate('string(//*[@id="runtimeRefreshInterval"]/@min)'));
            self::assertSame('3600', $xpath->evaluate('string(//*[@id="runtimeRefreshInterval"]/@max)'));
            self::assertStringContainsString('Min: 0 (Disabled), Max: 3600 (1 hour)', $xpath->evaluate('string(//*[@id="runtimeRefreshInterval-field"])'));
            self::assertStringContainsString('Current: <strong id="runtimeRefreshInterval-human"></strong>', $html);
            self::assertStringContainsString('<code>yii\\db\\*</code>', $html);
            self::assertStringContainsString('<code>yii\\db\\</code>', $html);
            self::assertStringContainsString('not words in the message', $html);
            self::assertLessThan(strpos($html, 'Advanced'), strpos($html, 'id="runtimeSkipConsoleRequests"'));
            self::assertLessThan(strpos($html, 'Advanced'), strpos($html, 'id="runtimeSkipQueueRequests"'));
            self::assertLessThan(strpos($html, 'Advanced'), strpos($html, 'id="runtimeIncludeUserId"'));

            foreach ([[false, null, false], [true, null, true], [false, true, true], [true, false, false]] as [$stored, $override, $visible]) {
                Craft::$app->set('config', new RuntimePreferencesConfig(
                    $override === null ? [] : ['runtimeLogStore' => ['enabled' => $override]],
                ));
                $settings = new Settings([
                    'runtimeEnabled' => $stored,
                    'runtimeCategories' => ['my-plugin', 'yii\\db\\*'],
                ]);
                $html = $template->renderBlock('content', [
                    'settings' => $settings,
                    'runtime' => $settings->getRuntimeConfig(),
                    'title' => 'Runtime Logs',
                ]);
                $document = new \DOMDocument();
                $document->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
                $xpath = new \DOMXPath($document);
                self::assertSame($visible ? '' : 'hidden', $xpath->evaluate('string(//*[@id="runtime-settings"]/@class)'));
                self::assertSame("my-plugin\nyii\\db\\*", $xpath->evaluate('string(//*[@id="runtime-settings"]//textarea[@id="runtimeCategories"])'));
                self::assertSame(1.0, $xpath->evaluate('count(//*[@id="runtime-storage-status"])'));
                self::assertSame(0.0, $xpath->evaluate('count(//*[@id="runtime-settings"]//*[@id="runtime-storage-status"])'));
                self::assertSame(1.0, $xpath->evaluate('count(//*[@id="runtimeEnabled"]/preceding::*[@id="runtime-storage-status"])'));
                self::assertStringContainsString('Capture changes apply to new requests.', $xpath->evaluate('string(//*[@id="runtime-settings"])'));
                self::assertStringContainsString('Uses the application cache configuration.', $xpath->evaluate('string(//*[@id="runtime-storage-status"])'));
                self::assertStringContainsString('To verify capture, trigger a log message and check Runtime Logs.', $xpath->evaluate('string(//*[@id="runtime-storage-status"])'));
                self::assertStringNotContainsString('On multiple servers', $html);
            }
            foreach (['setup', 'settings/general', 'settings/files', 'settings/interface', 'settings/runtime'] as $template) {
                self::assertNotNull($view->getTwig()->load('logging-library/' . $template));
            }
        } finally {
            if ($twigCache !== null) {
                $view->getTwig()->setCache($twigCache);
            }
            Craft::$app->set('config', $config);
            Craft::$app->set('request', $request);
            $view->setTemplateMode($mode);
        }
    }
}

/**
 * @since 5.19.0
 */
final class SettingsPermissionUser extends User
{
    public bool $manageSettings = false;

    public function checkPermission(string $permissionName): bool
    {
        return $permissionName === LoggingLibrary::PERMISSION_MANAGE_SETTINGS
            ? $this->manageSettings
            : $permissionName === LoggingLibrary::PERMISSION_VIEW_ALL_LOGS;
    }
}

/**
 * @since 5.19.0
 */
final class SettingsPageController extends SettingsController
{
    public ?string $renderedTemplate = null;

    public function renderTemplate(string $template, array $variables = [], ?string $templateMode = null): Response
    {
        $this->renderedTemplate = $template;
        return new Response();
    }
}

/**
 * Keep the test application in console mode while rendering Craft form macros.
 *
 * @since 5.19.0
 */
final class SettingsFormRequest extends Request
{
    public function isMobileBrowser(bool $includeTablets = false): bool
    {
        return false;
    }
}
