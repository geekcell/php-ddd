<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Infrastructure\InMemory;

use Assert\Assert;
use GeekCell\Ddd\Contracts\Domain\Paginator;
use GeekCell\Ddd\Contracts\Domain\Repository as RepositoryInterface;
use GeekCell\Ddd\Domain\Collection;
use GeekCell\Ddd\Infrastructure\InMemory\Paginator as InMemoryPaginator;
use Traversable;

abstract class Repository implements RepositoryInterface
{
    /**
     * @var T[]
     */
    protected array $items = [];

    /**
     * @template T of object
     * @extends IteratorAggregate<T>
     *
     * @param class-string<T> $itemType
     */
    public function __construct(
        private string $itemType,
    ) {
        Assert::that($this->itemType)->classExists();
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
