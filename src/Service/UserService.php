<?php

namespace App\Service;

use App\Document\User;
use App\Event\UserRegisteredEvent;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserService
{
    private UserRepository $userRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(UserRepository $userRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function registerUser($email, $password): User|array
    {
        $user = new User($email, $password);

        try {
            $user = $this->userRepository->save($user);

            if ($user instanceof User) {
                $this->eventDispatcher->dispatch(new UserRegisteredEvent($user), UserRegisteredEvent::NAME);
            } else {
                return $user;
            }

            return $user;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Find user by email and password
     *
     * @param $email
     * @param $password
     *
     * @return User|null
     */
    public function findLoginUser($email, $password): ?User
    {
        try {
            return $this->userRepository->findLoginUser($email, $password);
        } catch (\Exception $e) {
            return null;
        }
    }
}
