<?php

namespace App\ClientManagement\Domain\Service;


use App\ClientManagement\Domain\Events\ClientRegisteredEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ClientSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            ClientRegisteredEvent::NAME => 'onClientRegistered',
        ];
    }

    public function onClientRegistered(ClientRegisteredEvent $event)
    {
        $client = $event->getClient();
        // Do something with the user
    }
}