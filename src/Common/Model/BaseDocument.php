<?php

namespace App\Common\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

abstract class BaseDocument
{
    #[MongoDB\Id]
    protected string $id;

    #[MongoDB\Field(type: 'string')]
    #[Assert\Uuid]
    protected string $uuid;

    #[MongoDB\Field(type: 'date')]
    #[Assert\DateTime]
    protected DateTime $createdAt;

    #[MongoDB\Field(type: 'date', nullable: true)]
    #[Assert\DateTime]
    protected ?DateTime $updatedAt;

    public function getId(): string
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
