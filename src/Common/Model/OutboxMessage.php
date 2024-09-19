<?php

namespace App\Common\Model;

use App\Common\Model\Types\OutboxMessageStatus;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Common\Infrastructure\Repository\OutboxMessageRepository;

#[MongoDB\Document(collection: "outbox_messages", repositoryClass: OutboxMessageRepository::class)]
#[MongoDB\UniqueIndex(keys: ['messageId' => 1], options: ['unique' => true])]
class OutboxMessage extends BaseMessage
{

    #[MongoDB\Field(type: "date", nullable: true)]
    protected ?string $dispatchedAt;

    public function __construct(BaseEvent $event)
    {
        parent::__construct($event);
        $this->status = OutboxMessageStatus::Pending->value;
    }

    /**
     *  Mark the message as dispatched
     *
     * @return void
     */
    public function markAsDispatched(): void
    {
        $this->status = OutboxMessageStatus::Dispatched->value;
        $this->dispatchedAt = (new \DateTimeImmutable())->format(DATE_ATOM);
    }

    /**
     *
     * @return string|null
     */
    public function getDispatchedAt(): ?string
    {
        return $this->dispatchedAt;
    }
}