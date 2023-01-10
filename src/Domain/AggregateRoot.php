<?php

declare(strict_types=1);

namespace GeekCell\Ddd\Domain;

use GeekCell\Ddd\Contracts\Domain\Event as DomainEvent;

class AggregateRoot
{
    /**
     * @var DomainEvent[]
     */
    protected array $recordedDomainEvents = [];

    /**
     * Record a domain event.
     *
     * @param DomainEvent $domainEvent
     * @return void
     */
    public function record(DomainEvent $domainEvent): void
    {
        $this->recordedDomainEvents[] = $domainEvent;
    }

    /**
     * Alias for record().
     *
     * @see record()
     * @codeCoverageIgnore
     *
     * @param DomainEvent $domainEvent
     * @return void
     */
    public function log(DomainEvent $domainEvent): void
    {
        $this->record($domainEvent);
    }

    /**
     * Release all recorded domain events. It will clear the recorded
     * events and return them.
     *
     * @return DomainEvent[]
     */
    public function releaseEvents(): array
    {
        $events = $this->recordedDomainEvents;
        $this->recordedDomainEvents = [];

        return $events;
    }
}
