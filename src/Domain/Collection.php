<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Domain;

use ArrayAccess;
use ArrayIterator;
use Assert;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;

use function array_filter;
use function array_map;
use function array_reduce;
use function array_values;
use function count;
use function get_class;
use function is_int;
use function reset;

/**
 * @template T of object
 * @implements IteratorAggregate<T>
 * @implements ArrayAccess<array-key, T>
 */
class Collection implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * @param array<array-key, T> $items
     * @param class-string<T>|null $itemType
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
     * Creates a collection from a given iterable of items.
     * This function is useful when trying to create a collection from a generator or an iterator.
     *
     * @param iterable<T> $items
     * @param class-string<T>|null $itemType
     * @return self<T>
     * @throws Assert\AssertionFailedException
     */
    public static function fromIterable(iterable $items, ?string $itemType = null): static
    {
        if (is_array($items)) {
            return new static($items, $itemType);
        }

        if (!$items instanceof Traversable) {
            $items = [...$items];
        }

        return new static(iterator_to_array($items), $itemType);
    }

    /**
     * Returns the collection as an array.
     * The returned array is either a key-value array with values of type T or a list of T
     *
     * @return array<array-key, T>|list<T>
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * Returns the collection as a list of T
     *
     * @return list<T>
     */
    public function toList(): array
    {
        return array_values($this->items);
    }

    /**
     * Returns true if every value in the collection passes the callback truthy test. Opposite of self::none().
     * Callback arguments will be element, index, collection.
     * Function short-circuits on first falsy return value.
     *
     * @param ?callable(T, int, static): bool $callback
     * @return bool
     */
    public function every(callable $callback = null): bool
    {
        if ($callback === null) {
            $callback = static fn ($item, $index, $self) => $item;
        }

        foreach ($this->items as $index => $item) {
            if (!$callback($item, $index, $this)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns true if every value in the collection passes the callback falsy test. Opposite of self::every().
     * Callback arguments will be element, index, collection.
     * Function short-circuits on first truthy return value.
     *
     * @param ?callable(T, int, static): bool $callback
     * @return bool
     */
    public function none(callable $callback = null): bool
    {
        if ($callback === null) {
            $callback = static fn ($item, $index, $self) => $item;
        }

        foreach ($this->items as $index => $item) {
            if ($callback($item, $index, $this)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns true if at least one value in the collection passes the callback truthy test.
     * Callback arguments will be element, index, collection.
     * Function short-circuits on first truthy return value.
     *
     * @param ?callable(T, int, static): bool $callback
     * @return bool
     */
    public function some(callable $callback = null): bool
    {
        if ($callback === null) {
            $callback = static fn ($item, $index, $self) => $item;
        }

        foreach ($this->items as $index => $item) {
            if ($callback($item, $index, $this)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the first element of the collection that matches the given callback or null if the collection is empty
     * or the callback never returned true for any item
     *
     * @param callable(T, int, static): bool $callback
     * @return ?T
     */
    public function find(callable $callback)
    {
        foreach ($this->items as $index => $item) {
            if ($callback($item, $index, $this)) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Returns the last element of the collection that matches the given callback or null if the collection is empty
     * or the callback never returned true for any item
     *
     * @param callable(T, int, static): bool $callback
     * @return ?T
     */
    public function findLast(callable $callback)
    {
        foreach (array_reverse($this->items) as $index => $item) {
            if ($callback($item, $index, $this)) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Returns the first element of the collection that matches the given callback.
     * If no callback is given the first element in the collection is returned.
     * Throws exception if collection is empty or the given callback was never satisfied.
     *
     * @param ?callable(T, int, static): bool $callback
     * @return T
     * @throws InvalidArgumentException
     */
    public function first(callable $callback = null)
    {
        if ($this->items === []) {
            throw new InvalidArgumentException('No items in collection');
        }

        foreach ($this->items as $index => $item) {
            if ($callback === null || $callback($item, $index, $this)) {
                return $item;
            }
        }

        throw new InvalidArgumentException('No item found in collection that satisfies first callback');
    }

    /**
     * Returns the first element of the collection that matches the given callback.
     * If no callback is given the first element in the collection is returned.
     * If the collection is empty the given fallback value is returned instead.
     *
     * @template U of T|mixed
     * @param ?callable(T, int, static): bool $callback
     * @param U $fallbackValue
     * @return U|T
     * @throws InvalidArgumentException
     */
    public function firstOr(callable $callback = null, mixed $fallbackValue = null)
    {
        if ($this->items === []) {
            return $fallbackValue;
        }

        foreach ($this->items as $index => $item) {
            if ($callback === null || $callback($item, $index, $this)) {
                return $item;
            }
        }

        return $fallbackValue;
    }

    /**
     * Returns the last element of the collection that matches the given callback.
     * If no callback is given the last element in the collection is returned.
     * Throws exception if collection is empty or the given callback was never satisfied.
     *
     * @param ?callable(T, int, static): bool $callback
     * @return T
     * @throws InvalidArgumentException
     */
    public function last(callable $callback = null)
    {
        if ($this->items === []) {
            throw new InvalidArgumentException('No items in collection');
        }

        foreach (array_reverse($this->items) as $index => $item) {
            if ($callback === null || $callback($item, $index, $this)) {
                return $item;
            }
        }

        throw new InvalidArgumentException('No item found in collection that satisfies last callback');
    }

    /**
     * Returns the last element of the collection that matches the given callback.
     * If no callback is given the last element in the collection is returned.
     * If the collection is empty the given fallback value is returned instead.
     *
     * @template U of T|mixed
     * @param ?callable(T, int, static): bool $callback
     * @param U $fallbackValue
     * @return U|T
     * @throws InvalidArgumentException
     */
    public function lastOr(callable $callback = null, mixed $fallbackValue = null)
    {
        if ($this->items === []) {
            return $fallbackValue;
        }

        foreach (array_reverse($this->items) as $index => $item) {
            if ($callback === null || $callback($item, $index, $this)) {
                return $item;
            }
        }

        return $fallbackValue;
    }

    /**
     * Returns whether the collection is empty (has no items)
     */
    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    /**
     * Returns whether the collection has items
     */
    public function hasItems(): bool
    {
        return $this->items !== [];
    }

    /**
     * Add one or more items to the collection. It **does not** modify the
     * current collection, but returns a new one.
     *
     * @param T|iterable<T> $item One or more items to add to the collection.
     * @return static
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
            array_values(array_filter($this->items, $callback)),
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
        $mapResult = array_map($callback, $this->items);
        $firstItem = reset($mapResult);

        if ($firstItem === false || !is_object($firstItem)) {
            return new static($mapResult);
        }

        if ($inferTypes && $this->itemType !== null) {
            return new static($mapResult, get_class($firstItem));
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
        return array_reduce($this->items, $callback, $initial);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        if (!is_int($offset)) {
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
        return count($this->items);
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
