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
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
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
        yield 'general' => ['actionGeneral', 'logging-library/settings/general'];
    }

    public function testWelcomeDestinationOpensGeneralSettingsWithoutSetupRoutes(): void
    {
        $source = file_get_contents(dirname(__DIR__, 2) . '/src/LoggingLibrary.php');
        self::assertIsString($source);
        self::assertStringContainsString("'ctaLabel' => Craft::t('logging-library', 'Settings')", $source);
        self::assertStringContainsString("'ctaUrl' => 'logging-library/settings'", $source);
        self::assertStringContainsString("'redirectUri' => 'logging-library/settings'", $source);

        $event = new RegisterUrlRulesEvent();
        $manager = new UrlManager();
        $manager->trigger(UrlManager::EVENT_REGISTER_CP_URL_RULES, $event);
        self::assertSame('logging-library/settings/index', $event->rules['logging-library/settings']);
        self::assertArrayNotHasKey('logging-library/setup', $event->rules);
        $controller = new SettingsPageController('settings', LoggingLibrary::getInstance());
        self::assertNull($controller->createAction('setup'));
        self::assertInstanceOf(Response::class, $controller->actionIndex());
        self::assertSame('logging-library/settings/general', $controller->redirectedUrl);
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
            self::assertStringContainsString('Choose sources by name', $html);
            self::assertSame(2.0, $xpath->evaluate('count(//select[@multiple])'));
            self::assertLessThan(strpos($html, 'Advanced'), strpos($html, 'id="runtimeSkipConsoleRequests"'));
            self::assertLessThan(strpos($html, 'Advanced'), strpos($html, 'id="runtimeSkipQueueRequests"'));
            self::assertLessThan(strpos($html, 'Advanced'), strpos($html, 'id="runtimeIncludeUserId"'));

            foreach ([[false, null, false], [true, null, true], [false, true, true], [true, false, false]] as [$stored, $override, $visible]) {
                Craft::$app->set('config', new RuntimePreferencesConfig(
                    $override === null ? [] : ['runtimeLogStore' => ['enabled' => $override]],
                ));
                $settings = new Settings([
                    'runtimeEnabled' => $stored,
                    'runtimeIncludeCategories' => ['my-plugin', 'yii\\db\\*'],
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
                self::assertSame('p_' . bin2hex('my-plugin'), $xpath->evaluate('string(//select[@id="runtimeIncludeCategories"]/option[@selected][1]/@value)'));
                self::assertSame('p_' . bin2hex('yii\\db\\*'), $xpath->evaluate('string(//select[@id="runtimeIncludeCategories"]/option[@selected][2]/@value)'));
                self::assertSame(1.0, $xpath->evaluate('count(//*[@id="runtime-storage-status"])'));
                self::assertSame(0.0, $xpath->evaluate('count(//*[@id="runtime-settings"]//*[@id="runtime-storage-status"])'));
                self::assertSame(1.0, $xpath->evaluate('count(//*[@id="runtimeEnabled"]/preceding::*[@id="runtime-storage-status"])'));
                self::assertStringContainsString('Capture new messages in Runtime Logs.', $xpath->evaluate('string(//*[@id="runtimeEnabled-instructions"])'));
                self::assertStringContainsString('Turning this off does not delete existing logs.', $xpath->evaluate('string(//*[@id="runtimeEnabled-instructions"])'));
                self::assertSame(0.0, $xpath->evaluate('count(//*[@id="runtime-settings"]//*[@id="runtimeEnabled-instructions"])'));
                self::assertStringNotContainsString('Capture new messages in Runtime Logs.', $xpath->evaluate('string(//*[@id="runtime-settings"])'));
                self::assertStringContainsString('in bytes rather than characters. Longer messages are shortened. File logs are unaffected.', $xpath->evaluate('string(//*[@id="runtimeMaxMessageBytes-instructions"])'));
                self::assertStringContainsString('such as error details and stack traces, in bytes after JSON encoding. Larger context is shortened. File logs are unaffected.', $xpath->evaluate('string(//*[@id="runtimeMaxContextBytes-instructions"])'));
                self::assertStringContainsString('Uses the application cache configuration.', $xpath->evaluate('string(//*[@id="runtime-storage-status"])'));
                self::assertStringContainsString('To verify capture, trigger a log message and check Runtime Logs.', $xpath->evaluate('string(//*[@id="runtime-storage-status"])'));
                self::assertStringNotContainsString('On multiple servers', $html);
            }
            foreach (['settings/general', 'settings/files', 'settings/interface', 'settings/runtime'] as $template) {
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

    public function testSettingsOrderAndEdgeNoticeLinkRenderInEveryLocale(): void
    {
        $view = Craft::$app->getView();
        $mode = $view->getTemplateMode();
        $request = Craft::$app->getRequest();
        $language = Craft::$app->language;
        $twigCache = null;
        try {
            $view->setTemplateMode(View::TEMPLATE_MODE_CP);
            $twigCache = $view->getTwig()->getCache();
            $view->getTwig()->setCache($this->createTrackedTempDirectory('ll-settings-twig-'));
            Craft::$app->set('request', new SettingsFormRequest());
            foreach (['en', 'de', 'fr', 'nl', 'es', 'ar', 'it', 'pt', 'ja', 'sv', 'da', 'no'] as $locale) {
                Craft::$app->language = $locale;
                $html = $view->getTwig()->load('logging-library/_layouts/settings')->renderBlock('sidebar', [
                    'selectedSettingsItem' => 'files',
                ]);
                $document = new \DOMDocument();
                $document->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_NOERROR | LIBXML_NOWARNING);
                $xpath = new \DOMXPath($document);
                foreach (['general', 'files', 'runtime', 'interface'] as $index => $section) {
                    self::assertStringEndsWith('/logging-library/settings/' . $section, $xpath->evaluate('string((//nav//a)[' . ($index + 1) . ']/@href)'));
                }
                self::assertStringEndsWith('/logging-library/settings/files', $xpath->evaluate('string(//nav//a[@class="sel"]/@href)'));
                foreach ([true, false] as $edge) {
                    $html = $view->renderTemplate('logging-library/_components/file-availability', [
                        'settings' => ['edgeEnvironmentDetected' => $edge],
                    ], View::TEMPLATE_MODE_CP);
                    $document->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_NOERROR | LIBXML_NOWARNING);
                    $xpath = new \DOMXPath($document);
                    self::assertSame($edge ? 1.0 : 0.0, $xpath->evaluate('count(//a)'));
                    self::assertStringNotContainsString('{runtimeLogs}', $html);
                    if ($edge) {
                        self::assertStringEndsWith('/logging-library/settings/runtime', $xpath->evaluate('string(//a/@href)'));
                        self::assertSame(Craft::t('logging-library', 'Runtime Logs'), $xpath->evaluate('string(//a)'));
                    }
                }
            }
        } finally {
            Craft::$app->language = $language;
            if ($twigCache !== null) {
                $view->getTwig()->setCache($twigCache);
            }
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
    public array|string|null $redirectedUrl = null;

    public function redirect($url, $statusCode = 302): Response
    {
        $this->redirectedUrl = $url;
        return new Response();
    }

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
