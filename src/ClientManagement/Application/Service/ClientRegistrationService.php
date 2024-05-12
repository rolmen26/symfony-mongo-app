<?php

namespace App\ClientManagement\Application\Service;

use App\ClientManagement\Application\Command\RegisterClientCommand;
use App\ClientManagement\Domain\Events\ClientRegisteredEvent;
use App\ClientManagement\Domain\Model\Client;
use App\ClientManagement\Domain\Repository\ClientRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ClientRegistrationService
{

    private ClientRepositoryInterface $clientRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(ClientRepositoryInterface $clientRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->clientRepository = $clientRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function registerClient(RegisterClientCommand $command): Client
    {
        $client = new Client($command->getEmail(), $command->getName(), $command->getPassword());
        $this->clientRepository->add($client);
        $this->eventDispatcher->dispatch(new ClientRegisteredEvent($client), ClientRegisteredEvent::NAME);
        return $client;
    }

}