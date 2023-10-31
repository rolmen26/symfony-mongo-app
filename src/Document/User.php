<?php

namespace App\Document;

use App\Repository\UserRepository;
use DateTime;
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
    protected DateTime $created_at;

    #[MongoDB\Field(type: 'date')]
    #[Assert\DateTime]
    protected DateTime|null $updated_at;


    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = password_hash($password, CRYPT_SHA512);
        $this->created_at = date_create_from_format(DATE_ATOM, date(DATE_ATOM));
        $this->updated_at = null;
    }

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
     * @param DateTime $created_at
     */
    public function setCreatedAt(\DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @param DateTime $updated_at
     */
    public function setUpdatedAt(\DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updated_at;
    }
}