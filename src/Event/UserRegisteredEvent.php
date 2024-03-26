<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;
use App\Document\User;

class UserRegisteredEvent extends Event
{
    public const NAME = 'user.registered';

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
