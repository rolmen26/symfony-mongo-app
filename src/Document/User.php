<?php

namespace App\Document;

use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

#[MongoDB\Document(collection: 'users')]
#[MongoDB\Unique(fields: 'email')]
class User
{
    /**
     * @MongoDB\Id
     */
    #[MongoDB\Id]
    protected string $id;

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\Email]
    protected ?string $email = null;

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    protected ?string $password = null;

    #[MongoDB\Field(type: 'date')]
    #[Assert\Date]
    protected ?string $created_at = null;

    #[MongoDB\Field(type: 'date')]
    #[Assert\Date]
    protected ?string $updated_at = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    // stupid simple encryption (please don't copy it!)
    public function setPassword(?string $password): void
    {
        $this->password = sha1($password);
    }

    /**
     * @param string|null $created_at
     */
    public function setCreatedAt(?string $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @param string|null $updated_at
     */
    public function setUpdatedAt(?string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }
}