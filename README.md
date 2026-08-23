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

## Why this exists

Code that calls `time()` cannot be tested at a particular moment, and code that takes a clock
can. That is the whole of PSR-20 — one method, `now()`, returning a `DateTimeImmutable` — and
this is one class that reads the system clock and one that does not move.

It is here because several packages in this framework need to know what time it is and none of
them should be asking the operating system directly: the [cache](https://github.com/quillstack/cache)
decides whether an entry expired, the [queue](https://github.com/quillstack/queue) decides
whether a job is due. Both are tested by handing them a clock that says it is a particular
Tuesday.

## Requirements

- PHP 8.1 or newer

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

## Benchmark

Measured with [quillstack/benchmark](https://github.com/quillstack/benchmark) on a thousand calls
to `now()`. Runs are interleaved and unconcurrent, each figure is the median of five, and PHP is
8.5.7.

| | Version |
| --- | --- |
| quillstack/clock | v0.6.0 |
| symfony/clock | v7.4.8 |
| lcobucci/clock | 3.6.0 |

| | Per call |
| --- | --- |
| lcobucci/clock | 156 ns |
| **quillstack/clock** | **161 ns** |
| symfony/clock | 217 ns |

**These are the same number.** All three do one thing — construct a `DateTimeImmutable` — and
what the table measures is mostly how long PHP takes to do that. Sixty nanoseconds between the
first and the last is not a reason to choose anything, and this benchmark exists to say so
rather than to claim a win.

Pick on what surrounds the interface instead: `symfony/clock` brings a `MockClock` you can
advance by an interval, a global `Clock` façade and a `now()` function; `lcobucci/clock` has a
frozen clock too. This has a system clock, a frozen one, and no third file.

## Tests

```shell
composer test
```

## The rest of Quillstack

This is one component of [Quillstack](https://github.com/quillstack), a PHP framework which is
as simple to use as it is strict about what it does.

- [quillstack/cache](https://github.com/quillstack/cache) — which asks whether an entry expired
- [quillstack/queue](https://github.com/quillstack/queue) — which asks whether a job is due
- [quillstack/datetime](https://github.com/quillstack/datetime) — dates that behave

## License

MIT. See [LICENSE](LICENSE).
