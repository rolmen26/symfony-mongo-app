<?php

namespace App\Document;

use App\Repository\UserRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

#[MongoDB\Document(collection: 'users', repositoryClass: UserRepository::class)]
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
    protected string $email;

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    protected string $password;

    #[MongoDB\Field(type: 'date')]
    #[Assert\Date]
    protected string $created_at;

    #[MongoDB\Field(type: 'date')]
    #[Assert\Date]
    protected string $updated_at;

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    // stupid simple encryption (please don't copy it!)
    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, CRYPT_SHA512);
    }

    /**
     * @param string $created_at
     */
    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @param string|null $updated_at
     */
    public function setUpdatedAt(mixed $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }
}