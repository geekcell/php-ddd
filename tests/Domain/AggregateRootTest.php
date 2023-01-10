<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Tests\Domain;

use GeekCell\Ddd\Contracts\Domain\Event as DomainEvent;
use GeekCell\Ddd\Domain\AggregateRoot;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Test subject.
 *
 * @package GeekCell\Ddd\Tests\Domain
 */
class TestAggregateRoot extends AggregateRoot
{
    public function getRecordedDomainEvents(): array
    {
        return $this->recordedDomainEvents;
    }
}

class AggregateRootTest extends TestCase
{
    public function testRecord(): void
    {
        // Given
        $event = Mockery::mock(DomainEvent::class);

        // When
        $aggregateRoot = new TestAggregateRoot();
        $aggregateRoot->record($event);
        $aggregateRoot->record($event);

        // Then
        $this->assertCount(2, $aggregateRoot->getRecordedDomainEvents());
    }

    public function testReleaseEvents(): void
    {
        // Given
        $event = Mockery::mock(DomainEvent::class);

        // When
        $aggregateRoot = new TestAggregateRoot();
        $aggregateRoot->record($event);
        $aggregateRoot->record($event);
        $events = $aggregateRoot->releaseEvents();

        // Then
        $this->assertCount(2, $events);
        $this->assertCount(0, $aggregateRoot->getRecordedDomainEvents());
    }
}
