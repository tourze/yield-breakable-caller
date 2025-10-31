<?php

declare(strict_types=1);

namespace Tourze\YieldBreakableCaller;

/**
 * 一个基于yield的invoker，支持业务中断
 */
class BreakableCaller
{
    public function invoke(callable $callback, callable $shouldNext): void
    {
        // 调用回调函数执行任务
        $generator = $callback();

        // 模拟循环迭代任务
        while ($generator->valid()) {
            // 继续执行生成器函数的部分代码
            $generator->next();

            // 根据逻辑判断是否继续任务
            if (!$shouldNext()) {
                // $generator->send('break');
                break;
            }
        }
    }
}
