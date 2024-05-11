<?php

namespace App\ClientManagement\Application\Service;

use App\ClientManagement\Application\Command\RegisterClientCommand;
use App\ClientManagement\Domain\Model\Client;
use App\ClientManagement\Domain\Repository\ClientRepositoryInterface;

class ClientRegistrationService
{

    private ClientRepositoryInterface $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function registerClient(RegisterClientCommand $command): Client
    {
        $client = new Client($command->getEmail(), $command->getName(), $command->getPassword());
        $this->clientRepository->add($client);
        return $client;
    }

}