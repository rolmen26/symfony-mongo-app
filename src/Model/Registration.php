<?php

namespace App\Model;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class Registration
{
    #[Assert\Type(type: "App\Document\User")]
    protected $user;

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    /**
     * @throws MongoDBException
     */
    public function registerUser(DocumentManager $dm, Request $request): void
    {
        if (!$this->user) {
            $this->user = new User();
            $data = json_decode($request->getContent(), true);
            $this->user->setEmail($data['email']);
            $this->user->setPassword($data['password']);
            $this->user->setCreatedAt(date('Y-m-d h:i:sa'));
            $this->user->setUpdatedAt(date('Y-m-d h:i:sa'));
            $dm->persist($this->user);
            $dm->flush();
        }
    }

}