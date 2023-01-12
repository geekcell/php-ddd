<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Contracts\Application;

interface QueryBus
{
    /**
     * Dispatch a query to the appropriate handler.
     *
     * @param Query $query
     * @return mixed
     */
    public function dispatch(Query $query): mixed;
}
