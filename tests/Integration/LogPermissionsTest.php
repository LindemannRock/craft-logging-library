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
use craft\events\RegisterCacheOptionsEvent;
use craft\events\RegisterUserPermissionsEvent;
use craft\services\UserPermissions;
use craft\utilities\ClearCaches;
use craft\web\Response;
use lindemannrock\base\helpers\CpNavHelper;
use lindemannrock\logginglibrary\controllers\LogsController;
use lindemannrock\logginglibrary\LoggingLibrary;
use lindemannrock\logginglibrary\models\Settings;
use lindemannrock\logginglibrary\services\LogCacheService;
use lindemannrock\logginglibrary\services\RuntimeLogStoreService;
use lindemannrock\logginglibrary\tests\Support\RuntimePreferencesConfig;
use lindemannrock\logginglibrary\tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use yii\base\Event;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Independent viewing and destructive permissions across viewer entry points.
 *
 * @since 5.19.0
 */
final class LogPermissionsTest extends TestCase
{
    public function testPermissionTreeSeparatesFilesRuntimeAndSettings(): void
    {
        $event = new RegisterUserPermissionsEvent();
        Event::trigger(UserPermissions::class, UserPermissions::EVENT_REGISTER_PERMISSIONS, $event);
        $groups = array_filter($event->permissions, static fn(array $group): bool => isset($group['permissions'][LoggingLibrary::PERMISSION_VIEW_RUNTIME_LOGS]));
        self::assertCount(1, $groups);
        $permissions = array_values($groups)[0]['permissions'];
        self::assertSame([LoggingLibrary::PERMISSION_VIEW_ALL_LOGS, LoggingLibrary::PERMISSION_VIEW_RUNTIME_LOGS, LoggingLibrary::PERMISSION_MANAGE_SETTINGS], array_keys($permissions));
        self::assertSame('View all file logs', $permissions[LoggingLibrary::PERMISSION_VIEW_ALL_LOGS]['label']);
        self::assertSame([LoggingLibrary::PERMISSION_DOWNLOAD_ALL_LOGS, LoggingLibrary::PERMISSION_CLEAR_CACHE], array_keys($permissions[LoggingLibrary::PERMISSION_VIEW_ALL_LOGS]['nested']));
        self::assertSame([LoggingLibrary::PERMISSION_CLEAR_RUNTIME_LOGS], array_keys($permissions[LoggingLibrary::PERMISSION_VIEW_RUNTIME_LOGS]['nested']));
    }

    #[DataProvider('permissionProvider')]
    public function testNavigationAndMutationGuardsRespectIndependentGrants(array $permissions, bool $admin = false): void
    {
        $originals = [];
        $settingsProperty = new \ReflectionProperty(\craft\base\Plugin::class, '_settings');
        $originalSettings = $settingsProperty->getValue(LoggingLibrary::getInstance());
        foreach (['user', 'request', 'response', 'config'] as $id) {
            $originals[$id] = Craft::$app->get($id);
        }
        $user = $this->createMock(User::class);
        $user->method('getIsAdmin')->willReturn($admin);
        $user->method('checkPermission')->willReturnCallback(static fn(string $name): bool => $admin || in_array($name, $permissions, true));
        $canViewRuntime = $user->checkPermission(LoggingLibrary::PERMISSION_VIEW_RUNTIME_LOGS);
        $canClearRuntime = $canViewRuntime && $user->checkPermission(LoggingLibrary::PERMISSION_CLEAR_RUNTIME_LOGS);
        $canViewFiles = $user->checkPermission(LoggingLibrary::PERMISSION_VIEW_ALL_LOGS);
        $canClearFiles = $canViewFiles && $user->checkPermission(LoggingLibrary::PERMISSION_CLEAR_CACHE);
        $canDownload = $canViewFiles && $user->checkPermission(LoggingLibrary::PERMISSION_DOWNLOAD_ALL_LOGS);
        $store = $this->createMock(RuntimeLogStoreService::class);
        $stopAfterAuthorization = new \RuntimeException('Stop before session handling in the console harness.');
        $store->expects($canClearRuntime ? self::once() : self::never())->method('clear')->willThrowException($stopAfterAuthorization);
        $this->swapPluginComponent('logging-library', 'runtimeLogStore', $store);
        $file = 'lglib-test-permissions.log';
        $path = $this->seedLogFile($file);
        $cache = $this->createMock(LogCacheService::class);
        $cache->expects($canClearFiles ? self::once() : self::never())->method('invalidateLogCache')->with($path);
        $cache->method('getLogEntryCount')->willReturn(0);
        $this->swapPluginComponent('logging-library', 'logCache', $cache);
        $response = $this->getMockBuilder(Response::class)->onlyMethods(['sendFile'])->getMock();
        $response->expects($canDownload ? self::once() : self::never())->method('sendFile')->with($path, $file, self::isType('array'))->willReturnSelf();
        try {
            $settingsProperty->setValue(LoggingLibrary::getInstance(), new Settings(['forceEnableLogViewer' => true]));
            Craft::$app->set('user', $user);
            Craft::$app->set('request', new LogPermissionRequest($file));
            Craft::$app->set('response', $response);
            Craft::$app->set('config', new RuntimePreferencesConfig(['forceEnableLogViewer' => true, 'runtimeLogStore' => ['enabled' => true]]));
            $settings = new Settings(['forceEnableLogViewer' => true]);
            $sections = LoggingLibrary::getInstance()->getCpSections($settings);
            $navUser = $this->createMock(\craft\web\User::class);
            $navUser->method('checkPermission')->willReturnCallback($user->checkPermission(...));
            $nav = CpNavHelper::buildSubnav($navUser, $settings, $sections);
            self::assertSame($canViewFiles, isset($nav['all-logs']));
            self::assertSame($canViewRuntime, isset($nav['runtime-logs']));
            self::assertSame($user->checkPermission(LoggingLibrary::PERMISSION_MANAGE_SETTINGS), isset($nav['settings']));
            $event = new RegisterCacheOptionsEvent();
            Event::trigger(ClearCaches::class, ClearCaches::EVENT_REGISTER_CACHE_OPTIONS, $event);
            $options = array_filter($event->options, static fn(array $option): bool => ($option['key'] ?? null) === 'logging-library-cache');
            self::assertCount($canClearFiles ? 1 : 0, $options);

            $controller = $this->getMockBuilder(LogsController::class)
                ->setConstructorArgs(['logs', LoggingLibrary::getInstance()])
                ->onlyMethods(['redirectToPostedUrl'])->getMock();
            $controller->method('redirectToPostedUrl')->willReturn(new Response());
            foreach (['actionClearRuntime' => $canClearRuntime, 'actionRefreshCache' => $canClearFiles, 'actionDownload' => $canDownload] as $method => $allowed) {
                try {
                    self::assertInstanceOf(Response::class, $controller->$method());
                    self::assertTrue($allowed, $method . ' must reject missing permissions.');
                } catch (ForbiddenHttpException) {
                    self::assertFalse($allowed, $method . ' must allow the required pair.');
                } catch (\RuntimeException $exception) {
                    self::assertSame($stopAfterAuthorization, $exception);
                    self::assertSame('actionClearRuntime', $method);
                    self::assertTrue($allowed);
                }
            }
            // Disabled capture remains unavailable even with permission; denial
            // occurs before storage or Twig, so this never reads owner log data.
            Craft::$app->set('config', new RuntimePreferencesConfig(['runtimeLogStore' => ['enabled' => false]]));
            try {
                $controller->actionRuntimeData();
                self::fail('Disabled runtime data must not be served.');
            } catch (ForbiddenHttpException) {
                self::assertFalse($canViewRuntime);
            } catch (NotFoundHttpException) {
                self::assertTrue($canViewRuntime);
            }
        } finally {
            $settingsProperty->setValue(LoggingLibrary::getInstance(), $originalSettings);
            foreach ($originals as $id => $original) {
                Craft::$app->set($id, $original);
            }
        }
    }

    public static function permissionProvider(): iterable
    {
        yield 'none' => [[]];
        yield 'file viewer' => [[LoggingLibrary::PERMISSION_VIEW_ALL_LOGS]];
        yield 'file clearer' => [[LoggingLibrary::PERMISSION_VIEW_ALL_LOGS, LoggingLibrary::PERMISSION_CLEAR_CACHE]];
        yield 'file downloader' => [[LoggingLibrary::PERMISSION_VIEW_ALL_LOGS, LoggingLibrary::PERMISSION_DOWNLOAD_ALL_LOGS]];
        yield 'file download alone' => [[LoggingLibrary::PERMISSION_DOWNLOAD_ALL_LOGS]];
        yield 'runtime viewer' => [[LoggingLibrary::PERMISSION_VIEW_RUNTIME_LOGS]];
        yield 'runtime clearer' => [[LoggingLibrary::PERMISSION_VIEW_RUNTIME_LOGS, LoggingLibrary::PERMISSION_CLEAR_RUNTIME_LOGS]];
        yield 'runtime clear alone' => [[LoggingLibrary::PERMISSION_CLEAR_RUNTIME_LOGS]];
        yield 'file clear alone' => [[LoggingLibrary::PERMISSION_CLEAR_CACHE]];
        yield 'old clear with new view' => [[LoggingLibrary::PERMISSION_VIEW_RUNTIME_LOGS, LoggingLibrary::PERMISSION_CLEAR_CACHE]];
        yield 'new clear with old view' => [[LoggingLibrary::PERMISSION_VIEW_ALL_LOGS, LoggingLibrary::PERMISSION_CLEAR_RUNTIME_LOGS]];
        yield 'settings only' => [[LoggingLibrary::PERMISSION_MANAGE_SETTINGS]];
        yield 'administrator' => [[], true];
    }

    public function testSidebarRendersOnlyAuthorizedCacheAndRuntimeActions(): void
    {
        $request = Craft::$app->getRequest();
        $response = Craft::$app->getResponse();
        $view = Craft::$app->getView();
        $mode = $view->getTemplateMode();
        $twigCache = null;
        try {
            $formRequest = new LogPermissionFormRequest();
            $formRequest->setScriptUrl('/index.php');
            $formRequest->setUrl('/admin/logging-library');
            Craft::$app->set('request', $formRequest);
            Craft::$app->set('response', new Response());
            $view->setTemplateMode(\craft\web\View::TEMPLATE_MODE_CP);
            $twigCache = $view->getTwig()->getCache();
            $view->getTwig()->setCache($this->createTrackedTempDirectory('ll-permissions-twig-'));
            $template = $view->getTwig()->load('logging-library/logs/index');
            foreach ([false, true] as $runtime) {
                foreach ([false, true] as $allowed) {
                    $html = $template->renderBlock('sidebarContent', [
                        'isStandalone' => true, 'isRuntime' => $runtime,
                        'pluginHandle' => 'logging-library', 'logConfig' => null,
                        'logFiles' => [], 'selectedFile' => $runtime ? null : ['formattedSize' => '1 KB'],
                        'selectedStandaloneFile' => 'lglib-test-permissions.log',
                        'canDownload' => false, 'canRefreshCache' => $allowed,
                        'canClearRuntimeLogs' => $allowed, 'runtimeCurrentLevel' => 'info',
                        'levels' => ['info' => 'Info'],
                    ]);
                    self::assertSame(!$runtime && $allowed, str_contains($html, 'id="refresh-log-cache-form"'));
                    self::assertSame($runtime && $allowed, str_contains($html, 'value="logging-library/logs/clear-runtime"'));
                    self::assertSame(!$runtime && $allowed, str_contains($html, 'jQuery(refreshForm)'));
                }
            }
        } finally {
            if ($twigCache !== null) {
                $view->getTwig()->setCache($twigCache);
            }
            $view->setTemplateMode($mode);
            Craft::$app->set('request', $request);
            Craft::$app->set('response', $response);
        }
    }

    public function testIntegratedViewerRefreshRetainsItsOwnViewPermission(): void
    {
        $handle = 'lglib-test-permission-plugin';
        $date = '2026-09-25';
        $path = $this->seedLogFile("$handle-$date.log");
        $configs = new \ReflectionProperty(LoggingLibrary::class, '_pluginConfigs');
        $originalConfigs = $configs->getValue();
        $originalUser = Craft::$app->getUser();
        $originalRequest = Craft::$app->getRequest();
        $originalResponse = Craft::$app->getResponse();
        $cache = $this->createMock(LogCacheService::class);
        $cache->expects(self::once())->method('invalidateLogCache')->with($path);
        $cache->method('getLogEntryCount')->willReturn(0);
        $this->swapPluginComponent('logging-library', 'logCache', $cache);
        try {
            $configs->setValue(null, $originalConfigs + [$handle => [
                'enableLogViewer' => true, 'viewSystemLogsPermissions' => ['example:viewLogs'],
            ]]);
            Craft::$app->set('request', new LogPermissionRequest('', $handle, $date));
            Craft::$app->set('response', new Response());
            foreach ([false, true] as $canView) {
                $user = $this->createMock(User::class);
                $user->method('checkPermission')->willReturnCallback(static fn(string $name): bool => $canView && $name === 'example:viewLogs');
                Craft::$app->set('user', $user);
                try {
                    $response = (new LogsController('logs', LoggingLibrary::getInstance()))->actionRefreshCache();
                    self::assertTrue($canView);
                    self::assertTrue($response->data['success']);
                } catch (ForbiddenHttpException) {
                    self::assertFalse($canView);
                }
            }
        } finally {
            $configs->setValue(null, $originalConfigs);
            Craft::$app->set('user', $originalUser);
            Craft::$app->set('request', $originalRequest);
            Craft::$app->set('response', $originalResponse);
        }
    }
}

/**
 * Isolated JSON POST request for the permission matrix.
 *
 * @since 5.19.0
 */
final class LogPermissionRequest extends Request
{
    public function __construct(private string $filename, private string $handle = 'logging-library', private string $date = '')
    {
        parent::__construct();
    }

    public function getIsPost(): bool
    {
        return true;
    }

    public function getAcceptsJson(): bool
    {
        return true;
    }

    public function getRequiredParam(string $name): mixed
    {
        return match ($name) {
            'pluginHandle' => $this->handle,
            'file' => $this->filename,
            'date' => $this->date,
            default => throw new \InvalidArgumentException($name),
        };
    }
}

/**
 * Render forms without accessing a real browser session or CSRF cookie.
 *
 * @since 5.19.0
 */
final class LogPermissionFormRequest extends \craft\web\Request
{
    public function getCsrfToken($regenerate = false): string
    {
        return 'lglib-test-owned-csrf';
    }
}
