# Changelog

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
