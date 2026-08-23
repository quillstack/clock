# Quillstack Clock

[![Tests](https://github.com/quillstack/clock/actions/workflows/tests.yml/badge.svg)](https://github.com/quillstack/clock/actions/workflows/tests.yml)
[![Latest Version](https://img.shields.io/packagist/v/quillstack/clock.svg)](https://packagist.org/packages/quillstack/clock)
[![Downloads](https://img.shields.io/packagist/dt/quillstack/clock.svg)](https://packagist.org/packages/quillstack/clock)
[![PHP Version](https://img.shields.io/packagist/php-v/quillstack/clock)](https://packagist.org/packages/quillstack/clock)
[![StyleCI](https://github.styleci.io/repos/1342207629/shield?branch=main)](https://github.styleci.io/repos/1342207629?branch=main)
[![CodeFactor](https://www.codefactor.io/repository/github/quillstack/clock/badge)](https://www.codefactor.io/repository/github/quillstack/clock)
[![Quality Gate](https://sonarcloud.io/api/project_badges/measure?project=quillstack_clock&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=quillstack_clock)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=quillstack_clock&metric=coverage)](https://sonarcloud.io/summary/new_code?id=quillstack_clock)
[![Maintainability](https://sonarcloud.io/api/project_badges/measure?project=quillstack_clock&metric=sqale_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_clock)
[![Reliability](https://sonarcloud.io/api/project_badges/measure?project=quillstack_clock&metric=reliability_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_clock)
[![Security](https://sonarcloud.io/api/project_badges/measure?project=quillstack_clock&metric=security_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_clock)
[![License](https://img.shields.io/packagist/l/quillstack/clock)](https://github.com/quillstack/clock/blob/main/LICENSE)

A clock based on PSR-20: Clock, with one which stands still for tests.

## Installation

```shell
composer require quillstack/clock
```

## Usage

Anything deciding when something happened, or whether it has expired, takes a clock rather
than reading the wall clock where it stands. `SystemClock` is that wall clock:

```php
use Quillstack\Clock\SystemClock;

$cache = new ArrayCache(new SystemClock());
```

`FrozenClock` stands still until it is moved, so a test watches something expire without
waiting for it:

```php
use Quillstack\Clock\FrozenClock;

$clock = new FrozenClock();
$cache = new ArrayCache($clock);

$cache->set('key', 'value', 60);
$clock->sleep(61);

$cache->get('key'); // null
```

It is in the sources rather than the tests, because anything taking a clock will want it.

## Tests

```shell
composer test
```

## License

MIT. See [LICENSE](LICENSE).
