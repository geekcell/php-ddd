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
class CallableCommandHandler
{
    public function __invoke(TestCommand $command): mixed
    {
        return __CLASS__;
    }
}

final class CommandBusTest extends TestCase
{
    public function testAddHandlerClass(): void
    {
        // Given
        $commandBus = new InMemoryCommandBus();
        $commandBus->registerHandler(new TestCommandHandler());

        // When
        $result = $commandBus->dispatch(new TestCommand());

        // Then
        self::assertEquals(TestCommandHandler::class, $result);
    }

    public function testAddHandlerClassWithoutAttributes(): void
    {
        // Given
        $commandBus = new InMemoryCommandBus();
        $commandBus->registerHandler(new TestCommandHandlerWithoutAttributes());

        // When
        $result = $commandBus->dispatch(new TestCommand());

        // Then
        self::assertNull($result);
    }

    public function testAddCallableHandler(): void
    {
        // Given
        $commandBus = new InMemoryCommandBus();
        $commandBus->registerHandler(new CallableCommandHandler());

        // When
        $result = $commandBus->dispatch(new TestCommand());

        // Then
        self::assertEquals(CallableCommandHandler::class, $result);
    }

    public function testAddCallableHandlerWithUnknownCommand(): void
    {
        // Given
        $commandBus = new InMemoryCommandBus();
        $commandBus->registerHandler(new CallableCommandHandler());

        // When
        $result = $commandBus->dispatch(new UnknownCommand());

        // Then
        self::assertNull($result);
    }
}
