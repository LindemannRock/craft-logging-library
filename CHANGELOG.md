# Changelog

## [5.15.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.14.0...v5.15.0) (2026-07-05)


### Added

* **controllers:** add categoryLabel to log page data and improve source display names ([0523380](https://github.com/LindemannRock/craft-logging-library/commit/0523380bdc4fb657ac64b3e64e2348636bc3f1a2))
* **logging:** enhance runtime log category handling and labels ([122a89b](https://github.com/LindemannRock/craft-logging-library/commit/122a89bf5e02d56fb88d75647c3d3c0a9ba2634b))
* **logs:** improve category label handling in runtime log rows ([ae6100a](https://github.com/LindemannRock/craft-logging-library/commit/ae6100a7f385f0766c15c0844d1c47c72b1231e2))


### Fixed

* **logs:** use row toggle function from base ([77dc6e1](https://github.com/LindemannRock/craft-logging-library/commit/77dc6e15a4bf988711550ac7e8fc7e69f1826c42))

## [5.14.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.13.1...v5.14.0) - 2026-07-03


### Added

* add max message and context byte limits to runtime config ([82123bd](https://github.com/LindemannRock/craft-logging-library/commit/82123bd4e41f0a3abb2353754a3d8bfae07acca0))
* add TTL filtering for runtime log records ([4c576c3](https://github.com/LindemannRock/craft-logging-library/commit/4c576c3993437c65d6ad51bebdddc0f35e551575))
* cache all log files directory listing with TTL ([435d04c](https://github.com/LindemannRock/craft-logging-library/commit/435d04ce6a89eeb821fd241de9b4c15828f14d0d))
* enhance log pagination and user label attachment ([73a0980](https://github.com/LindemannRock/craft-logging-library/commit/73a098056c9578a05e410545b76282db9b310c29))
* **helpers:** add LogLevelHelper for canonicalizing log levels ([0433c8b](https://github.com/LindemannRock/craft-logging-library/commit/0433c8b32e7e16c5a70f18069d3f01d605840ede))
* **helpers:** add UserLabelHelper to attach user labels to log entries ([6a4a409](https://github.com/LindemannRock/craft-logging-library/commit/6a4a409fcd2c8acf51e3ccf625089c31bd7ea307))
* **helpers:** sort category options in a case-insensitive manner ([b687224](https://github.com/LindemannRock/craft-logging-library/commit/b687224cecc1e499f3c664083486ba5f7db9fb8a))
* **i18n:** add 'Plugins' translation key across multiple locales ([1a937be](https://github.com/LindemannRock/craft-logging-library/commit/1a937be3019e436fbbe15374f81c514d06b86501))
* **i18n:** add 'Request User' translation across multiple locales ([685edbc](https://github.com/LindemannRock/craft-logging-library/commit/685edbc786be45b46d33acedf8e9b9c27d4c78c4))
* **i18n:** add 'unknown' category and level translations ([8ddbd4a](https://github.com/LindemannRock/craft-logging-library/commit/8ddbd4a2ae577f46be495cbc752bdad8e4b6d88c))
* **i18n:** add runtime log translations across multiple locales ([e83506e](https://github.com/LindemannRock/craft-logging-library/commit/e83506e95fd75fda3c1262457509d3df1392177d))
* **logging:** add edge environment detection and logging handle auto-detection tests ([ed15b7d](https://github.com/LindemannRock/craft-logging-library/commit/ed15b7d41e02d2c74ce6065e855d6102abe41c2a))
* **logging:** enhance runtime log store with error handling and category options ([9d3330b](https://github.com/LindemannRock/craft-logging-library/commit/9d3330b3a16a03e5ba1d6dc9271d1887b48564f8))
* **logging:** implement caching for log file retrieval ([8489fc3](https://github.com/LindemannRock/craft-logging-library/commit/8489fc3792ab0b0f076331de741c3fe378fae464))
* **logging:** treat trace level as debug for sorting and stats ([c139077](https://github.com/LindemannRock/craft-logging-library/commit/c1390777fbbb1a519fe3021df14316f914bc98cb))
* **logs:** add colspan attribute to log entry and empty row templates ([eb2755c](https://github.com/LindemannRock/craft-logging-library/commit/eb2755c95778c20cd968df879ed12234e6178c43))
* **logs:** add getLogEntryCount method to LogCacheService ([118e8a5](https://github.com/LindemannRock/craft-logging-library/commit/118e8a5c2cc643a1faf9ccfb7e3b0160041ae66f))
* **logs:** add permission check for clearing runtime logs ([cb197a6](https://github.com/LindemannRock/craft-logging-library/commit/cb197a650e29056d64aa8a642d1859bd5a5aff1e))
* **logs:** add runtime Redis cache usage detection in logs index ([07db95e](https://github.com/LindemannRock/craft-logging-library/commit/07db95e714815147fdb3a7b6ad68a71248758405))
* **logs:** add runtime stored total to log entries and update messages ([6b57dff](https://github.com/LindemannRock/craft-logging-library/commit/6b57dff105ad3b638b19795c0073b273ddabc47c))
* **logs:** add sorting functionality with sequence tiebreaker for log entries ([39d18cb](https://github.com/LindemannRock/craft-logging-library/commit/39d18cb5da95e964cad97aacba01dec1099b551f))
* **logs:** attach user labels to log entries without per-row queries ([7683c7b](https://github.com/LindemannRock/craft-logging-library/commit/7683c7bf2c29d5dfb15c6aac5274a912c215567f))
* **logs:** replace hardcoded log levels with dynamic labels from method ([f02cce0](https://github.com/LindemannRock/craft-logging-library/commit/f02cce0e21477aedd33ab0488123de4742029008))
* **logs:** sort dated log files by filename date before mtime ([ae71ff9](https://github.com/LindemannRock/craft-logging-library/commit/ae71ff90f48d20fc75b472d9977ac53529e259aa))
* **runtime:** replace getRecordCount with storedTotal in log page ([4e30c69](https://github.com/LindemannRock/craft-logging-library/commit/4e30c69e9db732ee9268c45e42a1872986e9fc5b))
* sort category counts using case-insensitive comparison ([2072910](https://github.com/LindemannRock/craft-logging-library/commit/20729106e06219dee620f5be0e3630fb35b08361))
* **tests:** add user label resolution and permission check for clearing runtime logs ([7feaeb1](https://github.com/LindemannRock/craft-logging-library/commit/7feaeb1cba344ee7a1cfc9ce03afed44532138aa))
* update category options to include formatted labels and counts ([97b7297](https://github.com/LindemannRock/craft-logging-library/commit/97b72972c3349afa7d325dae1250f848c1b4fcfe))


### Fixed

* correct copyright year in LoggingService.php ([ce27ead](https://github.com/LindemannRock/craft-logging-library/commit/ce27eadf1595fc3f85f80e4dedf7486802f772ed))
* escape message and context in runtime log templates ([af930d0](https://github.com/LindemannRock/craft-logging-library/commit/af930d029adddee1b6ab9b3370fbf18b5523724c))
* handle false value in value check condition ([28d0d49](https://github.com/LindemannRock/craft-logging-library/commit/28d0d4982f751465809855751a9db9e90a931b74))
* **i18n:** correct Portuguese translations for runtime logs ([dec68e3](https://github.com/LindemannRock/craft-logging-library/commit/dec68e3471ab8e4695f18ffa34970c13b33764f5))
* increase export interval for runtime log target to improve performance ([352d188](https://github.com/LindemannRock/craft-logging-library/commit/352d1889a6eae63b884b51890237b63f30e1e639))
* **logging:** add error handling for JSON encoding in log messages ([428e291](https://github.com/LindemannRock/craft-logging-library/commit/428e29144527775fcedaf2c6a1a7547b8f930597))
* **logging:** correct cache type check for runtime logs ([beaa49f](https://github.com/LindemannRock/craft-logging-library/commit/beaa49f7ce9cd837a0d83b5f9571f466d70d8cc5))
* **logging:** remove case-insensitive sorting from category counts ([fa41cb1](https://github.com/LindemannRock/craft-logging-library/commit/fa41cb1abba9a0bf92ab5467cb0f88d5847c353f))
* **logs:** add confirmation dialog for clearing recent runtime logs ([2f28e98](https://github.com/LindemannRock/craft-logging-library/commit/2f28e9872392041dc6442a38092c1429ec5059cb))
* **logs:** correct user field for log entries without user information ([01b0dc9](https://github.com/LindemannRock/craft-logging-library/commit/01b0dc9f7c6dfda664a5063675435f719865f80c))
* **logs:** correct user label display for log entries ([f8f2efe](https://github.com/LindemannRock/craft-logging-library/commit/f8f2efefe97d7487efa1e496f5075cf4b65de45e))
* sort category options case insensitively in runtime log store ([01cfea4](https://github.com/LindemannRock/craft-logging-library/commit/01cfea4871bbaaed719e2866a23bb26a98584b7d))
* update copyright year in LoggingLibrary.php ([d42e1f7](https://github.com/LindemannRock/craft-logging-library/commit/d42e1f7b56afd8be51033b7ed210403f0c43e1f9))
* update copyright year in LoggingTrait.php ([320779f](https://github.com/LindemannRock/craft-logging-library/commit/320779f04e55b6b17d70070eee2b0d28b57ce57f))

## [5.13.1](https://github.com/LindemannRock/craft-logging-library/compare/v5.13.0...v5.13.1) - 2026-06-28


### Miscellaneous Chores

* require base 5.30 for table refresh fixes ([c6156b9](https://github.com/LindemannRock/craft-logging-library/commit/c6156b90747c0abbf0aa9e354fcead13cd4ad5b1))

## [5.13.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.12.0...v5.13.0) - 2026-06-24


### Added

* **cp:** add dynamic URL for logs navigation based on mode ([8a8fd85](https://github.com/LindemannRock/craft-logging-library/commit/8a8fd85d7f12c9f370d1fbe1469786dcf3d61b13))

## [5.12.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.11.0...v5.12.0) - 2026-06-23


### Added

* **logging:** classify undated source logs and recognize Monolog format ([1053bff](https://github.com/LindemannRock/craft-logging-library/commit/1053bffd964caaea576675957c61dbb352488c87))


### Fixed

* **logging:** correct regex for log message parsing ([16a7398](https://github.com/LindemannRock/craft-logging-library/commit/16a73986d1fc802e9985885f4cd83a605c93eb70))

## [5.11.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.10.1...v5.11.0) - 2026-06-23


### Added

* add smoke test and compatibility check scripts for Craft CMS ([b7e5026](https://github.com/LindemannRock/craft-logging-library/commit/b7e50267170d125819b8c761ba66904c1a939c95))

## [5.10.1](https://github.com/LindemannRock/craft-logging-library/compare/v5.10.0...v5.10.1) - 2026-06-18


### Fixed

* **i18n:** correct French translation for viewing system logs ([53c9fa7](https://github.com/LindemannRock/craft-logging-library/commit/53c9fa71a6da461531a02ea599972e613ccd5532))
* **i18n:** correct Spanish translations for log viewer messages and settings ([790309d](https://github.com/LindemannRock/craft-logging-library/commit/790309d06f2e24ae5bfe3bf118b96753a04c77ae))
* **i18n:** correct Spanish, Italian, and Portuguese translations for "All Sources" and "Current log level" ([ccbf747](https://github.com/LindemannRock/craft-logging-library/commit/ccbf7472ddcdf181c4497c405191f13048e22839))
* **i18n:** remove language notes for Arabic and Japanese translations ([0804f44](https://github.com/LindemannRock/craft-logging-library/commit/0804f44c47faf9c3e0306a8cc3d2dfe7691e3986))

## [5.10.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.9.0...v5.10.0) - 2026-06-07


### Added

* **i18n:** add loading message translations ([cfd4997](https://github.com/LindemannRock/craft-logging-library/commit/cfd4997f5abfd1440de672b89177316fe1d2db9c))
* **i18n:** add new controller messages and validation settings ([dafb444](https://github.com/LindemannRock/craft-logging-library/commit/dafb4449ae52611b35f65520520763a951400d95))
* **logs:** adjust timestamp column width based on selected file type ([e015b85](https://github.com/LindemannRock/craft-logging-library/commit/e015b850509838144a599f026bebc440056204cd))
* **tests:** add LogIndexedCacheTest for SQLite-backed log caching ([cb87cf2](https://github.com/LindemannRock/craft-logging-library/commit/cb87cf29cb5391d601eb21411c976b230e63799c))
* **tests:** add timestamp assertions and sorting for PHP error logs ([f8199c0](https://github.com/LindemannRock/craft-logging-library/commit/f8199c0a8f1f1d50fc729c4c703bf77ccf77d876))


### Fixed

* correct copyright year in LogsController and enhance parameter validation ([1405eea](https://github.com/LindemannRock/craft-logging-library/commit/1405eea614e461643512c537adc3a8898276a7a9))
* correct loading message translation in log viewer ([42be275](https://github.com/LindemannRock/craft-logging-library/commit/42be27506a035c0ed8a4707b1979f3b84b7b991e))
* **i18n:** correct log translations from "logs" to "registos" ([e832c10](https://github.com/LindemannRock/craft-logging-library/commit/e832c103bb562cfe3a1071f75f0d2bef44d54fd1))
* **logs:** change timestamp format to use 'cascade' instead of 'short' ([7038076](https://github.com/LindemannRock/craft-logging-library/commit/7038076f5aa846fd4bcf945e8bbaa49a9c754522))
* normalize PHP error timestamps to canonical format ([516d80d](https://github.com/LindemannRock/craft-logging-library/commit/516d80d1af7419f616bcbde24bff78dcf5ff1145))

## [5.9.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.8.3...v5.9.0) - 2026-05-22


### Added

* add [@since](https://github.com/since) annotations for new methods in LoggingLibrary ([5bba9f3](https://github.com/LindemannRock/craft-logging-library/commit/5bba9f3428b9e421bfb230cc71c14973e08513f0))
* add act-static-analysis script for CI integration ([85521cc](https://github.com/LindemannRock/craft-logging-library/commit/85521cc8d83d6cdae9392c3e544c80204aeae7cd))
* add CI scripts for PHPStan and ECS checks ([e368277](https://github.com/LindemannRock/craft-logging-library/commit/e36827723606bab68e44bade61c9859f5adf17c7))
* add clear cache permission and update cache options label ([b65746c](https://github.com/LindemannRock/craft-logging-library/commit/b65746c36c377cb49289ee34862548bf83dbb4e3))
* add pre-commit hook for ECS and PHPStan code quality checks ([1d60b6d](https://github.com/LindemannRock/craft-logging-library/commit/1d60b6d92c3494c9a6b5bbf768833e9b2db44ea9))
* **config:** update items per page setting to 100 for log viewer ([79223c8](https://github.com/LindemannRock/craft-logging-library/commit/79223c80caf27d64c4d2a1a5d1c082f7587a19f1))
* **i18n:** add 'Open Settings' translation to multiple languages ([b24cc3e](https://github.com/LindemannRock/craft-logging-library/commit/b24cc3e3b1f86c0e435e0cc26b096bdebac15628))
* **i18n:** add common translation keys for multiple languages ([fd72941](https://github.com/LindemannRock/craft-logging-library/commit/fd72941c683c6a2106d4f7ea2f0c6df556524ffb))
* **i18n:** add new log-related messages in multiple languages ([06f8b45](https://github.com/LindemannRock/craft-logging-library/commit/06f8b45c6d5724348bff3e4d574fcd0dad68aa50))
* **i18n:** add permission translations for multiple languages ([4920cf2](https://github.com/LindemannRock/craft-logging-library/commit/4920cf2b7b032165140cf67511d24afcabd7d1ee))
* **i18n:** add translation issue template for reporting language problems ([afe85a6](https://github.com/LindemannRock/craft-logging-library/commit/afe85a6ed2d99ac9562a94be5ae96276d7688312))
* **i18n:** add translations for 'Clear cache' and related cache strings ([9def6cd](https://github.com/LindemannRock/craft-logging-library/commit/9def6cd0cf562b946ed9ec7135128a380e75e00c))
* **i18n:** add user-related messages and cache refresh notifications in multiple languages ([e37dfe7](https://github.com/LindemannRock/craft-logging-library/commit/e37dfe702977b0ae9353323e8c80ec9f155e0bba))
* **logging:** add attribute labels for plugin settings ([351e62b](https://github.com/LindemannRock/craft-logging-library/commit/351e62b6a79d23bbe119a0af712ecea41197c9fc))
* **logging:** add newline sanitization to log messages to prevent log injection ([6f2aef7](https://github.com/LindemannRock/craft-logging-library/commit/6f2aef74111602ccc8a2c2424337dff86ebd4df4))
* **logs:** add canonical log level classification for improved styling ([70e6499](https://github.com/LindemannRock/craft-logging-library/commit/70e64996af2a66a1060cf0b325ba8f215b3da607))
* **logs:** add row class key for log level styling and enhance refresh cache button with loading state ([c39b2da](https://github.com/LindemannRock/craft-logging-library/commit/c39b2dabeb21c79df747c333135084c07890c421))
* **logs:** add rowClassKey for log level styling in log entries ([52bb2f1](https://github.com/LindemannRock/craft-logging-library/commit/52bb2f14c788666e0e21291ee2ae8d7cb700c87d))
* **logs:** add stable sorting for log entries with tiebreaker ([e8266ab](https://github.com/LindemannRock/craft-logging-library/commit/e8266abda16a6922b1bb0192797fc3f677755db1))
* **logs:** enhance log parsing and categorization for various formats ([3cfd164](https://github.com/LindemannRock/craft-logging-library/commit/3cfd16426ba696444bccc96f1178b04f7c861cb8))
* **migrations:** change itemsPerPage default from 50 to 100 ([ee77117](https://github.com/LindemannRock/craft-logging-library/commit/ee7711798110bc3f7ea774a7a241d395ce7a1251))
* **settings:** add date format settings to plugin configuration ([ad90bd0](https://github.com/LindemannRock/craft-logging-library/commit/ad90bd0910e22a8fbeec395c253870d8864f487f))
* **settings:** integrate items per page settings into interface template ([eeaa4d5](https://github.com/LindemannRock/craft-logging-library/commit/eeaa4d56d099e3c48dc76cce142b6673d127eea6))
* **settings:** integrate plugin name settings into model rules and labels ([080875d](https://github.com/LindemannRock/craft-logging-library/commit/080875d4c3b3102dbe73720d9f0b657863675c87))
* **tests:** add integration tests for log file handling and format detection ([3b3356e](https://github.com/LindemannRock/craft-logging-library/commit/3b3356e6c1660605eccd27517a82d447a820c54b))


### Fixed

* correct path for PHPStan configuration inclusion ([928f952](https://github.com/LindemannRock/craft-logging-library/commit/928f95215a81861deca6effdcbd0b57832473c07))
* correct PHPStan output handling in pre-commit hook ([1bb77f3](https://github.com/LindemannRock/craft-logging-library/commit/1bb77f3cdf124c61a4c38e8332bddf0cf353ca47))
* **i18n:** remove 'Items Per Page' string from translations ([4befe13](https://github.com/LindemannRock/craft-logging-library/commit/4befe13a558a441c5276abf0f7dcb9b5d4770996))
* **i18n:** remove deprecated plugin name translations from multiple locales ([3f0ba56](https://github.com/LindemannRock/craft-logging-library/commit/3f0ba5665dfcfbd6f93d139b33ebf8cea1a4b90b))
* **i18n:** remove redundant log entry display translation strings ([e138233](https://github.com/LindemannRock/craft-logging-library/commit/e138233e0d279ebdbb6063a686fd5d3e717cd2b9))
* **logging:** allow null plugin handle in log method signature ([52426c1](https://github.com/LindemannRock/craft-logging-library/commit/52426c1923e05cd162ae2eaaa4fb3780c3dafe99))
* **logging:** allow rotated log file variants in filename validation ([aefd169](https://github.com/LindemannRock/craft-logging-library/commit/aefd169976cd59d560845b2498da9e57c9476974))
* **logging:** ensure cache file array is initialized to avoid null errors ([870d493](https://github.com/LindemannRock/craft-logging-library/commit/870d493f3b189f5be4fdb53abd62ee8750e07f4a))
* **logging:** ensure log file arrays are initialized to avoid null errors ([46ad3f1](https://github.com/LindemannRock/craft-logging-library/commit/46ad3f15da6751c199871ceddca9b3f8ffb46a07))
* **logging:** ensure log files array is initialized to avoid null errors ([305952f](https://github.com/LindemannRock/craft-logging-library/commit/305952f3ef83f484ace70848d5b5e686ce739dae))
* **logging:** replace hardcoded error messages with translatable strings ([cfde7dc](https://github.com/LindemannRock/craft-logging-library/commit/cfde7dcbe07b36d092b64cf2645bdae63f130f23))
* **logging:** sanitize log messages to prevent log injection attacks ([6123e59](https://github.com/LindemannRock/craft-logging-library/commit/6123e5958fd7fee11a6441f6d622164b8df62e6f))
* **logs:** escape log message and context to prevent XSS vulnerabilities ([d4a2068](https://github.com/LindemannRock/craft-logging-library/commit/d4a206897b14d2bdb9dd698d61d28ff63ddce825))
* update [@since](https://github.com/since) annotations for log-related methods ([11f9a12](https://github.com/LindemannRock/craft-logging-library/commit/11f9a1283e9718d1aa8276db8c718a4d07961020))
* update plugin schema version to 1.0.3 ([c668cfa](https://github.com/LindemannRock/craft-logging-library/commit/c668cfa4779c4e93e9cd2dac4ccc961cf1096a35))
* update plugin schema version to 1.0.5 ([1078562](https://github.com/LindemannRock/craft-logging-library/commit/107856219855077b46a606164ec814f4d1a0c6c6))

## [5.8.3](https://github.com/LindemannRock/craft-logging-library/compare/v5.8.2...v5.8.3) - 2026-05-06


### Bug Fixes

* apply config overrides through shared settings helper ([eedbb1d](https://github.com/LindemannRock/craft-logging-library/commit/eedbb1da4f575e17ebc3666e6a80326436bb5f90))
* drop PAT requirement for release-please — use built-in GITHUB_TOKEN ([a559dfd](https://github.com/LindemannRock/craft-logging-library/commit/a559dfd7e7d2eb5d63d96d41f101e97d8b611e35))
* **interface:** update heading class for interface settings page ([9c10bf9](https://github.com/LindemannRock/craft-logging-library/commit/9c10bf9fb8e348e5b4a85815256d11cfac890f88))
* **LogCacheService:** update version annotation to reflect correct version ([ba7f120](https://github.com/LindemannRock/craft-logging-library/commit/ba7f120e63146d7f858d02fc1d1b8d6c58581afe))
* **LogsViewService:** correct version annotation to reflect initial release ([ba7f120](https://github.com/LindemannRock/craft-logging-library/commit/ba7f120e63146d7f858d02fc1d1b8d6c58581afe))
* **settings:** update heading class for general settings page ([3b5f6a4](https://github.com/LindemannRock/craft-logging-library/commit/3b5f6a418842f11991cbe9262866b8e8cf04da18))
* **translations:** correct Danish and Dutch translations for interface and plugin name ([6ce4f4e](https://github.com/LindemannRock/craft-logging-library/commit/6ce4f4e63a79e73bf8c1031f9ed8a91c6977cf6a))

## [5.8.2](https://github.com/LindemannRock/craft-logging-library/compare/v5.8.1...v5.8.2) - 2026-04-05


### Bug Fixes

* **logs:** update translation keys for log levels and messages ([6cb84f4](https://github.com/LindemannRock/craft-logging-library/commit/6cb84f4de087d7f03e1ee1804407b7febbbd13ac))

## [5.8.1](https://github.com/LindemannRock/craft-logging-library/compare/v5.8.0...v5.8.1) - 2026-04-05


### Bug Fixes

* read-only settings response handling ([313cbcb](https://github.com/LindemannRock/craft-logging-library/commit/313cbcb4b0abdd310157c27eb43261e08b812eb1))

## [5.8.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.7.1...v5.8.0) - 2026-04-02


### Features

* **icon:** replace old SVG icon with new design ([dc0bed9](https://github.com/LindemannRock/craft-logging-library/commit/dc0bed9a1d2eb5f4ac8e7a64c23a6bcc9706215f))
* **LoggingLibrary:** add settings management and configuration options ([56de0ae](https://github.com/LindemannRock/craft-logging-library/commit/56de0ae6db735a42321560bf1339b273f5e0b53d))
* **LoggingLibrary:** enhance log viewer availability and settings handling ([8bc7a54](https://github.com/LindemannRock/craft-logging-library/commit/8bc7a54115483f0c944812b0c669ee97aa19c0e2))
* **migrations:** add forceEnableLogViewer setting to logging library ([04d77e7](https://github.com/LindemannRock/craft-logging-library/commit/04d77e75e7628977f8857fecd438eff54ac0a716))
* **settings:** add forceEnableLogViewer option and related UI updates ([03ade12](https://github.com/LindemannRock/craft-logging-library/commit/03ade122cab7a91c21369ba68a00b359cba966dc))
* **settings:** add general and interface settings templates ([c866c33](https://github.com/LindemannRock/craft-logging-library/commit/c866c338179bd4e46d7bc5116a2a1a570c0972ed))


### Bug Fixes

* log menu label translation ([5a22de6](https://github.com/LindemannRock/craft-logging-library/commit/5a22de65823fcf8cabbdddbefdc34dc2ff552d33))
* **LoggingLibrary:** enhance plugin helper bootstrap with install experience ([d22867b](https://github.com/LindemannRock/craft-logging-library/commit/d22867b9d22ffcd9be26d9c72c8495fad941b2e7))
* **LogsController:** update log level and source labels for translation ([9ca656e](https://github.com/LindemannRock/craft-logging-library/commit/9ca656e4a84c64daa624a83cc16fab059ef221c5))
* **LogsUtility:** update displayName translation scope to logging-library ([6de0db9](https://github.com/LindemannRock/craft-logging-library/commit/6de0db997c190bbaa91e3fd94c07c12fd1ba641c))
* **LogsViewService:** update log level labels for translation ([3e5fded](https://github.com/LindemannRock/craft-logging-library/commit/3e5fded52c86e7c27490428d997be0efb2631568))
* **twig templates:** update translation labels to use pluginHandle ([6bd08da](https://github.com/LindemannRock/craft-logging-library/commit/6bd08da50c34db1ce0fe93bb03c8138aaea92b17))

## [5.7.1](https://github.com/LindemannRock/craft-logging-library/compare/v5.7.0...v5.7.1) - 2026-02-22


### Bug Fixes

* **logs:** update timestamp formatting in log templates ([d78765c](https://github.com/LindemannRock/craft-logging-library/commit/d78765c8883cdc668be95cedcc2bc01d2f5cdfde))


### Miscellaneous Chores

* add .gitattributes with export-ignore for Packagist distribution ([91a2e94](https://github.com/LindemannRock/craft-logging-library/commit/91a2e94248c6c49ea26fd46b3f7226cfbed422c5))
* clean up .gitignore by removing development files ([0cc8017](https://github.com/LindemannRock/craft-logging-library/commit/0cc8017c14ce11b39928a0cfdfbaf1200c20a50b))

## [5.7.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.6.0...v5.7.0) - 2026-02-05


### Features

* **logs:** enhance log permissions and navigation for system logs ([8f492b4](https://github.com/LindemannRock/craft-logging-library/commit/8f492b49c3f64bdf94850ace3d065b9fa1316839))
* **logs:** update log routing and menu structure for system logs ([941feea](https://github.com/LindemannRock/craft-logging-library/commit/941feea977c924d57b10758746bd078c5c048436))

## [5.6.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.5.0...v5.6.0) - 2026-01-28


### Features

* add LogsViewService and update LogsController for log viewing ([769b278](https://github.com/LindemannRock/craft-logging-library/commit/769b278accd774fd9d8c128771bc93acdc2f1c07))
* add permission system for standalone log viewer ([5b8c249](https://github.com/LindemannRock/craft-logging-library/commit/5b8c2490bc2f8c38d70d41b6cd414857e79e1016))


### Bug Fixes

* update timestamp formatting in tableRow block ([eec55b4](https://github.com/LindemannRock/craft-logging-library/commit/eec55b4ff0012d94d67ad77f73da8c75745c30f4))

## [5.5.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.4.0...v5.5.0) - 2026-01-24


### Features

* migrate logs index template to cp-table layout from base plugin ([dce3709](https://github.com/LindemannRock/craft-logging-library/commit/dce3709feff30ea49bb98a0b8b1c1e3ae9c488e8))


### Bug Fixes

* rename sidebar block to sidebarContent for consistency ([e6ce37d](https://github.com/LindemannRock/craft-logging-library/commit/e6ce37d3e25b5fe6454a50e5bd7de58ae056b0c7))

## [5.4.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.3.1...v5.4.0) - 2026-01-16


### Features

* enhance log download functionality with validation and permissions ([b2b8e32](https://github.com/LindemannRock/craft-logging-library/commit/b2b8e324acb848cf364be6a2d4a3699b353f7c94))


### Bug Fixes

* ensure download permission check only occurs if downloadPermissions are configured ([588a19b](https://github.com/LindemannRock/craft-logging-library/commit/588a19baae1ce1c240f8a8d8dcabc880920488a1))
* update hardcoded cache paths with PluginHelper for consistency ([146f207](https://github.com/LindemannRock/craft-logging-library/commit/146f20780226ef56b1c0e6dfba410378cb29152e))

## [5.3.1](https://github.com/LindemannRock/craft-logging-library/compare/v5.3.0...v5.3.1) - 2026-01-11


### Bug Fixes

* correct search input reference in clear button event listener ([a03be9d](https://github.com/LindemannRock/craft-logging-library/commit/a03be9db2a4a54445978107063d31bedeca55dfa))

## [5.3.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.2.3...v5.3.0) - 2026-01-08


### Features

* enhance logging permissions and download functionality ([a381730](https://github.com/LindemannRock/craft-logging-library/commit/a381730d69f42bf3095b5ffd8e50662fc61ecb3e))
* Migrate to shared base plugin (lindemannrock/craft-plugin-base) ([ce6e4e1](https://github.com/LindemannRock/craft-logging-library/commit/ce6e4e156923521ae5346a6effe65f8911ce814d))

## [5.2.3](https://github.com/LindemannRock/craft-logging-library/compare/v5.2.2...v5.2.3) - 2025-12-19


### Bug Fixes

* correct label for logging library cache option ([179b53e](https://github.com/LindemannRock/craft-logging-library/commit/179b53e279d9c5e6b52af8974d3ad935e29c38a5))

## [5.2.2](https://github.com/LindemannRock/craft-logging-library/compare/v5.2.1...v5.2.2) - 2025-12-04


### Miscellaneous Chores

* add [@since](https://github.com/since) 1.0.0 annotation to multiple classes and traits ([a0b5dc5](https://github.com/LindemannRock/craft-logging-library/commit/a0b5dc51654ec5fed29e465b39a088af7759fcf9))
* add MIT License file ([a3403a4](https://github.com/LindemannRock/craft-logging-library/commit/a3403a4bb2eece363939556666dfe7b02ed99fb6))

## [5.2.1](https://github.com/LindemannRock/craft-logging-library/compare/v5.2.0...v5.2.1) - 2025-10-27


### Miscellaneous Chores

* update .gitignore ([c28d153](https://github.com/LindemannRock/craft-logging-library/commit/c28d153f484e58a59d7791af8971391afe91b5d9))

## [5.2.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.1.2...v5.2.0) - 2025-10-26


### Features

* **logging:** enhance context processing and formatting in logging library ([a4b1442](https://github.com/LindemannRock/craft-logging-library/commit/a4b1442888b5a14ac7b11b03afaab34b04ee7d6f))

## [5.1.2](https://github.com/LindemannRock/craft-logging-library/compare/v5.1.1...v5.1.2) - 2025-10-25


### Bug Fixes

* trim whitespace from log filter parameters and URLs ([9b37b44](https://github.com/LindemannRock/craft-logging-library/commit/9b37b44810bbd588ed55a13fa616eadabb0f60ca))

## [5.1.1](https://github.com/LindemannRock/craft-logging-library/compare/v5.1.0...v5.1.1) - 2025-10-24


### Bug Fixes

* improve log viewer layout and functionality ([a024daf](https://github.com/LindemannRock/craft-logging-library/commit/a024dafc431979348daf3cbb2273d33afcf07988))

## [5.1.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.0.1...v5.1.0) - 2025-10-22


### Features

* add configurable items per page for log viewer ([29825d4](https://github.com/LindemannRock/craft-logging-library/commit/29825d40191f1e14ceedf8ddd6942142fc2fff20))

## [5.0.1](https://github.com/LindemannRock/craft-logging-library/compare/v5.0.0...v5.0.1) - 2025-10-20


### Miscellaneous Chores

* enhance README with additional badges ([608d99a](https://github.com/LindemannRock/craft-logging-library/commit/608d99aabffac1d353189a2ede2fa7d41e91b814))

## [5.0.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.10.4...v5.0.0) - 2025-10-20


### Features

* **logs:** add sorting and filtering options for log entries ([9c3e904](https://github.com/LindemannRock/craft-logging-library/commit/9c3e9049eb2059fbe609f9f85b08b2d2f7274452))


### Miscellaneous Chores

* bump version scheme to match Craft 5 ([c9b8b63](https://github.com/LindemannRock/craft-logging-library/commit/c9b8b63d1a99b4ec1ad883294b84b5a9a4af272d))

## [1.10.4](https://github.com/LindemannRock/craft-logging-library/compare/v1.10.3...v1.10.4) - 2025-10-17


### Bug Fixes

* update plugin name configuration to respect custom settings ([a0cdcd2](https://github.com/LindemannRock/craft-logging-library/commit/a0cdcd2a87d07dc605db3d5dffc24a72b115e431))

## [1.10.3](https://github.com/LindemannRock/craft-logging-library/compare/v1.10.2...v1.10.3) - 2025-10-16


### Bug Fixes

* update installation instructions for Composer and DDEV ([1122f6f](https://github.com/LindemannRock/craft-logging-library/commit/1122f6fca3b2e93b07ef735537191c06a743efe7))

## [1.10.2](https://github.com/LindemannRock/craft-logging-library/compare/v1.10.1...v1.10.2) - 2025-10-16


### Bug Fixes

* **composer:** change license from Proprietary to MIT ([45b23e7](https://github.com/LindemannRock/craft-logging-library/commit/45b23e75290d5109683b014bd6919b641eb534d8))

## [1.10.1](https://github.com/LindemannRock/craft-logging-library/compare/v1.10.0...v1.10.1) - 2025-10-16


### Bug Fixes

* **composer:** update author details and improve dependency constraints ([1d6ed8e](https://github.com/LindemannRock/craft-logging-library/commit/1d6ed8e5da5e5fe6387090ef54c399f769505181))

## [1.10.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.9.0...v1.10.0) - 2025-10-15


### Features

* **license:** add MIT license file to the repository ([83c94e7](https://github.com/LindemannRock/craft-logging-library/commit/83c94e789cab265fd74f35f13cea8564068eb984))


### Bug Fixes

* **logs:** Default to most recent log file instead of today ([850022c](https://github.com/LindemannRock/craft-logging-library/commit/850022c53d0def82849815eb96199839e02b5562))

## [1.9.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.8.0...v1.9.0) - 2025-10-09


### Features

* filter log level dropdown based on configured logLevel ([28397b6](https://github.com/LindemannRock/craft-logging-library/commit/28397b697c314b3e8ea9b82860f3bee9bb353e95))

## [1.8.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.7.0...v1.8.0) - 2025-09-25


### Features

* **docs:** add edge/CDN hosting environment detection and configuration examples ([42d0a2d](https://github.com/LindemannRock/craft-logging-library/commit/42d0a2d25bc7ac3d535c894bf19e01b93c8163bc))
* **logging:** implement automatic edge/CDN detection and disable log viewer accordingly ([c18a467](https://github.com/LindemannRock/craft-logging-library/commit/c18a4673f8cb95840cec5164031cc197ce038ced))

## [1.7.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.6.0...v1.7.0) - 2025-09-24


### Features

* **logs:** enhance navigation by adding match rule for logs pages ([1e6b971](https://github.com/LindemannRock/craft-logging-library/commit/1e6b9710a5f4bc76a775bfa8e9154a87e3076606))

## [1.6.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.5.0...v1.6.0) - 2025-09-24


### Features

* **logs:** enhance log filters display and improve level options logic ([1ffe8bb](https://github.com/LindemannRock/craft-logging-library/commit/1ffe8bb5051ef3303a0b2cd05beda02cd3348b08))


### Bug Fixes

* **composer:** update package name and add support information ([d598162](https://github.com/LindemannRock/craft-logging-library/commit/d5981624611483299a2984f67e950b5fce2c7723))

## [1.5.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.10...v1.5.0) - 2025-09-23


### Features

* Add user info to log output using custom processor ([acc89bd](https://github.com/LindemannRock/craft-logging-library/commit/acc89bdd22cc03db487594faaecc2b12e2e715ff))

## [1.4.10](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.9...v1.4.10) - 2025-09-23


### Bug Fixes

* Remove debug output from logging library ([3d37fb1](https://github.com/LindemannRock/craft-logging-library/commit/3d37fb1d36bef31928e33d4e4e8c68c606327e11))

## [1.4.9](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.8...v1.4.9) - 2025-09-23


### Bug Fixes

* Trigger version bump for debug output ([33e0c5a](https://github.com/LindemannRock/craft-logging-library/commit/33e0c5a3f1da299d0604bdbdfab61bc6121d76f6))

## [1.4.8](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.7...v1.4.8) - 2025-09-23


### Bug Fixes

* Use monologTargetConfig exclusion as the primary fix for filtering issues ([939bd15](https://github.com/LindemannRock/craft-logging-library/commit/939bd153f8c20c6a84ce73dfb73132013f9cdd7e))

## [1.4.7](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.6...v1.4.7) - 2025-09-23


### Bug Fixes

* Exclude plugin categories from global monolog filtering and use LogLevel constants ([a9d5123](https://github.com/LindemannRock/craft-logging-library/commit/a9d51234a729a049978187d462aab62013dc74ca))

## [1.4.6](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.5...v1.4.6) - 2025-09-23


### Bug Fixes

* Add target at beginning of dispatcher array to avoid being filtered by other plugins ([4273551](https://github.com/LindemannRock/craft-logging-library/commit/4273551a54a6d491b10722af84f78ededa8f750f))

## [1.4.5](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.4...v1.4.5) - 2025-09-23


### Bug Fixes

* Debug to inspect all targets that might be filtering messages ([3a8ae53](https://github.com/LindemannRock/craft-logging-library/commit/3a8ae530b9735fcdc8bab122e2e48506ab415ba9))

## [1.4.4](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.3...v1.4.4) - 2025-09-23


### Bug Fixes

* Add debug to check if target is staying in dispatcher ([ac8a6e4](https://github.com/LindemannRock/craft-logging-library/commit/ac8a6e40fe7b50f3b5cc109c8de872a220811804))

## [1.4.3](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.2...v1.4.3) - 2025-09-23


### Bug Fixes

* Add emergency debug to check if configure() is being called ([fdf7db7](https://github.com/LindemannRock/craft-logging-library/commit/fdf7db76cbd89ec7069561fc04309059b7d62f70))

## [1.4.2](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.1...v1.4.2) - 2025-09-23


### Bug Fixes

* Improve target cleanup and remove debug output ([98c3a25](https://github.com/LindemannRock/craft-logging-library/commit/98c3a2517b34ebb65a3d2a2bc6543dc890df3575))

## [1.4.1](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.0...v1.4.1) - 2025-09-23


### Bug Fixes

* Pass PSR-3 log level strings directly to MonologTarget ([a6bd3fd](https://github.com/LindemannRock/craft-logging-library/commit/a6bd3fdea5237dc6ea310a267ae86bca06cb0029))

## [1.4.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.3.1...v1.4.0) - 2025-09-23


### Features

* enhance logging with additional info messages during configuration and target setup ([cba791c](https://github.com/LindemannRock/craft-logging-library/commit/cba791cf643e0648d66662591be1a571a3790890))

## [1.3.1](https://github.com/LindemannRock/craft-logging-library/compare/v1.3.0...v1.3.1) - 2025-09-22


### Bug Fixes

* remove 'includeUserIp' from logging configuration ([bad00f4](https://github.com/LindemannRock/craft-logging-library/commit/bad00f49febc7b30960290f4df0a2e59b72466a9))

## [1.3.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.2.0...v1.3.0) - 2025-09-22


### Features

* enhance logging configuration with reconfiguration support and improved target management ([258036c](https://github.com/LindemannRock/craft-logging-library/commit/258036cc4c6f25484656474fa3763de9492cee25))

## [1.2.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.1.0...v1.2.0) - 2025-09-22


### Features

* add SVG icon for logging library ([82a1d11](https://github.com/LindemannRock/craft-logging-library/commit/82a1d118f86cf4bf503272dca2cdc24221ee3ea8))

## [1.1.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.0.0...v1.1.0) - 2025-09-22


### Features

* refactor logging library integration and enhance log viewer interface ([6edf612](https://github.com/LindemannRock/craft-logging-library/commit/6edf6120a9d74fa33c9ffcb4ffa813d0e50226c1))


### Bug Fixes

* update PHP requirement to 8.2+ to match Craft CMS 5 standards ([136bd2d](https://github.com/LindemannRock/craft-logging-library/commit/136bd2d94a44ae3e173be1c4649a8d12ec1b929e))

## 1.0.0 - 2025-09-22


### Features

* initial Craft Logging Library implementation ([297af57](https://github.com/LindemannRock/craft-logging-library/commit/297af572a5326607724b9125805145f06be7f0dd))
