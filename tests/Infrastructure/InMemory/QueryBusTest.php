<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Tests\Infrastructure\InMemory;

use GeekCell\Ddd\Contracts\Application\Query;
use GeekCell\Ddd\Contracts\Application\QueryHandler;
use GeekCell\Ddd\Infrastructure\InMemory\QueryBus as InMemoryQueryBus;
use GeekCell\Ddd\Support\Attributes\For;
use PHPUnit\Framework\TestCase;

/**
 * Test fixture for InMemoryQueryBus.
 */
class TestQuery implements Query
{
}

/**
 * Test fixture for InMemoryQueryBus.
 */
class UnknownQuery implements Query
{
}

/**
 * Test fixture for InMemoryQueryBus.
 */
#[For\Query(TestQuery::class)]
class TestQueryHandler implements QueryHandler
{
    public function execute(Query $query): mixed
    {
        return __CLASS__;
    }
}

/**
 * Test fixture for InMemoryQueryBus.
 */
#[For\Query(TestQuery::class, 'handle')]
class TestQueryHandlerWithExplicitHandleMethod implements QueryHandler
{
    public function handle(TestQuery $query): mixed
    {
        return __CLASS__;
    }
}

/**
 * Test fixture for InMemoryQueryBus.
 */
class TestQueryHandlerWithoutAttributes implements QueryHandler
{
    public function execute(Query $query): mixed
    {
        return __CLASS__;
    }
}

/**
 * Test fixture for InMemoryCommandBus.
 */
#[For\Query(TestQuery::class)]
class TestQueryHandlerWithoutExecuteMethod implements QueryHandler
{
}

/**
 * Test fixture for InMemoryQueryBus.
 */
class CallableQueryHandler
{
    public function __invoke(TestQuery $command): mixed
    {
        return __CLASS__;
    }
}

final class QueryBusTest extends TestCase
{
    public function testRegisterHandlerClass(): void
    {
        // Given
        $queryBus = new InMemoryQueryBus();
        $queryBus->registerHandler(new TestQueryHandler());

        // When
        $result = $queryBus->dispatch(new TestQuery());

        // Then
        self::assertEquals(TestQueryHandler::class, $result);
    }

    public function testRegisterHandlerClassWithExplicitHandleMethod(): void
    {
        // Given
        $queryBus = new InMemoryQueryBus();
        $queryBus->registerHandler(
            new TestQueryHandlerWithExplicitHandleMethod());

        // When
        $result = $queryBus->dispatch(new TestQuery());

        // Then
        self::assertEquals(
            TestQueryHandlerWithExplicitHandleMethod::class,
            $result
        );
    }

    public function testRegisterHandlerClassWithoutAttributes(): void
    {
        // Given
        $queryBus = new InMemoryQueryBus();
        $queryBus->registerHandler(new TestQueryHandlerWithoutAttributes());

        // When
        $result = $queryBus->dispatch(new TestQuery());

        // Then
        self::assertNull($result);
    }

    public function testRegisterHandlerClassWithoutExecuteMethod(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new InMemoryQueryBus())->registerHandler(
            new TestQueryHandlerWithoutExecuteMethod());
    }

    public function testRegisterCallableHandler(): void
    {
        // Given
        $queryBus = new InMemoryQueryBus();
        $queryBus->registerHandler(new CallableQueryHandler());

        // When
        $result = $queryBus->dispatch(new TestQuery());

        // Then
        self::assertEquals(CallableQueryHandler::class, $result);
    }

    public function testRegisterCallableHandlerWithAnonymousFunction(): void
    {
        // Given
        $queryBus = new InMemoryQueryBus();
        $queryBus->registerHandler(fn (TestQuery $command) => 'function');

        // When
        $result = $queryBus->dispatch(new TestQuery());

        // Then
        self::assertEquals('function', $result);
    }

    public function testRegisterCallableHandlerWithUnknownCommand(): void
    {
        // Given
        $queryBus = new InMemoryQueryBus();
        $queryBus->registerHandler(new CallableQueryHandler());

        // When
        $result = $queryBus->dispatch(new UnknownQuery());

        // Then
        self::assertNull($result);
    }

    public function testRegisterCallableHandlerWithMissingParameter(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new InMemoryQueryBus())
            ->registerHandler(fn (string $foo) => 'this will never be called');
    }
}
