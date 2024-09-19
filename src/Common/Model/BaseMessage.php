<?php

namespace App\Common\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use DateTimeImmutable;

#[MongoDB\MappedSuperclass]
abstract class BaseMessage
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
    protected string $createdAt;

    public function __construct(BaseEvent $event)
    {
        $this->messageId = $event->getUuid();
        $this->type = $event->getEventName();
        $this->headers = $event->getHeaders();
        $this->payload = $event->getPayload();
        $this->createdAt = (new DateTimeImmutable())->format(DATE_ATOM);
    }

    public function getMessageId(): string
    {
        return $this->messageId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}
