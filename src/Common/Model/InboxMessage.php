<?php

namespace App\Common\Model;

use App\Common\Model\Types\InboxMessageStatus;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: "inbox_messages")]
#[MongoDB\UniqueIndex(keys: ['messageId' => 1], options: ['unique' => true])]
class InboxMessage extends BaseMessage
{
    #[MongoDB\Field(type: "date", nullable: true)]
    protected ?string $processedAt = null;

    public function __construct(BaseEvent $event)
    {
        parent::__construct($event);
        $this->status = InboxMessageStatus::Pending->value;
    }

    /**
     * Mark the message as processed
     *
     * @return void
     */
    public function markAsProcessed(): void
    {
        $this->status = InboxMessageStatus::Processed->value;
        $this->processedAt = (new \DateTimeImmutable())->format(DATE_ATOM);
    }

    public function getProcessedAt(): ?string
    {
        return $this->processedAt;
    }
}