# YieldBreakableCaller

一个基于 PHP Generator (yield) 实现的可中断调用器，支持在任务执行过程中根据自定义条件中断。

---

## 项目状态

![Packagist Version](https://img.shields.io/packagist/v/tourze/yield-breakable-caller)
![License](https://img.shields.io/github/license/tourze/yield-breakable-caller)

---

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

```
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

- 欢迎通过 Issue 或 PR 贡献代码
- 遵循 PSR 代码规范
- 测试需覆盖主要功能

---

## 版权和许可

- MIT License

---

## 更新日志

详见 [CHANGELOG.md]（如有）
