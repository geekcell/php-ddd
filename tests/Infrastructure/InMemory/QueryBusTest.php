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
class TestQueryHandlerWithoutAttributes implements QueryHandler
{
    public function execute(Query $query): mixed
    {
        return __CLASS__;
    }
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
    public function testAddHandlerClass(): void
    {
        // Given
        $queryBus = new InMemoryQueryBus();
        $queryBus->registerHandler(new TestQueryHandler());

        // When
        $result = $queryBus->dispatch(new TestQuery());

        // Then
        self::assertEquals(TestQueryHandler::class, $result);
    }

    public function testAddHandlerClassWithoutAttributes(): void
    {
        // Given
        $queryBus = new InMemoryQueryBus();
        $queryBus->registerHandler(new TestQueryHandlerWithoutAttributes());

        // When
        $result = $queryBus->dispatch(new TestQuery());

        // Then
        self::assertNull($result);
    }

    public function testAddCallableHandler(): void
    {
        // Given
        $queryBus = new InMemoryQueryBus();
        $queryBus->registerHandler(new CallableQueryHandler());

        // When
        $result = $queryBus->dispatch(new TestQuery());

        // Then
        self::assertEquals(CallableQueryHandler::class, $result);
    }

    public function testAddCallableHandlerWithUnknownCommand(): void
    {
        // Given
        $queryBus = new InMemoryQueryBus();
        $queryBus->registerHandler(new CallableQueryHandler());

        // When
        $result = $queryBus->dispatch(new UnknownQuery());

        // Then
        self::assertNull($result);
    }
}
