<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Tests\Domain;

use ArrayIterator;
use Assert;
use GeekCell\Ddd\Domain\Collection;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Test fixture for Collection.
 *
 * @package GeekCell\Ddd\Tests\Domain
 */
class Foo
{
}

/**
 * Test fixture for Collection.
 *
 * @package GeekCell\Ddd\Tests\Domain
 */
class Bar
{
}

class CollectionTest extends TestCase
{
    public function testTypedConstructor(): void
    {
        // Given
        $items = [new Foo(), new Foo(), new Foo()];

        // When
        $collection = new Collection($items, Foo::class);

        $this->assertInstanceOf(Collection::class, $collection);
    }

    public function testTypedConstructorWithInvalidType(): void
    {
        // Given
        $items = [new Foo(), new Foo(), new \stdClass()];

        // Then
        $this->expectException(Assert\InvalidArgumentException::class);

        // When
        $collection = new Collection($items, Foo::class);
    }

    public function testUntypedConstructor(): void
    {
        // Given
        $items = [new Foo(), new \stdClass(), 42, 'foo'];

        // When
        $collection = new Collection($items);

        $this->assertInstanceOf(Collection::class, $collection);
    }

    public function testArrayAccess(): void
    {
        // Given
        $items = [new Foo(), new Foo(), new Foo()];

        // When
        $collection = new Collection($items, Foo::class);

        // Then
        $this->assertEquals($collection[0], $items[0]);
        $this->assertEquals($collection[1], $items[1]);
        $this->assertEquals($collection[2], $items[2]);
        $this->assertFalse(isset($collection[3]));
    }

    public function testArrayAccessWithInvalidOffset(): void
    {
        // Given
        $items = [new Foo(), new Foo(), new Foo()];
        $collection = new Collection($items, Foo::class);

        // When
        $result1 = $collection[10];
        $result2 = $collection[-1];
        $result3 = $collection[0.5];
        $result4 = $collection['foo'];

        // When
        $this->assertNull($result1);
        $this->assertNull($result2);
        $this->assertNull($result3);
        $this->assertNull($result4);
    }

    public function testCount(): void
    {
        // Given
        $items = [new Foo(), new Foo(), new Foo()];

        // When
        $collection = new Collection($items, Foo::class);

        // Then
        $this->assertCount(3, $collection);
    }

    public function testIterater(): void
    {
        // Given
        $items = [new Foo(), new Foo(), new Foo()];

        // When
        $collection = new Collection($items, Foo::class);

        // Then
        $this->assertEquals($items, iterator_to_array($collection));
        $this->assertCount(3, iterator_to_array($collection));
    }

    public function testAdd(): void
    {
        // Given
        $items = [new Foo(), new Foo(), new Foo()];
        $collection = new Collection($items, Foo::class);

        // When
        $newCollection = $collection->add(new Foo());

        // Then
        $this->assertCount(4, $newCollection);
        $this->assertCount(3, $collection);
        $this->assertNotSame($collection, $newCollection);
    }

    public function testAddMultiple(): void
    {
        // Given
        $items = [new Foo(), new Foo(), new Foo()];
        $collection = new Collection($items, Foo::class);

        // When
        $newCollection = $collection->add([new Foo(), new Foo()]);

        // Then
        $this->assertCount(5, $newCollection);
        $this->assertCount(3, $collection);
        $this->assertNotSame($collection, $newCollection);
    }

    public function testFilter(): void
    {
        // Given
        $items = [1, 2, 3,4, 5, 6, 7, 8, 9, 10];
        $collection = new Collection($items);

        // When
        $newCollection = $collection->filter(fn (int $i) => $i % 2 === 0);

        // Then
        $this->assertCount(5, $newCollection);
        $this->assertCount(10, $collection);
        $this->assertNotSame($collection, $newCollection);
    }

    public function testFilterWithAdjustedIndices(): void
    {
        // Given
        $items = [1, 2, 3, 4];
        $collection = new Collection($items);

        // When
        $newCollection = $collection->filter(fn (int $i) => $i % 2 === 0);

        // Then
        $this->assertCount(2, $newCollection);
        $this->assertEquals(2, $newCollection[0]);
        $this->assertEquals(4, $newCollection[1]);
    }

    public function testMap(): void
    {
        // Given
        $items = [new Foo(), new Foo(), new Foo()];
        $collection = new Collection($items, Foo::class);

        // When
        $newCollection = $collection->map(fn (Foo $item) => new Bar());

        // Then
        $this->assertCount(3, $newCollection);
        $this->assertCount(3, $collection);
        $this->assertNotSame($collection, $newCollection);
    }

    public function testMapWithStrictTypes(): void
    {
        // Given
        $items = [new Foo(), new Foo(), new Foo()];
        $collection = new Collection($items, Foo::class);

        // Then
        $this->expectException(Assert\InvalidArgumentException::class);

        // When
        $counter = 0;
        $newCollection = $collection->map(
            function (Foo $item) use (&$counter) {
                if ($counter++ % 2) {
                    return new Bar();
                }

                return $item;
            },
            true,
        );
    }

    public function testMapWithoutStrictTypes(): void
    {
        // Given
        $items = [new Foo(), new Foo(), new Foo()];
        $collection = new Collection($items, Foo::class);

        // When
        $counter = 0;
        $newCollection = $collection->map(
            function ($item) use (&$counter) {
                if ($counter++ % 2) {
                    return new Bar();
                }

                return $item;
            },
            false,
        );

        // Then
        $this->assertCount(3, $newCollection);
        $this->assertCount(3, $collection);
        $this->assertNotSame($collection, $newCollection);
    }

    public function testMapWithStrictTypesAndScalars(): void
    {
        // Given
        $items = [new Foo(), new Foo(), new Foo()];
        $collection = new Collection($items, Foo::class);

        // When
        $counter = 0;
        $newCollection = $collection->map(
            function (Foo $item) use (&$counter) {
                if ($counter % 2) {
                    return $counter++;
                }

                return 'foo';
            },
            true,
        );

        // Then
        $this->assertCount(3, $newCollection);
        $this->assertCount(3, $collection);
        $this->assertNotSame($collection, $newCollection);
    }

    public function testReduce(): void
    {
        // Given
        $items = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $collection = new Collection($items);

        // When
        $result = $collection->reduce(
            fn (int $carry, int $item) => $carry + $item,
            0,
        );

        // Then
        $this->assertEquals(55, $result);
    }

    public function testChaining(): void
    {
        // Given
        $items = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $collection = new Collection($items);

        // When
        $result = $collection
            ->filter(fn (int $i) => $i % 2 === 0)
            ->map(fn (int $i) => $i * 2)
            ->reduce(fn (int $carry, int $item) => $carry + $item, 0);

        // Then
        $this->assertEquals(60, $result);
    }

    public function testFromIterable(): void
    {
        $items = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $collectionFromArray = Collection::fromIterable($items);
        $this->assertSame($items, iterator_to_array($collectionFromArray));

        $collectionFromIterator = Collection::fromIterable(new ArrayIterator($items));
        $this->assertSame($items, iterator_to_array($collectionFromIterator));

        $generatorFn = static function () use ($items) {
            foreach ($items as $item) {
                yield $item;
            }
        };

        $collectionFromGenerator = Collection::fromIterable($generatorFn());
        $this->assertSame($items, iterator_to_array($collectionFromGenerator));
    }

    public function testEvery(): void
    {
        $items = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $collection = new Collection($items);

        $this->assertFalse($collection->every(static fn ($item) => $item > 10));
        $this->assertFalse($collection->every(static fn ($item) => $item > 5));
        $this->assertTrue($collection->every(static fn ($item) => $item > 0));
    }

    public function testEveryWithoutArgumentDefaultsToTruthyCheck(): void
    {
        $this->assertTrue((new Collection([1, true]))->every());
        $this->assertTrue((new Collection([1, true]))->every());
        $this->assertFalse((new Collection([null, false]))->every());
        $this->assertFalse((new Collection([false, null]))->every());
        $this->assertFalse((new Collection([0, false]))->every());
    }

    public function testEveryReturnsTrueOnEmptyCollection(): void
    {
        $this->assertTrue((new Collection())->every(static fn ($item) => false));
    }

    public function testEveryShortCircuitsOnFirstFalsyValue(): void
    {
        $items = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $collection = new Collection($items);

        $collection->every(function ($item, $index, $c) use ($collection): bool {
            // First item already returns false therefore the index should never be something other than 0
            $this->assertSame(0, $index);
            $this->assertSame($c, $collection);
            return false;
        });
    }

    public function testNone(): void
    {
        $items = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $collection = new Collection($items);

        $this->assertTrue($collection->none(static fn ($item) => $item > 10));
        $this->assertFalse($collection->none(static fn ($item) => $item > 5));
        $this->assertFalse($collection->none(static fn ($item) => $item > 0));
    }

    public function testNoneWithoutArgumentDefaultsToTruthyCheck(): void
    {
        $this->assertFalse((new Collection([1, true]))->none());
        $this->assertFalse((new Collection([1, true]))->none());
        $this->assertTrue((new Collection([null, false]))->none());
        $this->assertTrue((new Collection([false, null]))->none());
        $this->assertTrue((new Collection([0, false]))->none());
    }

    public function testNoneReturnsFalseOnEmptyCollection(): void
    {
        $this->assertTrue((new Collection())->none(static fn ($item) => true));
    }

    public function testNoneShortCircuitsOnFirstFalsyValue(): void
    {
        $items = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $collection = new Collection($items);

        $collection->none(function ($item, $index, $c) use ($collection): bool {
            // First item already returns true therefore the index should never be something other than 0
            $this->assertSame(0, $index);
            $this->assertSame($c, $collection);
            return true;
        });
    }

    public function testSome(): void
    {
        $items = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $collection = new Collection($items);

        $this->assertFalse($collection->some(static fn ($item) => $item > 10));
        $this->assertTrue($collection->some(static fn ($item) => $item > 5));
        $this->assertTrue($collection->some(static fn ($item) => $item > 0));
    }

    public function testSomeWithoutArgumentDefaultsToTruthyCheck(): void
    {
        $this->assertTrue((new Collection([1, true]))->some());
        $this->assertTrue((new Collection([1, true]))->some());
        $this->assertFalse((new Collection([null, false]))->some());
        $this->assertFalse((new Collection([false, null]))->some());
        $this->assertFalse((new Collection([0, false]))->some());
    }

    public function testSomeReturnsFalseOnEmptyCollection(): void
    {
        $this->assertFalse((new Collection())->some(static fn ($item) => true));
    }

    public function testSomeShortCircuitsOnFirstFalsyValue(): void
    {
        $items = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $collection = new Collection($items);

        $collection->some(function ($item, $index, $c) use ($collection): bool {
            // First item already returns true therefore the index should never be something other than 0
            $this->assertSame(0, $index);
            $this->assertSame($c, $collection);
            return true;
        });
    }

    public function testFirst(): void
    {
        $items = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $collection = new Collection($items);

        $this->assertSame(1, $collection->first());
    }

    public function testFirstThrowsExceptionOnEmptyCollection(): void
    {
        $collection = new Collection([]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No items in collection');
        $collection->first();
    }

    public function testFirstThrowsExceptionIfCallbackIsNeverSatisfied(): void
    {
        $items = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $collection = new Collection($items);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No item found in collection that satisfies first callback');
        $collection->first(static fn () => false);
    }

    public function testFirstOrReturnsFirstValueInCollectionIfNoCallbackIsGiven(): void
    {
        $items = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $collection = new Collection($items);
        $this->assertSame(1, $collection->firstOr());
    }

    public function testFirstOrReturnsFirstValueThatSatisfiesCallback(): void
    {
        $items = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $collection = new Collection($items);
        $this->assertSame(6, $collection->firstOr(static fn ($item) => $item > 5));
    }

    public function testFirstOrReturnsFallbackValueIfCallbackIsNeverSatisfied(): void
    {
        $items = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $collection = new Collection($items);
        $this->assertSame(-1, $collection->firstOr(static fn ($item) => $item > 10, -1));
    }

    public function testLast(): void
    {
        $items = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $collection = new Collection($items);

        $this->assertSame(10, $collection->last());
    }

    public function testLastThrowsExceptionOnEmptyCollection(): void
    {
        $collection = new Collection([]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No items in collection');
        $collection->last();
    }

    public function testLastThrowsExceptionIfCallbackIsNeverSatisfied(): void
    {
        $items = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $collection = new Collection($items);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No item found in collection that satisfies last callback');
        $collection->last(static fn () => false);
    }

    public function testLastOrReturnsLastValueInCollectionIfNoCallbackIsGiven(): void
    {
        $items = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $collection = new Collection($items);
        $this->assertSame(10, $collection->lastOr());
    }

    public function testLastOrReturnsLastValueThatSatisfiesCallback(): void
    {
        $items = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $collection = new Collection($items);
        $this->assertSame(10, $collection->lastOr(static fn ($item) => $item > 5));
    }

    public function testLastOrReturnsFallbackValueIfCallbackIsNeverSatisfied(): void
    {
        $items = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $collection = new Collection($items);
        $this->assertSame(-1, $collection->lastOr(static fn ($item) => $item > 10, -1));
    }
}
