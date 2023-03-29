<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Domain;

use Assert;

class Collection implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * @template T of object
     * @extends \IteratorAggregate<T>
     *
     * @param T[] $items
     * @param class-string<T> $itemType
     *
     * @throws Assert\AssertionFailedException
     */
    final public function __construct(
        private readonly array $items = [],
        private ?string $itemType = null,
    ) {
        if ($itemType !== null) {
            Assert\Assertion::allIsInstanceOf($items, $itemType);
        }
    }

    /**
     * Add one or more items to the collection. It **does not** modify the
     * current collection, but returns a new one.
     *
     * @param mixed $item  One or more items to add to the collection.
     * @return static
     *
     * @throws Assert\AssertionFailedException
     */
    public function add(mixed $item): static
    {
        if (!is_array($item)) {
            $item = [$item];
        }

        if ($this->itemType !== null) {
            Assert\Assertion::allIsInstanceOf($item, $this->itemType);
        }

        return new static([...$this->items, ...$item], $this->itemType);
    }

    /**
     * Filter the collection using the given callback. It **does not** modify
     * the current collection, but returns a new one.
     *
     * @param callable $callback  The callback to use for filtering.
     * @return static
     */
    public function filter(callable $callback): static
    {
        return new static(
            \array_values(\array_filter($this->items, $callback)),
            $this->itemType,
        );
    }

    /**
     * Map the collection using the given callback. It **does not** modify
     * the current collection, but returns a new one.
     *
     * @param callable $callback  The callback to use for mapping.
     * @param bool $inferTypes    Whether to infer the type of the items in the
     *                            collection based on the first item in the
     *                            mapping result. Defaults to `true`.
     *
     * @return static
     */
    public function map(callable $callback, bool $inferTypes = true): static
    {
        $mapResult = \array_map($callback, $this->items);
        $firstItem = \reset($mapResult);

        if ($firstItem === false || !is_object($firstItem)) {
            return new static($mapResult);
        }

        if ($inferTypes && $this->itemType !== null) {
            return new static($mapResult, \get_class($firstItem));
        }

        return new static($mapResult);
    }

    /**
     * Reduce the collection using the given callback.
     *
     * @param callable $callback  The callback to use for reducing.
     * @param mixed $initial      The initial value to use for reducing.
     *
     * @return mixed
     */
    public function reduce(callable $callback, mixed $initial = null): mixed
    {
        return \array_reduce($this->items, $callback, $initial);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        if (!\is_int($offset)) {
            return false;
        }

        return isset($this->items[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset): mixed
    {
        if (!$this->offsetExists($offset)) {
            return null;
        }

        return $this->items[$offset];
    }

    /**
     * This method is not supported since it would break the immutability of the
     * collection.
     *
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        // Unsupported since it would break the immutability of the collection.
    }

    /**
     * This method is not supported since it would break the immutability of the
     * collection.
     *
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {
        // Unsupported since it would break the immutability of the collection.
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return \count($this->items);
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }
}
