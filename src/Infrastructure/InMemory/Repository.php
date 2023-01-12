<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Infrastructure\InMemory;

use Assert\Assert;
use GeekCell\Ddd\Contracts\Domain\Repository as RepositoryInterface;
use GeekCell\Ddd\Infrastructure\InMemory\Paginator as InMemoryPaginator;
use Traversable;

abstract class Repository implements RepositoryInterface
{
    /**
     * @var T[]
     */
    protected array $items = [];

    /**
     * @var int|null
     */
    protected ?int $itemsPerPage = null;

    /**
     * @var int|null
     */
    protected ?int $currentPage = null;

    /**
     * @var bool
     */
    protected bool $isPaginated = false;

    /**
     * @template T of object
     * @extends IteratorAggregate<T>
     */
    public function __construct()
    {
        Assert::that($this->itemType())->classExists();
        Assert::that($this->collectionType())->classExists();
    }

    /**
     * @inheritDoc
     */
    public function collect(): static
    {
        $clone = clone $this;
        $clone->itemsPerPage = null;
        $clone->currentPage = null;
        $clone->isPaginated = false;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function paginate(int $itemsPerPage, int $currentPage = 1): static
    {
        $clone = clone $this;
        $clone->itemsPerPage = $itemsPerPage;
        $clone->currentPage = $currentPage;
        $clone->isPaginated = true;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        $collectionClass = $this->collectionType();
        $collection = new $collectionClass($this->items);
        if ($this->isPaginated) {
            return new InMemoryPaginator(
                $collection,
                $this->itemsPerPage,
                $this->currentPage
            );
        }

        return $collection;
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count(iterator_to_array($this->getIterator()));
    }

    /**
     * Return the type of the items managed in the repository.
     * This is a poor man's workaround for missing generics in PHP.
     *
     * @return string
     */
    abstract protected function itemType(): string;

    /**
     * Return the type of the collection managed in the repository.
     * This is a poor man's workaround for missing generics in PHP.
     */
    abstract protected function collectionType(): string;
}
