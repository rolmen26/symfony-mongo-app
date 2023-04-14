<?php

namespace App\Repository;

use App\Document\User;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class UserRepository extends DocumentRepository
{

    /**
     * Find and return the desired user if the password match
     *
     * @param $email
     * @param $password
     * @return User|bool
     */
    public function findLoginUser($email, $password): User|null
    {
        /** @var User $userFound */
        $userFound = $this->userExists($email);
        if (!$userFound) return null;
        $hashedPassword = $userFound->getPassword();
        $matchPassword = password_verify($password, $hashedPassword);
        return $matchPassword ? $userFound : null;
    }

    /**
     * Verify if the user exists in the database
     * Return the User if it exists instead it'll return null
     *
     * @param $email
     * @return User|null
     */
    public function userExists($email): User|null
    {
        return $this->createQueryBuilder()->refresh()
            ->field('email')->equals($email)->getQuery()->getSingleResult();
    }
}