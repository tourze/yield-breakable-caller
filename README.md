# YieldBreakableCaller

一个基于 yield 的可中断调用器，支持在执行过程中根据条件中断任务。

## 安装

```bash
composer require tourze/yield-breakable-caller
```

## 使用方法

```php
use Tourze\YieldBreakableCaller\BreakableCaller;

$caller = new BreakableCaller();

// 创建一个生成器函数
$task = function () {
    echo "步骤 1 开始执行\n";
    yield;
    
    echo "步骤 2 开始执行\n";
    yield;
    
    echo "步骤 3 开始执行\n";
    yield;
    
    echo "步骤 4 开始执行\n";
};

// 中断条件
$shouldContinue = function () {
    // 这里可以添加任何条件判断
    static $count = 0;
    $count++;
    return $count < 3; // 在执行到第三步时中断
};

// 执行任务
$caller->invoke($task, $shouldContinue);
```

以上代码将输出：

```
步骤 1 开始执行
步骤 2 开始执行
步骤 3 开始执行
```

步骤 4 不会被执行，因为在第三步后条件判断为 false，任务被中断。

## 特性

- 使用 PHP Generator (yield) 实现的任务步骤管理
- 支持在任意步骤中断任务执行
- 简单轻量的实现，无额外依赖
- 适用于需要分步执行并可能需要中断的任务场景

## 许可证

MIT
