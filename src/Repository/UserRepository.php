<?php

namespace App\Repository;

use App\Document\User;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\Persistence\ManagerRegistry;

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
        $userFound = $this->userExists($email);
        if (!$userFound) {
            return null;
        }

        $hashedPassword = $userFound->getPassword();
        $matchPassword = password_verify($password, $hashedPassword);

        return $matchPassword ? $userFound : null;
    }

    public function userExists(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function save(User $user): ?User
    {
        try {
            $this->dm->persist($user);
            $this->dm->flush();
            return $user;
        } catch (\Exception $e) {
            return null;
        }
    }
}
