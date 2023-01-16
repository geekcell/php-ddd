<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Tests\Domain;

use Assert;
use GeekCell\Ddd\Domain\Collection;
use PHPUnit\Framework\TestCase;

/**
 * Test fixture for Collection.
 *
 * @package GeekCell\Ddd\Tests\Domain
 */
class Foo
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
}
