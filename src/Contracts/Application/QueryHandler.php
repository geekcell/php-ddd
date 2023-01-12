<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Contracts\Application;

use GeekCell\Ddd\Contracts\Core\Interactable;

interface QueryHandler extends Interactable
{
    /**
     * Execute a query.
     *
     * @param Query $query
     * @return mixed
     */
    public function execute(Query $query): mixed;
}
