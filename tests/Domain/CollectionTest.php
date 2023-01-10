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

/**
 * Test subject.
 *
 * @package GeekCell\Ddd\Tests\Domain
 */
class TestCollection extends Collection
{
    protected function itemType(): string
    {
        return Foo::class;
    }
}

class CollectionTest extends TestCase
{
    public function testConstructor(): void
    {
        // Given
        $items = [new Foo(), new Foo(), new Foo()];

        // When
        $collection = new TestCollection($items);

        $this->assertInstanceOf(TestCollection::class, $collection);
    }

    public function testConstructorWithInvalidType(): void
    {
        // Given
        $items = [new Foo(), new Foo(), new \stdClass()];

        // Then
        $this->expectException(Assert\InvalidArgumentException::class);

        // When
        $collection = new TestCollection($items);
    }

    public function testCount(): void
    {
        // Given
        $items = [new Foo(), new Foo(), new Foo()];

        // When
        $collection = new TestCollection($items);

        // Then
        $this->assertCount(3, $collection);
    }

    public function testIterater(): void
    {
        // Given
        $items = [new Foo(), new Foo(), new Foo()];

        // When
        $collection = new TestCollection($items);

        // Then
        $this->assertEquals($items, iterator_to_array($collection));
        $this->assertCount(3, iterator_to_array($collection));
    }
}
