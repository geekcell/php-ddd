<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Tests\Infrastructure\InMemory;

use ArrayIterator;
use EmptyIterator;
use GeekCell\Ddd\Domain\Collection;
use GeekCell\Ddd\Infrastructure\InMemory\Paginator as InMemoryPaginator;
use GeekCell\Ddd\Tests\Fixtures\Counter;
use LimitIterator;
use Mockery;
use PHPUnit\Framework\TestCase;

class PaginatorTest extends TestCase
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

    public function testArrayAccess(): void
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
        $result1 = $paginator[0];
        $result2 = $paginator[4];

        // Then
        $this->assertInstanceOf(Counter::class, $result1);
        $this->assertEquals(3, $result1->getValue());
        $this->assertNull($result2);
    }

    public function testArrayAccessWithInvalidOffset(): void
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

        // Then
        $this->assertNull($paginator[-1]);
        $this->assertNull($paginator[0.5]);
        $this->assertNull($paginator['invalid']);
    }

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
