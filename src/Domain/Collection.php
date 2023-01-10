<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Domain;

use ArrayIterator;
use Assert;
use Countable;
use IteratorAggregate;
use Traversable;

abstract class Collection implements Countable, IteratorAggregate
{
    /**
     * @template T of object
     * @extends IteratorAggregate<T>
     *
     * @param T[] $items
     */
    final public function __construct(
        private readonly array $items = [],
    ) {
        Assert\Assertion::allIsInstanceOf($items, $this->itemType());
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

    /**
     * @inheritDoc
     */

    /**
     * Return the type of the items in the collection.
     * This is a poor man's workaround for missing generics in PHP.
     *
     * @return string
     */
    abstract protected function itemType(): string;
}
