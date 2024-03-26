<?php

namespace App\Service;

use App\Document\User;
use App\Event\UserRegisteredEvent;
use App\Repository\UserRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserService
{
    private UserRepository $userRepository;
    private $eventDispatcher;

    public function __construct(UserRepository $userRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function registerUser($email, $password): ?User
    {
        $user = new User($email, $password);

        try {
            $user = $this->userRepository->save($user);

            if ($user) {
                $this->eventDispatcher->dispatch(new UserRegisteredEvent($user), UserRegisteredEvent::NAME);
            }

            return $user;
        } catch (\Exception $e) {
            return null;
        }
    }
}
