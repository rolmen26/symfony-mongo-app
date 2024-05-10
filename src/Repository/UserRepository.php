<?php

namespace App\Repository;

use App\Document\User;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

class UserRepository extends ServiceDocumentRepository
{

    /**
     * @inheritDoc
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findLoginUser(string $email, string $password): ?User
    {
        $userFound = $this->findUserByEmail($email);
        if (!$userFound) {
            return null;
        }

        $hashedPassword = $userFound->getPassword();
        $matchPassword = password_verify($password, $hashedPassword);

        return $matchPassword ? $userFound : null;
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function save(User $user): User|array
    {
        try {
            $this->dm->persist($user);
            $this->dm->flush();
            return $user;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
