<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Domain;

use ArrayIterator;
use Assert;
use Countable;
use IteratorAggregate;
use Traversable;

class Collection implements Countable, IteratorAggregate
{
    /**
     * @template T of object
     * @extends IteratorAggregate<T>
     *
     * @param T[] $items
     * @param class-string<T> $itemType
     */
    final public function __construct(
        private readonly array $items = [],
        ?string $itemType = null,
    ) {
        if ($itemType !== null) {
            Assert\Assertion::allIsInstanceOf($items, $itemType);
        }
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->items);
    }
}
