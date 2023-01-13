<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Domain\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;
use GeekCell\Ddd\Contracts\Domain\ValueObject as ValueObjectInterface;
use GeekCell\Ddd\Domain\ValueObject;
use Ramsey\Uuid\Rfc4122\UuidV4;

class Uuid extends ValueObject
{
    /**
     * @var string
     */
    private string $uuid;

    /**
     * Uuid constructor.
     *
     * @param string $uuid
     */
    public function __construct(string $uuid)
    {
        parent::__construct($uuid);

        $this->uuid = $uuid;
    }

    /**
     * Creates an instance with a random UUID.
     *
     * @return static
     */
    public static function random(): static
    {
        return new static(UuidV4::uuid4()->toString());
    }

    /**
     * @inheritDoc
     */
    public function equals(ValueObjectInterface $object): bool
    {
        if (!($object instanceof static)) {
            return false;
        }

        return (UuidV4::fromString($this->uuid))
            ->equals(UuidV4::fromString($object->getValue()));
    }

    /**
     * @inheritDoc
     */
    public function getValue(): mixed
    {
        return $this->uuid;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->getValue();
    }

    /**
     * @inheritDoc
     */
    protected function doValidate(...$args): bool
    {
        try {
            return Assertion::uuid($args[0]);
        } catch (AssertionFailedException $e) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    protected function raiseValidationException(...$args): void
    {
        throw new \InvalidArgumentException(sprintf(
            "Argument '%s' cannot be converted to a valid UUID.",
            $args[0],
        ));
    }
}
