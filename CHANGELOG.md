# Changelog

## [1.4.0](https://github.com/geekcell/php-ddd/compare/v1.3.1...v1.4.0) (2023-12-19)


### Features

* annotate generic ArrayAccess implementation, fix generic IteratorAggregate implementation annotation ([d654dbe](https://github.com/geekcell/php-ddd/commit/d654dbedb44ed2760ccaf6659c10c5e95ce2263b))

## [1.3.1](https://github.com/geekcell/php-ddd/compare/v1.3.0...v1.3.1) (2023-12-05)


### Bug Fixes

* phpdoc: let InMemoryRepository implement interface instead of extending it ([ef575a5](https://github.com/geekcell/php-ddd/commit/ef575a50947025ca8e15c303eaa9148645408272))

## [1.3.0](https://github.com/geekcell/php-ddd/compare/v1.2.0...v1.3.0) (2023-11-29)


### Features

* accept items in constructor arguments for in memory repository ([687479e](https://github.com/geekcell/php-ddd/commit/687479ec264bc0255aa5300782318af4baa1572c))

## [1.2.0](https://github.com/geekcell/php-ddd/compare/v1.1.1...v1.2.0) (2023-05-22)


### Features

* add generic/template support for queries/commands ([5d2e689](https://github.com/geekcell/php-ddd/commit/5d2e689a0c5b233a97bbef57f3148c5cf0b151e0))
* add generic/template support for queries/commands ([6d25a9c](https://github.com/geekcell/php-ddd/commit/6d25a9c346597ccb8a5bff49e90a682d605d6ec6))

## [1.1.1](https://github.com/geekcell/php-ddd/compare/v1.1.0...v1.1.1) (2023-03-31)


### Bug Fixes

* Improve `Collection::filter` method to not retain indices. ([#13](https://github.com/geekcell/php-ddd/issues/13)) ([1848c02](https://github.com/geekcell/php-ddd/commit/1848c020ca062573b5b88a235ec9c304d110445f)), closes [#12](https://github.com/geekcell/php-ddd/issues/12)

## [1.1.0](https://github.com/geekcell/php-ddd/compare/v1.0.2...v1.1.0) (2023-01-20)


### Features

* Add `ChainRepository` to support multiple repositories. ([87deb1d](https://github.com/geekcell/php-ddd/commit/87deb1de1650d269b6f0472ea1e7ebd4535e5830))
* Add more functionality to `Collection` class ([6e0c0a9](https://github.com/geekcell/php-ddd/commit/6e0c0a9796daa8f5513ce7b179f8b347cbd33c86))
* Implement `ArrayAccess` for in-memory paginator. ([6abd60e](https://github.com/geekcell/php-ddd/commit/6abd60e099608b065002fe338b7faf3a9cf26104))


### Bug Fixes

* Use `ForType` instead of `For` for attributes ([7dd103e](https://github.com/geekcell/php-ddd/commit/7dd103ea0f411a486a55881a9220b4cb186bf0f7))


## [1.0.2](https://github.com/geekcell/php-ddd/compare/v1.0.1...v1.0.2) (2023-01-16)


### Bug Fixes

* Rename class to match PSR-4 autoloading standards. ([6e8d82e](https://github.com/geekcell/php-ddd/commit/6e8d82ecc6be3df29eb65f2468e7d760c27ba0b9))

## [1.0.1](https://github.com/geekcell/php-ddd/compare/v1.0.0...v1.0.1) (2023-01-16)


### Bug Fixes

* Remove package from docstring. ([a74b1ac](https://github.com/geekcell/php-ddd/commit/a74b1ac3cbc1d03681e574e339c4c6d2b4b433f2))

## [1.0.0](https://github.com/geekcell/php-ddd/compare/v2.0.0...v1.0.0) (2023-01-16)


### ⚠ BREAKING CHANGES

* Make `Collection` an instantiable class.
* Change Repository interface.
* Use item and collection types as constructor arguments
* Update contracts for command and query bus.

### Features

* Add `ValueObject` contract and implementations. ([c1a00b4](https://github.com/geekcell/php-ddd/commit/c1a00b451ef70e096d7fd5a105bbd635cb57da55))
* Add command and query bus. ([848da2c](https://github.com/geekcell/php-ddd/commit/848da2c60a4c3ee459614804099baadd8fa1ff2e))


### Code Refactoring

* Change Repository interface. ([4e1b14a](https://github.com/geekcell/php-ddd/commit/4e1b14a7e16f4bf99748d764bbb14832661e4087))
* Make `Collection` an instantiable class. ([3ca9db5](https://github.com/geekcell/php-ddd/commit/3ca9db517e24931d26145de7def76c416da94d15))
* Update contracts for command and query bus. ([a5b8957](https://github.com/geekcell/php-ddd/commit/a5b89573ad5f282a5b8b510a815332a6f2fe2f0a))
* Use item and collection types as constructor arguments ([050ffcc](https://github.com/geekcell/php-ddd/commit/050ffcce4ef49aa1db2921713f6a79d428c567d3))


### Miscellaneous Chores

* Apply PHP CS fixer fixes. ([8072b49](https://github.com/geekcell/php-ddd/commit/8072b49e198368e514c30e496e073ba2ff82a808))
* Apply PHP CS fixer fixes. ([8e8c960](https://github.com/geekcell/php-ddd/commit/8e8c9608e03c332d1a43d16c5908b582bcac2c84))
