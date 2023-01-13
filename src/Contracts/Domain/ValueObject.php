<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Contracts\Domain;

interface ValueObject
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

    /**
     * Return the string representation of the value object.
     *
     * @return string
     */
    public function __toString(): string;
}
