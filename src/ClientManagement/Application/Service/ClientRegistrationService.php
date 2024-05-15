<?php

namespace App\ClientManagement\Application\Service;

use App\ClientManagement\Application\Command\RegisterClientCommand;
use App\ClientManagement\Domain\Events\ClientRegistered as ClientRegisteredDomainEvent;
use App\ClientManagement\Domain\Model\Client;
use App\ClientManagement\Domain\Repository\ClientRepositoryInterface;
use App\Common\Infrastructure\Repository\OutboxMessageRepository;
use App\Common\Model\OutboxMessage;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ClientRegistrationService
{

    private ClientRepositoryInterface $clientRepository;
    private EventDispatcherInterface $eventDispatcher;
    private OutboxMessageRepository $outboxMessageRepository;

    public function __construct(ClientRepositoryInterface $clientRepository, EventDispatcherInterface $eventDispatcher,
                OutboxMessageRepository $outboxMessageRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->outboxMessageRepository = $outboxMessageRepository;
    }

    /**
     * @param RegisterClientCommand $command
     * @return Client
     * @throws \Exception
     */
    public function registerClient(RegisterClientCommand $command): Client
    {
        $client = new Client($command->getEmail(), $command->getName(), $command->getPassword());
        try {
            $this->clientRepository->add($client);
            $ClientRegisteredEvent = new ClientRegisteredDomainEvent($client);
            $outboxMessage = new OutboxMessage($ClientRegisteredEvent);
            $this->outboxMessageRepository->storeEvent($outboxMessage);
            return $client;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

}