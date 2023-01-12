<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Contracts\Application;

interface CommandBus
{
    /**
     * Dispatch a command to the appropriate handler.
     *
     * @param Command $command
     * @return mixed
     */
    public function dispatch(Command $command): mixed;
}
