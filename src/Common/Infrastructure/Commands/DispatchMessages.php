<?php

namespace App\Common\Infrastructure\Commands;

use App\Common\Infrastructure\Messaging\EventPublisher;
use App\Common\Infrastructure\Repository\OutboxMessageRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DispatchMessages extends Command
{
    protected static $defaultName = 'app:dispatch-messages';

    private OutboxMessageRepository $outboxMessageRepository;

    private EventPublisher $eventBus;

    public function __construct(OutboxMessageRepository $outboxMessageRepository, EventPublisher $eventBus)
    {
        $this->outboxMessageRepository = $outboxMessageRepository;
        $this->eventBus = $eventBus;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Dispatch messages from outbox table to message broker')
            ->setHelp('This command allows you to dispatch messages from outbox table to message broker');
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $messages = $this->outboxMessageRepository->getUnsentMessages();

            if ($messages->count() === 0) {
                $io->success('No messages to dispatch.');
                return Command::SUCCESS;
            }

            $this->eventBus->publish($messages);
            $io->success("Messages dispatched: {$messages->count()}");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Error dispatching message: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}