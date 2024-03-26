<?php

namespace App\EventListener;

use App\Event\UserRegisteredEvent;

class UserRegisteredListener
{
    public function onUserRegistered(UserRegisteredEvent $event)
    {
        $user = $event->getUser();
        // Lógica para enviar un email de bienvenida, etc.
    }
}
