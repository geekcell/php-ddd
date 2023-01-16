<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Domain\ValueObject;

use GeekCell\Ddd\Contracts\Domain\ValueObject as ValueObjectInterface;
use GeekCell\Ddd\Domain\ValueObject;

abstract class Id extends ValueObject
{
    /**
     * @var int
     */
    private int $id;

    /**
     * Id constructor.
     *
     * @param int $id
     */
    final public function __construct(int $id)
    {
        parent::__construct($id);

        $this->id = $id;
    }

    /**
     * @inheritDoc
     */
    public function getValue(): mixed
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    protected function doValidate(...$args): bool
    {
        return is_int($args[0]) && $args[0] > 0;
    }

    /**
     * @inheritDoc
     */
    public function equals(ValueObjectInterface $object): bool
    {
        if (!($object instanceof static)) {
            return false;
        }

        return $this->id === $object->getValue();
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return strval($this->id);
    }

    protected function raiseValidationException(...$args): void
    {
        throw new \InvalidArgumentException(
            sprintf(
                "Argument '%s' cannot be used to create numeric Id.",
                $args[0],
            )
        );
    }
}
