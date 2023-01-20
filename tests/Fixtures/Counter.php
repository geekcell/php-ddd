<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Tests\Fixtures;

class Counter
{
    public function __construct(private int $value)
    {
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
