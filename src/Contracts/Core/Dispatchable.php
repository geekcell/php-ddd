<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Contracts\Core;

use Psr\EventDispatcher\EventDispatcherInterface;

interface Dispatchable extends EventDispatcherInterface
{
}
