# Changelog

## [5.8.2](https://github.com/LindemannRock/craft-logging-library/compare/v5.8.1...v5.8.2) (2026-04-05)


### Bug Fixes

* **logs:** update translation keys for log levels and messages ([6cb84f4](https://github.com/LindemannRock/craft-logging-library/commit/6cb84f4de087d7f03e1ee1804407b7febbbd13ac))

## [5.8.1](https://github.com/LindemannRock/craft-logging-library/compare/v5.8.0...v5.8.1) (2026-04-05)


### Bug Fixes

* read-only settings response handling ([313cbcb](https://github.com/LindemannRock/craft-logging-library/commit/313cbcb4b0abdd310157c27eb43261e08b812eb1))

## [5.8.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.7.1...v5.8.0) (2026-04-02)


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

## [5.7.1](https://github.com/LindemannRock/craft-logging-library/compare/v5.7.0...v5.7.1) (2026-02-22)


### Bug Fixes

* **logs:** update timestamp formatting in log templates ([d78765c](https://github.com/LindemannRock/craft-logging-library/commit/d78765c8883cdc668be95cedcc2bc01d2f5cdfde))


### Miscellaneous Chores

* add .gitattributes with export-ignore for Packagist distribution ([91a2e94](https://github.com/LindemannRock/craft-logging-library/commit/91a2e94248c6c49ea26fd46b3f7226cfbed422c5))
* clean up .gitignore by removing development files ([0cc8017](https://github.com/LindemannRock/craft-logging-library/commit/0cc8017c14ce11b39928a0cfdfbaf1200c20a50b))

## [5.7.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.6.0...v5.7.0) (2026-02-05)


### Features

* **logs:** enhance log permissions and navigation for system logs ([8f492b4](https://github.com/LindemannRock/craft-logging-library/commit/8f492b49c3f64bdf94850ace3d065b9fa1316839))
* **logs:** update log routing and menu structure for system logs ([941feea](https://github.com/LindemannRock/craft-logging-library/commit/941feea977c924d57b10758746bd078c5c048436))

## [5.6.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.5.0...v5.6.0) (2026-01-28)


### Features

* add LogsViewService and update LogsController for log viewing ([769b278](https://github.com/LindemannRock/craft-logging-library/commit/769b278accd774fd9d8c128771bc93acdc2f1c07))
* add permission system for standalone log viewer ([5b8c249](https://github.com/LindemannRock/craft-logging-library/commit/5b8c2490bc2f8c38d70d41b6cd414857e79e1016))


### Bug Fixes

* update timestamp formatting in tableRow block ([eec55b4](https://github.com/LindemannRock/craft-logging-library/commit/eec55b4ff0012d94d67ad77f73da8c75745c30f4))

## [5.5.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.4.0...v5.5.0) (2026-01-24)


### Features

* migrate logs index template to cp-table layout from base plugin ([dce3709](https://github.com/LindemannRock/craft-logging-library/commit/dce3709feff30ea49bb98a0b8b1c1e3ae9c488e8))


### Bug Fixes

* rename sidebar block to sidebarContent for consistency ([e6ce37d](https://github.com/LindemannRock/craft-logging-library/commit/e6ce37d3e25b5fe6454a50e5bd7de58ae056b0c7))

## [5.4.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.3.1...v5.4.0) (2026-01-16)


### Features

* enhance log download functionality with validation and permissions ([b2b8e32](https://github.com/LindemannRock/craft-logging-library/commit/b2b8e324acb848cf364be6a2d4a3699b353f7c94))


### Bug Fixes

* ensure download permission check only occurs if downloadPermissions are configured ([588a19b](https://github.com/LindemannRock/craft-logging-library/commit/588a19baae1ce1c240f8a8d8dcabc880920488a1))
* update hardcoded cache paths with PluginHelper for consistency ([146f207](https://github.com/LindemannRock/craft-logging-library/commit/146f20780226ef56b1c0e6dfba410378cb29152e))

## [5.3.1](https://github.com/LindemannRock/craft-logging-library/compare/v5.3.0...v5.3.1) (2026-01-11)


### Bug Fixes

* correct search input reference in clear button event listener ([a03be9d](https://github.com/LindemannRock/craft-logging-library/commit/a03be9db2a4a54445978107063d31bedeca55dfa))

## [5.3.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.2.3...v5.3.0) (2026-01-08)


### Features

* enhance logging permissions and download functionality ([a381730](https://github.com/LindemannRock/craft-logging-library/commit/a381730d69f42bf3095b5ffd8e50662fc61ecb3e))
* Migrate to shared base plugin (lindemannrock/craft-plugin-base) ([ce6e4e1](https://github.com/LindemannRock/craft-logging-library/commit/ce6e4e156923521ae5346a6effe65f8911ce814d))

## [5.2.3](https://github.com/LindemannRock/craft-logging-library/compare/v5.2.2...v5.2.3) (2025-12-19)


### Bug Fixes

* correct label for logging library cache option ([179b53e](https://github.com/LindemannRock/craft-logging-library/commit/179b53e279d9c5e6b52af8974d3ad935e29c38a5))

## [5.2.2](https://github.com/LindemannRock/craft-logging-library/compare/v5.2.1...v5.2.2) (2025-12-04)


### Miscellaneous Chores

* add [@since](https://github.com/since) 1.0.0 annotation to multiple classes and traits ([a0b5dc5](https://github.com/LindemannRock/craft-logging-library/commit/a0b5dc51654ec5fed29e465b39a088af7759fcf9))
* add MIT License file ([a3403a4](https://github.com/LindemannRock/craft-logging-library/commit/a3403a4bb2eece363939556666dfe7b02ed99fb6))

## [5.2.1](https://github.com/LindemannRock/craft-logging-library/compare/v5.2.0...v5.2.1) (2025-10-27)


### Miscellaneous Chores

* update .gitignore ([c28d153](https://github.com/LindemannRock/craft-logging-library/commit/c28d153f484e58a59d7791af8971391afe91b5d9))

## [5.2.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.1.2...v5.2.0) (2025-10-26)


### Features

* **logging:** enhance context processing and formatting in logging library ([a4b1442](https://github.com/LindemannRock/craft-logging-library/commit/a4b1442888b5a14ac7b11b03afaab34b04ee7d6f))

## [5.1.2](https://github.com/LindemannRock/craft-logging-library/compare/v5.1.1...v5.1.2) (2025-10-25)


### Bug Fixes

* trim whitespace from log filter parameters and URLs ([9b37b44](https://github.com/LindemannRock/craft-logging-library/commit/9b37b44810bbd588ed55a13fa616eadabb0f60ca))

## [5.1.1](https://github.com/LindemannRock/craft-logging-library/compare/v5.1.0...v5.1.1) (2025-10-24)


### Bug Fixes

* improve log viewer layout and functionality ([a024daf](https://github.com/LindemannRock/craft-logging-library/commit/a024dafc431979348daf3cbb2273d33afcf07988))

## [5.1.0](https://github.com/LindemannRock/craft-logging-library/compare/v5.0.1...v5.1.0) (2025-10-22)


### Features

* add configurable items per page for log viewer ([29825d4](https://github.com/LindemannRock/craft-logging-library/commit/29825d40191f1e14ceedf8ddd6942142fc2fff20))

## [5.0.1](https://github.com/LindemannRock/craft-logging-library/compare/v5.0.0...v5.0.1) (2025-10-20)


### Miscellaneous Chores

* enhance README with additional badges ([608d99a](https://github.com/LindemannRock/craft-logging-library/commit/608d99aabffac1d353189a2ede2fa7d41e91b814))

## [5.0.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.10.4...v5.0.0) (2025-10-20)


### Features

* **logs:** add sorting and filtering options for log entries ([9c3e904](https://github.com/LindemannRock/craft-logging-library/commit/9c3e9049eb2059fbe609f9f85b08b2d2f7274452))


### Miscellaneous Chores

* bump version scheme to match Craft 5 ([c9b8b63](https://github.com/LindemannRock/craft-logging-library/commit/c9b8b63d1a99b4ec1ad883294b84b5a9a4af272d))

## [1.10.4](https://github.com/LindemannRock/craft-logging-library/compare/v1.10.3...v1.10.4) (2025-10-17)


### Bug Fixes

* update plugin name configuration to respect custom settings ([a0cdcd2](https://github.com/LindemannRock/craft-logging-library/commit/a0cdcd2a87d07dc605db3d5dffc24a72b115e431))

## [1.10.3](https://github.com/LindemannRock/craft-logging-library/compare/v1.10.2...v1.10.3) (2025-10-16)


### Bug Fixes

* update installation instructions for Composer and DDEV ([1122f6f](https://github.com/LindemannRock/craft-logging-library/commit/1122f6fca3b2e93b07ef735537191c06a743efe7))

## [1.10.2](https://github.com/LindemannRock/craft-logging-library/compare/v1.10.1...v1.10.2) (2025-10-16)


### Bug Fixes

* **composer:** change license from Proprietary to MIT ([45b23e7](https://github.com/LindemannRock/craft-logging-library/commit/45b23e75290d5109683b014bd6919b641eb534d8))

## [1.10.1](https://github.com/LindemannRock/craft-logging-library/compare/v1.10.0...v1.10.1) (2025-10-16)


### Bug Fixes

* **composer:** update author details and improve dependency constraints ([1d6ed8e](https://github.com/LindemannRock/craft-logging-library/commit/1d6ed8e5da5e5fe6387090ef54c399f769505181))

## [1.10.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.9.0...v1.10.0) (2025-10-15)


### Features

* **license:** add MIT license file to the repository ([83c94e7](https://github.com/LindemannRock/craft-logging-library/commit/83c94e789cab265fd74f35f13cea8564068eb984))


### Bug Fixes

* **logs:** Default to most recent log file instead of today ([850022c](https://github.com/LindemannRock/craft-logging-library/commit/850022c53d0def82849815eb96199839e02b5562))

## [1.9.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.8.0...v1.9.0) (2025-10-09)


### Features

* filter log level dropdown based on configured logLevel ([28397b6](https://github.com/LindemannRock/craft-logging-library/commit/28397b697c314b3e8ea9b82860f3bee9bb353e95))

## [1.8.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.7.0...v1.8.0) (2025-09-25)


### Features

* **docs:** add edge/CDN hosting environment detection and configuration examples ([42d0a2d](https://github.com/LindemannRock/craft-logging-library/commit/42d0a2d25bc7ac3d535c894bf19e01b93c8163bc))
* **logging:** implement automatic edge/CDN detection and disable log viewer accordingly ([c18a467](https://github.com/LindemannRock/craft-logging-library/commit/c18a4673f8cb95840cec5164031cc197ce038ced))

## [1.7.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.6.0...v1.7.0) (2025-09-24)


### Features

* **logs:** enhance navigation by adding match rule for logs pages ([1e6b971](https://github.com/LindemannRock/craft-logging-library/commit/1e6b9710a5f4bc76a775bfa8e9154a87e3076606))

## [1.6.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.5.0...v1.6.0) (2025-09-24)


### Features

* **logs:** enhance log filters display and improve level options logic ([1ffe8bb](https://github.com/LindemannRock/craft-logging-library/commit/1ffe8bb5051ef3303a0b2cd05beda02cd3348b08))


### Bug Fixes

* **composer:** update package name and add support information ([d598162](https://github.com/LindemannRock/craft-logging-library/commit/d5981624611483299a2984f67e950b5fce2c7723))

## [1.5.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.10...v1.5.0) (2025-09-23)


### Features

* Add user info to log output using custom processor ([acc89bd](https://github.com/LindemannRock/craft-logging-library/commit/acc89bdd22cc03db487594faaecc2b12e2e715ff))

## [1.4.10](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.9...v1.4.10) (2025-09-23)


### Bug Fixes

* Remove debug output from logging library ([3d37fb1](https://github.com/LindemannRock/craft-logging-library/commit/3d37fb1d36bef31928e33d4e4e8c68c606327e11))

## [1.4.9](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.8...v1.4.9) (2025-09-23)


### Bug Fixes

* Trigger version bump for debug output ([33e0c5a](https://github.com/LindemannRock/craft-logging-library/commit/33e0c5a3f1da299d0604bdbdfab61bc6121d76f6))

## [1.4.8](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.7...v1.4.8) (2025-09-23)


### Bug Fixes

* Use monologTargetConfig exclusion as the primary fix for filtering issues ([939bd15](https://github.com/LindemannRock/craft-logging-library/commit/939bd153f8c20c6a84ce73dfb73132013f9cdd7e))

## [1.4.7](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.6...v1.4.7) (2025-09-23)


### Bug Fixes

* Exclude plugin categories from global monolog filtering and use LogLevel constants ([a9d5123](https://github.com/LindemannRock/craft-logging-library/commit/a9d51234a729a049978187d462aab62013dc74ca))

## [1.4.6](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.5...v1.4.6) (2025-09-23)


### Bug Fixes

* Add target at beginning of dispatcher array to avoid being filtered by other plugins ([4273551](https://github.com/LindemannRock/craft-logging-library/commit/4273551a54a6d491b10722af84f78ededa8f750f))

## [1.4.5](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.4...v1.4.5) (2025-09-23)


### Bug Fixes

* Debug to inspect all targets that might be filtering messages ([3a8ae53](https://github.com/LindemannRock/craft-logging-library/commit/3a8ae530b9735fcdc8bab122e2e48506ab415ba9))

## [1.4.4](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.3...v1.4.4) (2025-09-23)


### Bug Fixes

* Add debug to check if target is staying in dispatcher ([ac8a6e4](https://github.com/LindemannRock/craft-logging-library/commit/ac8a6e40fe7b50f3b5cc109c8de872a220811804))

## [1.4.3](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.2...v1.4.3) (2025-09-23)


### Bug Fixes

* Add emergency debug to check if configure() is being called ([fdf7db7](https://github.com/LindemannRock/craft-logging-library/commit/fdf7db76cbd89ec7069561fc04309059b7d62f70))

## [1.4.2](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.1...v1.4.2) (2025-09-23)


### Bug Fixes

* Improve target cleanup and remove debug output ([98c3a25](https://github.com/LindemannRock/craft-logging-library/commit/98c3a2517b34ebb65a3d2a2bc6543dc890df3575))

## [1.4.1](https://github.com/LindemannRock/craft-logging-library/compare/v1.4.0...v1.4.1) (2025-09-23)


### Bug Fixes

* Pass PSR-3 log level strings directly to MonologTarget ([a6bd3fd](https://github.com/LindemannRock/craft-logging-library/commit/a6bd3fdea5237dc6ea310a267ae86bca06cb0029))

## [1.4.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.3.1...v1.4.0) (2025-09-23)


### Features

* enhance logging with additional info messages during configuration and target setup ([cba791c](https://github.com/LindemannRock/craft-logging-library/commit/cba791cf643e0648d66662591be1a571a3790890))

## [1.3.1](https://github.com/LindemannRock/craft-logging-library/compare/v1.3.0...v1.3.1) (2025-09-22)


### Bug Fixes

* remove 'includeUserIp' from logging configuration ([bad00f4](https://github.com/LindemannRock/craft-logging-library/commit/bad00f49febc7b30960290f4df0a2e59b72466a9))

## [1.3.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.2.0...v1.3.0) (2025-09-22)


### Features

* enhance logging configuration with reconfiguration support and improved target management ([258036c](https://github.com/LindemannRock/craft-logging-library/commit/258036cc4c6f25484656474fa3763de9492cee25))

## [1.2.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.1.0...v1.2.0) (2025-09-22)


### Features

* add SVG icon for logging library ([82a1d11](https://github.com/LindemannRock/craft-logging-library/commit/82a1d118f86cf4bf503272dca2cdc24221ee3ea8))

## [1.1.0](https://github.com/LindemannRock/craft-logging-library/compare/v1.0.0...v1.1.0) (2025-09-22)


### Features

* refactor logging library integration and enhance log viewer interface ([6edf612](https://github.com/LindemannRock/craft-logging-library/commit/6edf6120a9d74fa33c9ffcb4ffa813d0e50226c1))


### Bug Fixes

* update PHP requirement to 8.2+ to match Craft CMS 5 standards ([136bd2d](https://github.com/LindemannRock/craft-logging-library/commit/136bd2d94a44ae3e173be1c4649a8d12ec1b929e))

## 1.0.0 (2025-09-22)


### Features

* initial Craft Logging Library implementation ([297af57](https://github.com/LindemannRock/craft-logging-library/commit/297af572a5326607724b9125805145f06be7f0dd))
