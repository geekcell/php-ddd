<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Tests\Domain\ValueObject;

use GeekCell\Ddd\Domain\ValueObject\Id;
use PHPUnit\Framework\TestCase;

/**
 * Test class.
 */
class FooId extends Id
{
}

/**
 * Test class.
 */
class BarId extends Id
{
}

class IdTest extends TestCase
{
    public function testCreate(): void
    {
        // Given
        $numericId = 42;

        // When
        $id = new FooId($numericId);

        // Then
        $this->assertInstanceOf(FooId::class, $id);
        $this->assertEquals($numericId, $id->getValue());
    }

    public function testCreateWithInvalidId(): void
    {
        // Given
        $numericId = -1;

        // Then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            "Argument '-1' cannot be used to create numeric Id."
        );

        // When
        new FooId($numericId);
    }

    /**
     * @dataProvider provideEqualsData
     *
     * @param Id $first
     * @param Id $second
     * @param bool $result
     */
    public function testEquals(Id $first, Id $second, bool $expected): void
    {
        // Given - When
        $result = $first->equals($second);

        // Then
        $this->assertEquals($result, $expected);
    }

    public function testToString(): void
    {
        // Given
        $numericId = 42;

        // When
        $id = new FooId($numericId);

        // Then
        $this->assertEquals(strval($numericId), $id->__toString());
    }

    /**
     * @return array<array<mixed>>
     */
    public function provideEqualsData(): array
    {
        return [
            [new FooId(1), new FooId(2), false],
            [new FooId(1), new FooId(1), true],
            [new FooId(1), new BarId(1), false],
        ];
    }
}
