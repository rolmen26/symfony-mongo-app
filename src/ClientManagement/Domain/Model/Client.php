<?php

namespace App\ClientManagement\Domain\Model;

use App\ClientManagement\Infrastructure\Repository\ClientRepository;
use App\Common\Model\BaseDocument;
use App\Common\Model\Interfaces\LifecycleCallbacks;
use App\Common\Model\Traits\SoftDelete;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[MongoDB\Document(collection: "clients", repositoryClass: ClientRepository::class)]
#[MongoDB\UniqueIndex(keys: ['email' => 'asc'])]
#[MongoDB\HasLifecycleCallbacks]
class Client extends BaseDocument implements LifecycleCallbacks
{

    use SoftDelete;

    #[MongoDB\Field(type: "string")]
    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email;

    #[MongoDB\Field(type: "string")]
    #[Assert\NotBlank]
    private string $name;

    #[MongoDB\Field(type: "string")]
    #[Assert\NotBlank]
    private string $password;

    public function __construct(string $email, string $name, string $password)
    {
        $this->uuid = Uuid::uuid5(Uuid::NAMESPACE_DNS, $email)->toString();
        $this->email = $email;
        $this->name = $name;
        $this->password = password_hash($password, CRYPT_SHA512);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, CRYPT_SHA512);
    }

    #[MongoDB\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = date_create_from_format(DATE_ATOM, date(DATE_ATOM));
    }

    #[MongoDB\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = date_create_from_format(DATE_ATOM, date(DATE_ATOM));
    }
}