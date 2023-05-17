<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Contracts\Application;

interface QueryBus
{
    /**
     * Dispatch a query to the appropriate handler.
     *
     * @template T of mixed
     * @param Query<T> $query
     * @return T
     */
    public function dispatch(Query $query): mixed;
}
