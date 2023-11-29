<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Infrastructure\InMemory;

use Assert\Assert;
use GeekCell\Ddd\Contracts\Domain\Paginator;
use GeekCell\Ddd\Contracts\Domain\Repository as RepositoryInterface;
use GeekCell\Ddd\Domain\Collection;
use GeekCell\Ddd\Infrastructure\InMemory\Paginator as InMemoryPaginator;
use Traversable;

/**
 * @template T of object
 * @extends RepositoryInterface<T>
 */
abstract class Repository implements RepositoryInterface
{
    /**
     * @param class-string<T> $itemType
     * @param T[] $items
     */
    public function __construct(
        private string $itemType,
        protected array $items = []
    ) {
        Assert::that($this->itemType)->classExists();
        Assert::thatAll($this->items)->isInstanceOf($this->itemType);
    }

    /**
     * @inheritDoc
     */
    public function collect(): Collection
    {
        return new Collection($this->items, $this->itemType);
    }

    /**
     * @inheritDoc
     */
    public function paginate(int $itemsPerPage, int $currentPage = 1): Paginator
    {
        return new InMemoryPaginator(
            $this->collect(),
            $itemsPerPage,
            $currentPage
        );
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return $this->collect()->getIterator();
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count(iterator_to_array($this->getIterator()));
    }
}
