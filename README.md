# YieldBreakableCaller

A lightweight PHP library for breakable step-by-step task execution using Generators (yield). Allows interruption of task flow at any step based on custom conditions.

---

## Project Status

![Packagist Version](https://img.shields.io/packagist/v/tourze/yield-breakable-caller)
![License](https://img.shields.io/github/license/tourze/yield-breakable-caller)

---

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

```
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

- Issues and PRs are welcome
- Follow PSR coding standards
- Tests should cover main features

---

## License

MIT License

---

## Changelog

See [CHANGELOG.md] if available.

MIT
