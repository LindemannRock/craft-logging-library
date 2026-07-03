<?php
/**
 * LindemannRock Logging Library
 *
 * @link      https://lindemannrock.com
 * @copyright Copyright (c) 2026 LindemannRock
 */

declare(strict_types=1);

namespace lindemannrock\logginglibrary\tests\Integration;

use lindemannrock\logginglibrary\LoggingLibrary;
use lindemannrock\logginglibrary\tests\TestCase;

/**
 * Pins the SQLite-backed CP viewer cache path.
 *
 * @since 5.9.0
 */
final class LogIndexedCacheTest extends TestCase
{
    public function testIndexedCacheAvailabilityReflectsPdoSqliteRuntime(): void
    {
        self::assertSame(
            class_exists(\PDO::class) && in_array('sqlite', \PDO::getAvailableDrivers(), true),
            LoggingLibrary::getInstance()->logCache::supportsIndexedCache(),
        );
    }

    public function testIndexedPageSortsAndPaginatesWithoutArrayQuery(): void
    {
        $path = $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . 'indexed-2026-05-25.log',
            "2026-05-25 10:00:00 [user:1][INFO][indexed] first\n" .
            "2026-05-25 10:00:00 [user:1][INFO][indexed] second\n" .
            "2026-05-25 10:00:01 [user:1][ERROR][indexed] third\n",
        );

        $page = LoggingLibrary::getInstance()->logCache->getLogPage($path, 'all', 'all', '', 'timestamp', 'desc', 1, 2);

        self::assertSame(3, $page['total']);
        self::assertSame(['third', 'second'], array_column($page['entries'], 'message'));
        self::assertSame([1, 2], array_column($page['entries'], 'lineNumber'));
        self::assertSame('error', $page['entries'][0]['canonicalLevel']);
        self::assertSame('lr-level-error', $page['entries'][0]['levelClass']);

        LoggingLibrary::getInstance()->logCache->invalidateLogCache($path);
    }

    public function testIndexedPageFiltersByLevelSearchAndCategory(): void
    {
        $path = $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . 'indexed-filters-2026-05-25.log',
            "2026-05-25 10:00:00 [web.INFO] [application] Request context:\n\$_GET = [\n    'needle' => 'yes'\n]\n" .
            "2026-05-25 10:00:01 [web.ERROR] [yii\\db\\Connection::open] Database failed\n" .
            "2026-05-25 10:00:02 [web.ERROR] [application] Needle failure\n",
        );

        $page = LoggingLibrary::getInstance()->logCache->getLogPage($path, 'error', 'application', 'Needle', 'timestamp', 'asc', 1, 10);

        self::assertSame(1, $page['total']);
        self::assertSame('application', $page['category']);
        self::assertSame(['Needle failure'], array_column($page['entries'], 'message'));

        $categoryOptions = $page['categoryOptions'];
        self::assertSame('all', $categoryOptions[0]['value']);
        self::assertSame('application', $categoryOptions[1]['value']);

        LoggingLibrary::getInstance()->logCache->invalidateLogCache($path);
    }

    public function testIndexedPageUsesTranslatedSystemLabelForLogsWithoutUser(): void
    {
        $path = $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . 'indexed-system-user-2026-05-25.log',
            "2026-05-25 10:00:00 [web.INFO] [application] System message\n",
        );

        $page = LoggingLibrary::getInstance()->logCache->getLogPage($path, 'all', 'all', '', 'timestamp', 'desc', 1, 10);

        self::assertSame('', $page['entries'][0]['user']);
        self::assertSame('System', $page['entries'][0]['userLabel']);

        LoggingLibrary::getInstance()->logCache->invalidateLogCache($path);
    }

    public function testIndexedSearchTreatsLikeWildcardsLiterally(): void
    {
        $path = $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . 'indexed-like-2026-05-25.log',
            "2026-05-25 10:00:00 [user:1][INFO][indexed] 100% complete\n" .
            "2026-05-25 10:00:01 [user:1][INFO][indexed] 1000 complete\n" .
            "2026-05-25 10:00:02 [user:1][INFO][indexed] user_name matched\n" .
            "2026-05-25 10:00:03 [user:1][INFO][indexed] username matched\n",
        );

        $percentPage = LoggingLibrary::getInstance()->logCache->getLogPage($path, 'all', 'all', '100%', 'timestamp', 'asc', 1, 10);
        self::assertSame(['100% complete'], array_column($percentPage['entries'], 'message'));

        $underscorePage = LoggingLibrary::getInstance()->logCache->getLogPage($path, 'all', 'all', 'user_name', 'timestamp', 'asc', 1, 10);
        self::assertSame(['user_name matched'], array_column($underscorePage['entries'], 'message'));

        LoggingLibrary::getInstance()->logCache->invalidateLogCache($path);
    }

    public function testIndexedSearchIncludesUserField(): void
    {
        $path = $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . 'indexed-user-search-2026-05-25.log',
            "2026-05-25 10:00:00 [user:12345][INFO][indexed] User-owned message\n" .
            "2026-05-25 10:00:01 [user:67890][INFO][indexed] Other user message\n",
        );

        $page = LoggingLibrary::getInstance()->logCache->getLogPage($path, 'all', 'all', 'user:12345', 'timestamp', 'asc', 1, 10);

        self::assertSame(1, $page['total']);
        self::assertSame(['User-owned message'], array_column($page['entries'], 'message'));

        LoggingLibrary::getInstance()->logCache->invalidateLogCache($path);
    }

    public function testIndexedPageNormalizesPhpTimestampsForSorting(): void
    {
        $path = $this->seedLogFile(
            self::TEST_HANDLE_PREFIX . 'indexed-phperrors.log',
            "[30-Apr-2026 19:46:49 UTC] PHP Fatal error: April error\n" .
            "[23-May-2026 09:52:47 UTC] PHP Fatal error: May error\n",
        );

        $page = LoggingLibrary::getInstance()->logCache->getLogPage($path, 'all', 'all', '', 'timestamp', 'desc', 1, 10);

        self::assertSame(['May error', 'April error'], array_column($page['entries'], 'message'));
        self::assertSame(['2026-05-23 09:52:47', '2026-04-30 19:46:49'], array_column($page['entries'], 'timestamp'));

        LoggingLibrary::getInstance()->logCache->invalidateLogCache($path);
    }
}
