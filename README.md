# YieldBreakableCaller

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/yield-breakable-caller.svg?style=flat-square)](https://packagist.org/packages/tourze/yield-breakable-caller)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/yield-breakable-caller.svg?style=flat-square)](https://packagist.org/packages/tourze/yield-breakable-caller)
[![PHP Version](https://img.shields.io/packagist/php-v/tourze/yield-breakable-caller.svg?style=flat-square)](https://packagist.org/packages/tourze/yield-breakable-caller)
[![License](https://img.shields.io/github/license/tourze/yield-breakable-caller.svg?style=flat-square)](https://github.com/tourze/yield-breakable-caller/blob/master/LICENSE)
[![Coverage Status](https://img.shields.io/codecov/c/github/tourze/yield-breakable-caller.svg?style=flat-square)](https://codecov.io/gh/tourze/yield-breakable-caller)

A lightweight PHP library for breakable step-by-step task execution using Generators (yield). Allows interruption of task flow at any step based on custom conditions.

## Features

- Step-by-step task execution based on PHP Generator (yield)
- Interrupt task flow at any step by custom logic
- Simple, dependency-free implementation
- Suitable for scenarios requiring controllable breakpoints in process

---

## Installation

- Requires PHP >= 8.1
- Install via Composer:

```bash
composer require tourze/yield-breakable-caller
```

---

## Quick Start

```php
use Tourze\YieldBreakableCaller\BreakableCaller;

$caller = new BreakableCaller();

$task = function () {
    echo "Step 1 executing\n";
    yield;
    echo "Step 2 executing\n";
    yield;
    echo "Step 3 executing\n";
    yield;
    echo "Step 4 executing\n";
};

$shouldContinue = function () {
    static $count = 0;
    $count++;
    return $count < 3; // Interrupt after 3rd step
};

$caller->invoke($task, $shouldContinue);
```

Output:

```text
Step 1 executing
Step 2 executing
Step 3 executing
```

---

## Documentation

### Core API

#### `BreakableCaller::invoke(callable $callback, callable $shouldNext): void`

- `$callback`: A function returning a generator
- `$shouldNext`: A callback to determine whether to continue after each step

### Behavior

- After each `yield`, `$shouldNext` is evaluated. If it returns `false`, all subsequent steps are interrupted.
- Supports empty generators, single-yield generators, and will throw if callback does not return a generator.

---

## Contributing

We welcome contributions to improve this library. Please follow these guidelines:

- Submit issues for bugs or feature requests
- Follow PSR coding standards
- Write tests for new features
- Update documentation when needed

### Development

1. Clone the repository
2. Install dependencies: `composer install`
3. Run tests: `composer test` or `vendor/bin/phpunit`
4. Run static analysis: `composer phpstan` or `vendor/bin/phpstan analyse`

## License

This library is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for version history and updates (if available).
