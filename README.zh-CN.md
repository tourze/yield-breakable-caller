# YieldBreakableCaller

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/yield-breakable-caller.svg?style=flat-square)](https://packagist.org/packages/tourze/yield-breakable-caller)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/yield-breakable-caller.svg?style=flat-square)](https://packagist.org/packages/tourze/yield-breakable-caller)
[![PHP Version](https://img.shields.io/packagist/php-v/tourze/yield-breakable-caller.svg?style=flat-square)](https://packagist.org/packages/tourze/yield-breakable-caller)
[![License](https://img.shields.io/github/license/tourze/yield-breakable-caller.svg?style=flat-square)](https://github.com/tourze/yield-breakable-caller/blob/master/LICENSE)
[![Coverage Status](https://img.shields.io/codecov/c/github/tourze/yield-breakable-caller.svg?style=flat-square)](https://codecov.io/gh/tourze/yield-breakable-caller)

一个基于 PHP Generator (yield) 实现的可中断调用器，支持在任务执行过程中根据自定义条件中断。

## 功能特性

- 基于 yield 的任务分步执行与中断
- 支持任意条件下中断任务流程
- 实现简单、无额外依赖
- 适用于分步流程、可控中断场景

---

## 安装说明

- 需要 PHP >= 8.1
- 通过 Composer 安装：

```bash
composer require tourze/yield-breakable-caller
```

---

## 快速开始

```php
use Tourze\YieldBreakableCaller\BreakableCaller;

$caller = new BreakableCaller();

$task = function () {
    echo "步骤 1 开始执行\n";
    yield;
    echo "步骤 2 开始执行\n";
    yield;
    echo "步骤 3 开始执行\n";
    yield;
    echo "步骤 4 开始执行\n";
};

$shouldContinue = function () {
    static $count = 0;
    $count++;
    return $count < 3; // 执行到第3步时中断
};

$caller->invoke($task, $shouldContinue);
```

输出：

```text
步骤 1 开始执行
步骤 2 开始执行
步骤 3 开始执行
```

---

## 详细文档

### 核心 API

#### `BreakableCaller::invoke(callable $callback, callable $shouldNext): void`

- `$callback`：返回生成器的回调函数
- `$shouldNext`：每步执行后判断是否继续的回调

### 行为说明

- 每次 `yield` 后，判断 `$shouldNext`，返回 `false` 时中断后续所有步骤
- 支持空生成器、单步生成器及非生成器回调（会抛出异常）

---

## 贡献指南

欢迎为这个库做出贡献。请遵循以下准则：

- 提交 Issue 报告错误或功能请求
- 遵循 PSR 代码规范
- 为新功能编写测试
- 更新文档（如有需要）

### 开发环境

1. 克隆仓库
2. 安装依赖：`composer install`
3. 运行测试：`composer test` 或 `vendor/bin/phpunit`
4. 运行静态分析：`composer phpstan` 或 `vendor/bin/phpstan analyse`

## 版权和许可

该库采用 MIT 许可证。详情请参阅 [LICENSE](LICENSE) 文件。

## 更新日志

版本历史和更新请参见 [CHANGELOG.md](CHANGELOG.md)（如有）。
