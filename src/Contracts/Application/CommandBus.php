<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Contracts\Application;

interface CommandBus
{
    /**
     * Dispatch a command to the appropriate handler.
     *
     * @template T of mixed
     * @param Command<T> $command
     * @return T
     */
    public function dispatch(Command $command): mixed;
}
