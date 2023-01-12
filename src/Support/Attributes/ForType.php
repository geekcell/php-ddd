<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Support\Attributes;

abstract class ForType
{
    public function __construct(
        private string $type,
        private string $handler = 'execute',
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getHandler(): string
    {
        return $this->handler;
    }

    public function isValid(): bool
    {
        return is_subclass_of($this->getType(), $this->supports());
    }

    abstract protected function supports(): string;
}
