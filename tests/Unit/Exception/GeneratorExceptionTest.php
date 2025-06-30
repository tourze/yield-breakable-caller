<?php

namespace Tourze\YieldBreakableCaller\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Tourze\YieldBreakableCaller\Exception\GeneratorException;

/**
 * GeneratorException 异常类的单元测试
 */
class GeneratorExceptionTest extends TestCase
{
    /**
     * 测试异常能够正确创建并包含消息
     */
    public function testException_CanBeCreatedWithMessage(): void
    {
        $message = 'Test exception message';
        $exception = new GeneratorException($message);

        $this->assertInstanceOf(GeneratorException::class, $exception);
        $this->assertInstanceOf(\RuntimeException::class, $exception);
        $this->assertSame($message, $exception->getMessage());
    }

    /**
     * 测试异常能够正确创建并包含消息和代码
     */
    public function testException_CanBeCreatedWithMessageAndCode(): void
    {
        $message = 'Test exception message';
        $code = 500;
        $exception = new GeneratorException($message, $code);

        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($code, $exception->getCode());
    }

    /**
     * 测试异常能够正确创建并包含前一个异常
     */
    public function testException_CanBeCreatedWithPreviousException(): void
    {
        $previousException = new \Exception('Previous exception');
        $message = 'Test exception message';
        $exception = new GeneratorException($message, 0, $previousException);

        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($previousException, $exception->getPrevious());
    }
}