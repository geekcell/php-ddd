<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Infrastructure\InMemory;

use GeekCell\Ddd\Contracts\Application\Command;
use GeekCell\Ddd\Contracts\Application\CommandBus as CommandBusInterface;
use GeekCell\Ddd\Contracts\Application\CommandHandler;
use GeekCell\Ddd\Support\Attributes\For\Command as ForCommand;

class CommandBus extends AbstractBus implements CommandBusInterface
{
    /**
     * @inheritDoc
     */
    public function dispatch(Command $command): mixed
    {
        $commandType = get_class($command);
        if (!array_key_exists($commandType, $this->handlers)) {
            return null;
        }

        $handler = $this->handlers[$commandType];
        if (is_callable($handler)) {
            return $handler($command);
        }

        if (is_array($handler)) {
            [$handler, $method] = $handler;
            return call_user_func([$handler, $method], $command);
        }
    }

    /**
     * @inheritDoc
     */
    public function registerHandler(mixed $handler): void
    {
        if (is_callable($handler)) {
            $this->registerCallableHandler($handler, Command::class);
            return;
        }

        if ($handler instanceof CommandHandler) {
            $this->registerHandlerClass($handler, ForCommand::class);
        }
    }
}
