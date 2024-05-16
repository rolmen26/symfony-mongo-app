<?php

namespace App\Common\Model;

use App\Common\Model\Types\OutboxMessageStatus;
use DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use App\Common\Infrastructure\Repository\OutboxMessageRepository;

#[MongoDB\Document(collection: "outbox_messages", repositoryClass: OutboxMessageRepository::class)]
#[MongoDB\UniqueIndex(keys: ['messageId' => 1], options: ['unique' => true])]
class OutboxMessage
{
    #[MongoDB\Id(strategy: "auto")]
    #[Assert\NotBlank]
    protected string $id;

    #[MongoDB\Field(type: "string")]
    #[Assert\NotBlank]
    #[Assert\Uuid]
    protected string $messageId;

    #[MongoDB\Field(type: "string")]
    #[Assert\NotBlank]
    protected string $type;

    #[MongoDB\Field(type: "hash")]
    protected array $headers;

    #[MongoDB\Field(type: "hash")]
    protected array $payload;

    #[MongoDB\Field(type: "string")]
    #[Assert\NotBlank]
    protected string $status;

    #[MongoDB\Field(type: "date")]
    protected DateTime $createdAt;

    #[MongoDB\Field(type: "date")]
    protected ?DateTime $dispatchedAt;

    public function __construct(BaseEvent $event)
    {
        $this->messageId = $event->getUuid();
        $this->type = $event->getEventName();
        $this->headers = $event->getHeaders();
        $this->payload = $event->getPayload();
        $this->status = OutboxMessageStatus::Pending->value;
        $this->createdAt = date_create_from_format(DATE_ATOM, date(DATE_ATOM));
        $this->dispatchedAt = null;
    }


    public function markAsDispatched(): void
    {
        $this->status = 'dispatched';
        $this->dispatchedAt = date_create_from_format(DATE_ATOM, date(DATE_ATOM));
    }

    /**
     * @return string
     */
    public function getMessageId(): string
    {
        return $this->messageId;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime|null
     */
    public function getDispatchedAt(): ?DateTime
    {
        return $this->dispatchedAt;
    }
}