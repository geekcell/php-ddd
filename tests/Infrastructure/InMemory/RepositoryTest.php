<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Tests\Infrastructure\InMemory;

use Assert;
use GeekCell\Ddd\Domain\Collection;
use GeekCell\Ddd\Infrastructure\InMemory\Paginator as InMemoryPaginator;
use GeekCell\Ddd\Infrastructure\InMemory\Repository as InMemoryRepository;
use GeekCell\Ddd\Tests\Fixtures\Counter;
use PHPUnit\Framework\TestCase;

/**
 * Test fixture for InMemoryRepository.
 *
 * @package GeekCell\Ddd\Tests\Infrastructure
 */
class CounterCollection extends Collection
{
    protected function itemType(): string
    {
        return Counter::class;
    }
}

/**
 * Test subject.
 *
 * @package GeekCell\Ddd\Tests\Infrastructure
 */
class InMemoryTestRepository extends InMemoryRepository
{
    public function __construct()
    {
        parent::__construct(
            Counter::class,
            CounterCollection::class
        );
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    public function isPaginated(): bool
    {
        return $this->isPaginated;
    }

    public function getItemsPerPage(): ?int
    {
        return $this->itemsPerPage;
    }

    public function getCurrentPage(): ?int
    {
        return $this->currentPage;
    }
}

class InMemoryRepositoryTest extends TestCase
{
    /**
     * @var Counter[]
     */
    private array $items;

    public function setUp(): void
    {
        parent::setUp();

        $this->items = [
            new Counter(1),
            new Counter(2),
            new Counter(3),
            new Counter(4),
            new Counter(5),
        ];
    }

    public function testConstructWithInvalidTypes(): void
    {
        // Then
        $this->expectException(Assert\InvalidArgumentException::class);

        // When
        $instance = new class () extends InMemoryRepository {
            public function __construct()
            {
                parent::__construct(
                    'Foo\Bar\Baz',
                    'Foo\Bar\BazCollection'
                );
            }
        };
    }

    /**
     * @covers \GeekCell\Ddd\Infrastructure\InMemoryRepository::collect
     * @covers \GeekCell\Ddd\Infrastructure\InMemoryRepository::count
     */
    public function testCollectAndCount(): void
    {
        // Given
        $repository = new InMemoryTestRepository();
        $repository->setItems($this->items);

        // When
        $result = $repository->collect();

        // Then
        $this->assertFalse($result->isPaginated());
        $this->assertNull($result->getItemsPerPage());
        $this->assertNull($result->getCurrentPage());
        $this->assertEquals(count($this->items), count($result));
    }

    /**
     * @covers \GeekCell\Ddd\Infrastructure\InMemoryRepository::paginate
     * @covers \GeekCell\Ddd\Infrastructure\InMemoryRepository::count
     */
    public function testPaginateAndCount(): void
    {
        // Given
        $repository = new InMemoryTestRepository();
        $repository->setItems($this->items);

        // When
        $result = $repository->paginate(2);

        // Then
        $this->assertTrue($result->isPaginated());
        $this->assertEquals(2, $result->getItemsPerPage());
        $this->assertEquals(1, $result->getCurrentPage());
        $this->assertEquals(2, count($result));
    }

    public function testGetIterator(): void
    {
        // Given
        $repository = new InMemoryTestRepository();
        $repository->setItems($this->items);

        // When
        $result = $repository->collect();

        // Then
        $this->assertInstanceOf(Collection::class, $result->getIterator());
    }

    public function testGetIteratorWithPagination(): void
    {
        // Given
        $repository = new InMemoryTestRepository();
        $repository->setItems($this->items);

        // When
        $result = $repository->paginate(2);

        // Then
        $this->assertInstanceOf(
            InMemoryPaginator::class,
            $result->getIterator()
        );
    }
}
