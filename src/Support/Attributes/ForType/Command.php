<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Support\Attributes\ForType;

use Attribute;
use GeekCell\Ddd\Contracts\Application\Command as CommandInterface;
use GeekCell\Ddd\Support\Attributes\ForType;

#[Attribute(Attribute::TARGET_CLASS)]
class Command extends ForType
{
    protected function supports(): string
    {
        return CommandInterface::class;
    }
}
