<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Domain;

use GeekCell\Ddd\Contracts\Domain\ValueObject as ValueObjectInterface;

abstract class ValueObject implements ValueObjectInterface
{
    /**
     * ValueObject constructor.
     *
     * @param mixed $args
     *
     * @throws \Throwable
     */
    public function __construct(...$args)
    {
        $this->validate(...$args);
    }

    /**
     * Validate the given arguments.
     *
     * @param mixed $args
     * @return void
     *
     * @throws \Throwable
     */
    protected function validate(...$args): void
    {
        if (!$this->doValidate(...$args)) {
            $this->raiseValidationException(...$args);
        }
    }

    /**
     * Raise an exception if the provided arguments to the constructor
     * are invalid. This method can/should be overridden in the child class
     * to raise a custom exception.
     *
     * @param mixed $args
     * @return void
     *
     * @throws \Throwable
     */
    protected function raiseValidationException(...$args): void
    {
        throw new \InvalidArgumentException('Invalid argument(s) provided.');
    }

    /**
     * Provides the actual validation logic.
     *
     * @param mixed $args
     * @return bool
     */
    abstract protected function doValidate(...$args): bool;
}
