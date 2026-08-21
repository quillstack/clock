# Quillstack Clock

[![Tests](https://github.com/quillstack/clock/actions/workflows/tests.yml/badge.svg)](https://github.com/quillstack/clock/actions/workflows/tests.yml)

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
