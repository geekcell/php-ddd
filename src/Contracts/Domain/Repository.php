<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Contracts\Domain;

use GeekCell\Ddd\Domain\Collection;

/**
 * @template T of object
 * @extends \IteratorAggregate<T>
 */
interface Repository extends \Countable, \IteratorAggregate
{
    /**
     * Returns a collection of items.
     *
     * @return Collection<T>
     */
    public function collect(): Collection;

    /**
     * Returns a paginator to paginate the items.
     *
     * @param int $itemsPerPage
     * @param int $currentPage
     *
     * @return Paginator<T>
     */
    public function paginate(
        int $itemsPerPage,
        int $currentPage = 1
    ): Paginator;
}
