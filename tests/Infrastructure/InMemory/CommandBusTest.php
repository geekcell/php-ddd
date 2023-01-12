<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Tests\Infrastructure\InMemory;

use GeekCell\Ddd\Contracts\Application\Command;
use GeekCell\Ddd\Contracts\Application\CommandHandler;
use GeekCell\Ddd\Infrastructure\InMemory\CommandBus as InMemoryCommandBus;
use GeekCell\Ddd\Support\Attributes\For;
use PHPUnit\Framework\TestCase;

/**
 * Test fixture for InMemoryCommandBus.
 */
class TestCommand implements Command
{
}

/**
 * Test fixture for InMemoryCommandBus.
 */
class UnknownCommand implements Command
{
}

/**
 * Test fixture for InMemoryCommandBus.
 */
#[For\Command(TestCommand::class)]
class TestCommandHandler implements CommandHandler
{
    public function execute(Command $command): mixed
    {
        return __CLASS__;
    }
}

/**
 * Test fixture for InMemoryCommandBus.
 */
#[For\Command(TestCommand::class, 'handle')]
class TestCommandHandlerWithExplicitHandleMethod implements CommandHandler
{
    public function handle(TestCommand $command): mixed
    {
        return __CLASS__;
    }
}

/**
 * Test fixture for InMemoryCommandBus.
 */
class TestCommandHandlerWithoutAttributes implements CommandHandler
{
    public function execute(Command $command): mixed
    {
        return __CLASS__;
    }
}

/**
 * Test fixture for InMemoryCommandBus.
 */
#[For\Command(TestCommand::class)]
class TestComamndHandlerWithoutExecuteMethod implements CommandHandler
{
}

/**
 * Test fixture for InMemoryCommandBus.
 */
class CallableCommandHandler
{
    public function __invoke(TestCommand $command): mixed
    {
        return __CLASS__;
    }
}

final class CommandBusTest extends TestCase
{
    public function testRegisterHandlerClass(): void
    {
        // Given
        $commandBus = new InMemoryCommandBus();
        $commandBus->registerHandler(new TestCommandHandler());

        // When
        $result = $commandBus->dispatch(new TestCommand());

        // Then
        self::assertEquals(TestCommandHandler::class, $result);
    }

    public function testRegisterHandlerClassWithExplicitHandleMethod(): void
    {
        // Given
        $commandBus = new InMemoryCommandBus();
        $commandBus->registerHandler(
            new TestCommandHandlerWithExplicitHandleMethod());

        // When
        $result = $commandBus->dispatch(new TestCommand());

        // Then
        self::assertEquals(
            TestCommandHandlerWithExplicitHandleMethod::class,
            $result,
        );
    }

    public function testRegisterHandlerClassWithoutAttributes(): void
    {
        // Given
        $commandBus = new InMemoryCommandBus();
        $commandBus->registerHandler(new TestCommandHandlerWithoutAttributes());

        // When
        $result = $commandBus->dispatch(new TestCommand());

        // Then
        self::assertNull($result);
    }

    public function testRegisterHandlerClassWithoutExecuteMethod(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new InMemoryCommandBus())
            ->registerHandler(new TestComamndHandlerWithoutExecuteMethod());
    }

    public function testRegisterCallableHandler(): void
    {
        // Given
        $commandBus = new InMemoryCommandBus();
        $commandBus->registerHandler(new CallableCommandHandler());

        // When
        $result = $commandBus->dispatch(new TestCommand());

        // Then
        self::assertEquals(CallableCommandHandler::class, $result);
    }

    public function testRegisterCallableHandlerWithAnonymousFunction(): void
    {
        // Given
        $commandBus = new InMemoryCommandBus();
        $commandBus->registerHandler(fn (TestCommand $command) => 'function');

        // When
        $result = $commandBus->dispatch(new TestCommand());

        // Then
        self::assertEquals('function', $result);
    }

    public function testRegisterCallableHandlerWithUnknownCommand(): void
    {
        // Given
        $commandBus = new InMemoryCommandBus();
        $commandBus->registerHandler(new CallableCommandHandler());

        // When
        $result = $commandBus->dispatch(new UnknownCommand());

        // Then
        self::assertNull($result);
    }

    public function testRegisterCallableHandlerWithMissingParameter(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new InMemoryCommandBus())
            ->registerHandler(fn (string $foo) => 'this will never be called');
    }
}
