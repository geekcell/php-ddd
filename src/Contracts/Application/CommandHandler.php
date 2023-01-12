<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Contracts\Application;

use GeekCell\Ddd\Contracts\Core\Interactable;

interface CommandHandler extends Interactable
{
    /**
     * Execute a command.
     *
     * @param Command $command
     * @return mixed
     */
    public function execute(Command $command): mixed;
}
