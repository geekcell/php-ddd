<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Infrastructure\InMemory;

use GeekCell\Ddd\Contracts\Application\Query;
use GeekCell\Ddd\Contracts\Application\QueryBus as QueryBusInterface;
use GeekCell\Ddd\Contracts\Application\QueryHandler;
use GeekCell\Ddd\Support\Attributes\For\Query as ForQuery;

final class QueryBus extends AbstractBus implements QueryBusInterface
{
    /**
     * @inheritDoc
     */
    public function dispatch(Query $query): mixed
    {
        $queryType = get_class($query);
        if (!array_key_exists($queryType, $this->handlers)) {
            return null;
        }

        $handler = $this->handlers[$queryType];
        if (is_callable($handler)) {
            return $handler($query);
        }

        if (is_array($handler)) {
            [$handler, $method] = $handler;
            return call_user_func([$handler, $method], $query);
        }
    }

    /**
     * @inheritDoc
     */
    public function registerHandler(mixed $handler): void
    {
        if (is_callable($handler)) {
            $this->registerCallableHandler($handler, Query::class);
            return;
        }

        if ($handler instanceof QueryHandler) {
            $this->registerHandlerClass($handler, ForQuery::class);
        }
    }
}
