<?php

namespace App\ClientManagement\Domain\Events;

use App\ClientManagement\Domain\Model\Client;
use App\Common\Model\BaseEvent;

class ClientRegistered extends BaseEvent
{
    public const NAME = 'client.registered';

    protected Client $client;

    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
        $this->eventName = self::NAME;
        $this->queue = 'client-management';
        $this->payload = $this->getPayload();
        $this->headers = $this->getHeaders();
    }

    public function getPayload(): array
    {
        return [
            'client' => [
                'id' => $this->client->getId(),
                'name' => $this->client->getName(),
                'email' => $this->client->getEmail(),
                'created_at' => $this->client->getCreatedAt()->format(DATE_ATOM)
            ]
        ];
    }
}