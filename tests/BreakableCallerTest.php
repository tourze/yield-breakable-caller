<?php

namespace Tourze\YieldBreakableCaller\Tests;

use PHPUnit\Framework\TestCase;
use Tourze\YieldBreakableCaller\BreakableCaller;
use Tourze\YieldBreakableCaller\Exception\GeneratorException;

/**
 * BreakableCaller 类的单元测试
 */
class BreakableCallerTest extends TestCase
{
    /**
     * 测试 invoke 方法能够完整执行所有生成器代码
     */
    public function testInvoke_CompletesFullExecution(): void
    {
        $caller = new BreakableCaller();

        $executedSteps = [];

        $callback = function () use (&$executedSteps) {
            $executedSteps[] = 'before first yield';
            yield;

            $executedSteps[] = 'after first yield';
            yield;

            $executedSteps[] = 'after second yield';
            yield;

            $executedSteps[] = 'after third yield';
        };

        $shouldNext = fn() => true;

        $caller->invoke($callback, $shouldNext);

        $this->assertCount(4, $executedSteps);
        $this->assertSame([
            'before first yield',
            'after first yield',
            'after second yield',
            'after third yield',
        ], $executedSteps);
    }

    /**
     * 测试 invoke 方法能够在指定条件下中断执行
     */
    public function testInvoke_BreaksExecution(): void
    {
        $caller = new BreakableCaller();

        $executedSteps = [];

        $callback = function () use (&$executedSteps) {
            $executedSteps[] = 'before first yield';
            yield;

            $executedSteps[] = 'after first yield';
            yield;

            $executedSteps[] = 'after second yield';
            yield;

            $executedSteps[] = 'after third yield';
        };

        $callCount = 0;
        $shouldNext = function () use (&$callCount) {
            $callCount++;
            // 第二次调用时返回 false，中断执行
            return $callCount < 2;
        };

        $caller->invoke($callback, $shouldNext);

        // 注意：根据实际代码，由于在 next() 和 shouldNext() 判断之间，
        // 已经执行了生成器的下一步，所以实际上会执行到 "after second yield"
        $this->assertCount(3, $executedSteps);
        $this->assertSame([
            'before first yield',
            'after first yield',
            'after second yield',
        ], $executedSteps);
    }

    /**
     * 测试 invoke 方法能够正确处理空生成器
     */
    public function testInvoke_WithEmptyGenerator(): void
    {
        $caller = new BreakableCaller();

        $executed = false;

        $callback = function () use (&$executed) {
            $executed = true;
            // 空生成器，有 yield 但不可达
            /** @phpstan-ignore-next-line if.alwaysFalse */
            if (false) {
                yield;
            }
        };

        $shouldNext = fn() => true;

        $caller->invoke($callback, $shouldNext);

        $this->assertTrue($executed, '回调函数应该被执行');
    }

    /**
     * 测试 invoke 方法能够正确处理只有一个 yield 点的生成器
     */
    public function testInvoke_WithSingleYieldPoint(): void
    {
        $caller = new BreakableCaller();

        $executedSteps = [];

        $callback = function () use (&$executedSteps) {
            $executedSteps[] = 'before yield';
            yield;
            $executedSteps[] = 'after yield';
        };

        $shouldNext = fn() => true;

        $caller->invoke($callback, $shouldNext);

        $this->assertCount(2, $executedSteps);
        $this->assertSame([
            'before yield',
            'after yield',
        ], $executedSteps);
    }

    /**
     * 测试第一个 yield 前后代码的执行情况
     */
    public function testInvoke_FirstYieldPoint(): void
    {
        $caller = new BreakableCaller();

        $executedSteps = [];

        $callback = function () use (&$executedSteps) {
            $executedSteps[] = 'before first yield';
            yield 'first yield value';
            $executedSteps[] = 'after first yield';
        };

        $shouldNext = fn() => false; // 立即中断执行

        $caller->invoke($callback, $shouldNext);

        // 由于实际实现在检查shouldNext前已经执行了next()，
        // 所以"after first yield"也会被执行
        $this->assertCount(2, $executedSteps);
        $this->assertSame(['before first yield', 'after first yield'], $executedSteps);
    }

    /**
     * 测试 invoke 方法使用非生成器回调时应抛出异常
     */
    public function testInvoke_WithNonGeneratorCallback(): void
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Call to a member function valid() on string');

        $caller = new BreakableCaller();

        $callback = function () {
            return 'not a generator';
        };

        $shouldNext = fn() => true;

        $caller->invoke($callback, $shouldNext);
    }

    /**
     * 测试生成器内部抛出异常的情况
     */
    public function testInvoke_WithGeneratorThrowingException(): void
    {
        $this->expectException(GeneratorException::class);
        $this->expectExceptionMessage('Exception from generator');

        $caller = new BreakableCaller();

        $callback = function () {
            yield;
            throw new GeneratorException('Exception from generator');
        };

        $shouldNext = fn() => true;

        $caller->invoke($callback, $shouldNext);
    }

    /**
     * 测试生成器 yield 值的传递行为
     * 注意：当前实现并没有使用 yield 传递的值
     */
    public function testInvoke_WithYieldValues(): void
    {
        $caller = new BreakableCaller();

        $executedSteps = [];

        $callback = function () use (&$executedSteps) {
            $executedSteps[] = 'before first yield';
            $value1 = yield 'value1';
            $executedSteps[] = 'got ' . ($value1 ?? 'null');

            $value2 = yield 'value2';
            $executedSteps[] = 'got ' . ($value2 ?? 'null');
        };

        $shouldNext = fn() => true;

        $caller->invoke($callback, $shouldNext);

        // 当前实现不返回值，所以 yield 表达式的结果为 null
        $this->assertCount(3, $executedSteps);
        $this->assertSame([
            'before first yield',
            'got null',
            'got null',
        ], $executedSteps);
    }

    /**
     * 测试一个更复杂的场景，生成器在循环中 yield
     */
    public function testInvoke_WithLoopingYield(): void
    {
        $caller = new BreakableCaller();

        $executedSteps = [];

        $callback = function () use (&$executedSteps) {
            $executedSteps[] = 'start';

            for ($i = 1; $i <= 3; $i++) {
                $executedSteps[] = "iteration $i";
                yield;
            }

            $executedSteps[] = 'end';
        };

        $shouldNext = fn() => true;

        $caller->invoke($callback, $shouldNext);

        $this->assertCount(5, $executedSteps);
        $this->assertSame([
            'start',
            'iteration 1',
            'iteration 2',
            'iteration 3',
            'end',
        ], $executedSteps);
    }

    /**
     * 测试在指定迭代次数后中断带循环的生成器
     */
    public function testInvoke_BreakLoopingYield(): void
    {
        $caller = new BreakableCaller();

        $executedSteps = [];

        $callback = function () use (&$executedSteps) {
            $executedSteps[] = 'start';

            for ($i = 1; $i <= 5; $i++) {
                $executedSteps[] = "iteration $i";
                yield;
            }

            $executedSteps[] = 'end';
        };

        $callCount = 0;
        $shouldNext = function () use (&$callCount) {
            $callCount++;
            // 在第三次调用后中断
            return $callCount < 3;
        };

        $caller->invoke($callback, $shouldNext);

        // 由于实际实现的顺序问题，执行到了第四个迭代
        $this->assertCount(5, $executedSteps);
        $this->assertSame([
            'start',
            'iteration 1',
            'iteration 2',
            'iteration 3',
            'iteration 4', // 由于 next() 在 shouldNext() 之前调用，所以会执行到这一步
        ], $executedSteps);
        $this->assertNotContains('end', $executedSteps, '循环应该在到达结束之前被中断');
    }
}
