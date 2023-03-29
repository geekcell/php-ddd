<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Contracts\Domain;

use Stringable;

interface ValueObject extends Stringable
{
    /**
     * Check if the given object is equal to the current object.
     *
     * @param ValueObject $object
     * @return bool
     */
    public function equals(ValueObject $object): bool;

    /**
     * Return the value of the value object.
     *
     * @return mixed
     */
    public function getValue(): mixed;
}
