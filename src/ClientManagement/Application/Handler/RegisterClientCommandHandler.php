<?php

namespace App\ClientManagement\Application\Handler;

use App\ClientManagement\Application\Command\RegisterClientCommand;
use App\ClientManagement\Domain\Events\ClientOnboarded;
use App\ClientManagement\Domain\Model\Client;
use App\ClientManagement\Domain\Repository\ClientRepositoryInterface;
use App\Common\Infrastructure\Repository\OutboxMessageRepository;
use App\Common\Model\OutboxMessage;

class RegisterClientCommandHandler
{
    private ClientRepositoryInterface $clientRepository;
    private OutboxMessageRepository $outboxMessageRepository;

    public function __construct(ClientRepositoryInterface $clientRepository, OutboxMessageRepository $outboxMessageRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->outboxMessageRepository = $outboxMessageRepository;
    }

    /**
     * Handle the RegisterClientCommand
     *
     * @param RegisterClientCommand $command
     *
     * @return void
     *
     * @throws \Exception
     */
    public function __invoke(RegisterClientCommand $command): void
    {
        $Client = new Client($command->getName(), $command->getEmail(), $command->getPassword());
        try {
            $this->clientRepository->add($Client);
            $ClientOnboardedEvent = new OutboxMessage(new ClientOnboarded($Client));
            $this->outboxMessageRepository->storeEvent($ClientOnboardedEvent);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

}