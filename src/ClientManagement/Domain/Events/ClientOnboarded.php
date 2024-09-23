<?php

namespace App\ClientManagement\Domain\Events;

use App\ClientManagement\Domain\Model\Client;
use App\Common\Model\BaseEvent;

class ClientOnboarded extends BaseEvent
{
    public const NAME = 'client.onboarded';

    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        parent::__construct();
    }

    public function getPayload(): array
    {
        return [
            'uuid' => $this->getUuid(),
            'fired_at' => $this->firedAt,
            'client' => [
                'id' => $this->client->getId(),
                'name' => $this->client->getName(),
                'email' => $this->client->getEmail(),
                'created_at' => $this->client->getCreatedAt()->format(DATE_ATOM)
            ]
        ];
    }
}