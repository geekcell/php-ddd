<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Infrastructure\InMemory;

use EmptyIterator;
use GeekCell\Ddd\Contracts\Domain\Paginator as PaginatorInterface;
use GeekCell\Ddd\Domain\Collection;
use LimitIterator;
use Traversable;

class Paginator implements PaginatorInterface
{
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
