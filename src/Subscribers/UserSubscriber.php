<?php 

namespace App\Subscribers;

use App\Event\UserRegisteredEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class UserSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::NAME => 'onUserRegistered',
            'user.removed' => 'onUserRemoved',
        ];
    }

    public function onUserRegistered(UserRegisteredEvent $event)
    {
        $user = $event->getUser();
        // Do something with the user
    }

    public function onUserRemoved(GenericEvent $event)
    {
        $user = $event->getSubject();
        // Do something with the user
    }
}