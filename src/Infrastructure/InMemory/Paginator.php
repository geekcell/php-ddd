<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Infrastructure\InMemory;

use ArrayAccess;
use EmptyIterator;
use GeekCell\Ddd\Contracts\Domain\Paginator as PaginatorInterface;
use GeekCell\Ddd\Domain\Collection;
use LimitIterator;
use Traversable;

/**
 * @template T of object
 * @implements PaginatorInterface<T>
 * @implements ArrayAccess<mixed, T>
 */
class Paginator implements PaginatorInterface, ArrayAccess
{
    /**
     * @param Collection<T> $collection
     * @param int $itemsPerPage
     * @param int $currentPage
     */
    public function __construct(
        private readonly Collection $collection,
        private int $itemsPerPage,
        private int $currentPage = 1,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage < 1 ? 1 : $this->currentPage;
    }

    /**
     * @inheritDoc
     */
    public function getTotalPages(): int
    {
        return (int) ceil(count($this->collection) / $this->itemsPerPage);
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * @inheritDoc
     */
    public function getTotalItems(): int
    {
        return count($this->collection);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        if (!is_int($offset)) {
            return false;
        }

        return $offset >= 0 && $offset < $this->count();
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset): mixed
    {
        if (!$this->offsetExists($offset)) {
            return null;
        }

        $realOffset = $offset + $this->getCurrentPage();
        foreach ($this->getIterator() as $index => $item) {
            if ($index === $realOffset) {
                return $item;
            }
        }

        return null;
    }

    /**
     * This method is not supported since it is not appropriate for a paginator.
     *
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        // Unsupported since it is not appropriate for a paginator.
    }

    /**
     * This method is not supported since it is not appropriate for a paginator.
     *
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {
        // Unsupported since it is not appropriate for a paginator.
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return iterator_count($this->getIterator());
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        $currentPage = $this->getCurrentPage();
        if ($currentPage > $this->getTotalPages()) {
            return new EmptyIterator();
        }

        $offset = ($currentPage - 1) * $this->itemsPerPage;

        return new LimitIterator(
            $this->collection->getIterator(),
            $offset,
            $this->itemsPerPage
        );
    }
}
