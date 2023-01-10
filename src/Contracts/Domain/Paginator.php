<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Contracts\Domain;

use Countable;
use IteratorAggregate;

interface Paginator extends Countable, IteratorAggregate
{
    /**
     * Returns the current page.
     *
     * @return int
     */
    public function getCurrentPage(): int;

    /**
     * Returns the total number of pages.
     *
     * @return int
     */
    public function getTotalPages(): int;

    /**
     * Returns the number of items per page.
     *
     * @return int
     */
    public function getItemsPerPage(): int;

    /**
     * Returns the total number of items.
     *
     * @return int
     */
    public function getTotalItems(): int;
}
