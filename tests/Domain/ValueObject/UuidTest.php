<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Tests\Domain\ValueObject;

use GeekCell\Ddd\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

/**
 * Test class.
 */
class FooUuid extends Uuid
{
}

/**
 * Test class.
 */
class BarUuid extends Uuid
{
}

class UuidTest extends TestCase
{
    public function testCreate(): void
    {
        // Given
        $uuid = 'a4a70900-47da-11eb-b378-0242ac130002';

        // When
        $id = new FooUuid($uuid);

        // Then
        $this->assertInstanceOf(FooUuid::class, $id);
        $this->assertEquals($uuid, $id->getValue());
    }

    public function testCreateWithInvalidUuid(): void
    {
        // Given
        $uuid = 'invalid-uuid';

        // Then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            "Argument 'invalid-uuid' cannot be converted to a valid UUID."
        );

        // When
        new FooUuid($uuid);
    }

    public function testRandom(): void
    {
        // Given - When
        $uuid = FooUuid::random();

        // Then
        $this->assertInstanceOf(FooUuid::class, $uuid);
    }

    /**
     * @dataProvider provideEqualsData
     *
     * @param Uuid $first
     * @param Uuid $second
     * @param bool $result
     */
    public function testEquals(Uuid $first, Uuid $second, bool $expected): void
    {
        // Given - When
        $result = $first->equals($second);

        // Then
        $this->assertEquals($expected, $result);
    }

    public function testToString(): void
    {
        // Given
        $uuid = 'a4a70900-47da-11eb-b378-0242ac130002';

        // When
        $id = new FooUuid($uuid);

        // Then
        $this->assertEquals($uuid, $id->__toString());
    }

    /**
     * @return array<array<mixed>>
     */
    public function provideEqualsData(): array
    {
        return [
            [
                new FooUuid('a4a70900-47da-11eb-b378-0242ac130002'),
                new FooUuid('a4a70900-47da-11eb-b378-0242ac130002'),
                true,
            ],
            [
                new FooUuid('a4a70900-47da-11eb-b378-0242ac130002'),
                new BarUuid('a4a70900-47da-11eb-b378-0242ac130002'),
                false,
            ],
            [
                new FooUuid('a4a70900-47da-11eb-b378-0242ac130002'),
                new FooUuid('a4a70900-47da-11eb-b378-0242ac130003'),
                false,
            ],
        ];
    }
}
