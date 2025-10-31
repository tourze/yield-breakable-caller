<?php

declare(strict_types=1);

namespace Tourze\YieldBreakableCaller\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use Tourze\YieldBreakableCaller\Exception\GeneratorException;

/**
 * GeneratorException 异常类的单元测试
 *
 * @internal
 */
#[CoversClass(GeneratorException::class)]
final class GeneratorExceptionTest extends AbstractExceptionTestCase
{
    /**
     * 测试异常能够正确创建并包含消息
     */
    public function testExceptionCanBeCreatedWithMessage(): void
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
    public function testExceptionCanBeCreatedWithMessageAndCode(): void
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
    public function testExceptionCanBeCreatedWithPreviousException(): void
    {
        $previousException = new \Exception('Previous exception');
        $message = 'Test exception message';
        $exception = new GeneratorException($message, 0, $previousException);

        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($previousException, $exception->getPrevious());
    }
}
