<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Support\Attributes\ForType;

use Attribute;
use GeekCell\Ddd\Contracts\Application\Query as QueryInterface;
use GeekCell\Ddd\Support\Attributes\ForType;

#[Attribute(Attribute::TARGET_CLASS)]
class Query extends ForType
{
    protected function supports(): string
    {
        return QueryInterface::class;
    }
}
