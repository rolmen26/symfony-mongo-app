<?php

namespace App\Common\Model;

use App\Common\Model\Interfaces\DomainEvent;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use DateTimeImmutable;

abstract class BaseEvent implements DomainEvent
{
    const DEFAULT_APP_ID = 'symfony.core';

    const NAME = 'base.event';
    /** @var UuidInterface $Uuid */
    protected UuidInterface $Uuid;
    /** @var string $eventName */
    protected string $eventName;
    /** @var array $payload */
    protected array $payload;
    /** @var array $headers */
    protected array $headers;
    /** @var string $queue */
    protected string $queue;
    /** @var string $firedAt */
    protected string $firedAt;
    /** @var string $dispatchedAt */
    protected string $dispatchedAt;

    public function __construct()
    {
        $this->Uuid = Uuid::uuid4();
        $this->firedAt = microtime(true);
    }

    public function getUuid(): string
    {
        return $this->Uuid->toString();
    }

    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function getQueue(): string
    {
        return $this->queue;
    }

    public function getHeaders(): array
    {
        return [
            'app_id' => self::DEFAULT_APP_ID,
            'message_id' => $this->getUuid(),
            'type' => $this->getEventName(),
            'timestamp' => $this->firedAt
        ];
    }

    public function getPayload(): array
    {
        return $this->payload ?: [];
    }

    public function getFiredAt(): string
    {
        return $this->firedAt;
    }

    public function getDispatchedAt(): string
    {
        return $this->dispatchedAt;
    }

}
