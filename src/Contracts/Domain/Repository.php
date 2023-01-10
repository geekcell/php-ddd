<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Contracts\Domain;

use Countable;
use IteratorAggregate;

interface Repository extends Countable, IteratorAggregate
{
    /**
     * Returns a new instance of the repository without pagination.
     *
     * @return static
     */
    public function collect(): static;

    /**
     * Returns a new instance of the repository with pagination.
     *
     * @param int $itemsPerPage
     * @param int $currentPage
     *
     * @return static
     */
    public function paginate(int $itemsPerPage, int $currentPage = 1): static;
}
