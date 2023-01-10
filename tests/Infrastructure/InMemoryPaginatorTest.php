<?php

declare(strict_types=1);

use GeekCell\Ddd\Domain\Collection;
use GeekCell\Ddd\Infrastructure\InMemoryPaginator;
use PHPUnit\Framework\TestCase;

/**
 * Test fixture for InMemoryPaginator.
 *
 * @package GeekCell\Ddd\Tests\Infrastructure
 */
class Counter
{
    public function __construct(private int $value)
    {
    }
}

class InMemoryPaginatorTest extends TestCase
{
    public function testGetCurrentPage(): void
    {
        // Given
        $collection = Mockery::mock(Collection::class);

        // When
        $paginator = new InMemoryPaginator($collection, 10, 2);
        $result = $paginator->getCurrentPage();

        // Then
        $this->assertEquals(2, $result);
    }

    public function testGetCurrenPageDefaultValue(): void
    {
        // Given
        $collection = Mockery::mock(Collection::class);

        // When
        $paginator = new InMemoryPaginator($collection, 10);
        $result = $paginator->getCurrentPage();

        // Then
        $this->assertEquals(1, $result);
    }

    public function testGetCurrentPageWithNegativePage(): void
    {
        // Given
        $collection = Mockery::mock(Collection::class);

        // When
        $paginator = new InMemoryPaginator($collection, 10, -1);
        $result = $paginator->getCurrentPage();

        // Then
        $this->assertEquals(1, $result);
    }

    public function testGetTotalPages(): void
    {
        // Given

        /** @var Mockery\MockInterface $collection */
        $collection = Mockery::mock(Collection::class);
        $collection->shouldReceive('count')->andReturn(10);

        // When

        /** @var Collection $collection */
        $paginator = new InMemoryPaginator($collection, 3, 1);
        $result = $paginator->getTotalPages();

        // Then
        $this->assertEquals(4, $result);
    }

    public function testGetTotalItems(): void
    {
        // Given

        /** @var Mockery\MockInterface $collection */
        $collection = Mockery::mock(Collection::class);
        $collection->shouldReceive('count')->andReturn(10);

        // When

        /** @var Collection $collection */
        $paginator = new InMemoryPaginator($collection, 10);
        $result = $paginator->getTotalItems();

        // Then
        $this->assertEquals(10, $result);
    }

    /**
     * @covers \GeekCell\Ddd\Infrastructure\InMemoryPaginator::getIterator
     * @covers \GeekCell\Ddd\Infrastructure\InMemoryPaginator::count
     */
    public function testGetIteratorAndCount(): void
    {
        // Given
        $items = [
            new Counter(1),
            new Counter(2),
            new Counter(3),
            new Counter(4),
            new Counter(5),
        ];

        /** @var Mockery\MockInterface $collection */
        $collection = Mockery::mock(Collection::class);
        $collection
            ->shouldReceive('getIterator')
            ->andReturn(new ArrayIterator($items));
        $collection
            ->shouldReceive('count')
            ->andReturn(count($items));

        // When

        /** @var Collection $collection */
        $paginator = new InMemoryPaginator($collection, 2, 2);
        $result = $paginator->getIterator();

        // Then
        $this->assertInstanceOf(LimitIterator::class, $result);
        $this->assertEquals(
            [new Counter(3), new Counter(4)],
            iterator_to_array($result, false),
        );
        $this->assertEquals(2, count($paginator));
    }

    /**
     * @covers \GeekCell\Ddd\Infrastructure\InMemoryPaginator::getIterator
     * @covers \GeekCell\Ddd\Infrastructure\InMemoryPaginator::count
     */
    public function testGetIteratorWithEmptyCollection(): void
    {
        // Given
        $items = [];

        /** @var Mockery\MockInterface $collection */
        $collection = Mockery::mock(Collection::class);
        $collection
            ->shouldReceive('getIterator')
            ->andReturn(new ArrayIterator($items));
        $collection
            ->shouldReceive('count')
            ->andReturn(count($items));

        // When

        /** @var Collection $collection */
        $paginator = new InMemoryPaginator($collection, 2, 2);
        $result = $paginator->getIterator();

        // Then
        $this->assertInstanceOf(EmptyIterator::class, $result);
        $this->assertEquals([], iterator_to_array($result, false));
        $this->assertEquals(0, count($paginator));
    }

    /**
     * @covers \GeekCell\Ddd\Infrastructure\InMemoryPaginator::getIterator
     * @covers \GeekCell\Ddd\Infrastructure\InMemoryPaginator::count
     */
    public function testGetIteratorWithCurrentPageTooHigh(): void
    {
        // Given
        $items = [
            new Counter(1),
            new Counter(2),
            new Counter(3),
            new Counter(4),
            new Counter(5),
        ];

        /** @var Mockery\MockInterface $collection */
        $collection = Mockery::mock(Collection::class);
        $collection
            ->shouldReceive('getIterator')
            ->andReturn(new ArrayIterator($items));
        $collection
            ->shouldReceive('count')
            ->andReturn(count($items));

        // When

        /** @var Collection $collection */
        $paginator = new InMemoryPaginator($collection, 5, 2);
        $result = $paginator->getIterator();

        // Then
        $this->assertInstanceOf(EmptyIterator::class, $result);
        $this->assertEquals([], iterator_to_array($result, false));
        $this->assertEquals(0, count($paginator));
    }
}
