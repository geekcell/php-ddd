<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Contracts\Domain;

use Countable;
use GeekCell\Ddd\Domain\Collection;
use IteratorAggregate;

interface Repository extends Countable, IteratorAggregate
{
    /**
     * Returns a collection of items.
     *
     * @return Collection
     */
    public function collect(): Collection;

    /**
     * Returns a paginator to paginate the items.
     *
     * @param int $itemsPerPage
     * @param int $currentPage
     *
     * @return Paginator
     */
    public function paginate(
        int $itemsPerPage,
        int $currentPage = 1
    ): Paginator;
}
