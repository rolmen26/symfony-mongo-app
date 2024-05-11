<?php

namespace App\ClientManagement\Domain\Events;

use App\ClientManagement\Domain\Model\Client;
use Symfony\Contracts\EventDispatcher\Event;

class ClientRegisteredEvent extends Event
{
    public const NAME = 'client.registered';

    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
