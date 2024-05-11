<?php

namespace App\Common\Infrastructure\Commands;

use App\Common\Infrastructure\Commands\Handlers\SecretRegenerateHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'secret:regenerate-app-secret',
    description: 'Regenerates the APP_SECRET in the specified .env file.'
)]
class SecretRegenerateAppSecretCommand extends Command
{
    private SecretRegenerateHandler $handler;
    private string $projectDir;

    public function __construct(string $projectDir)
    {
        parent::__construct();
        $this->projectDir = $projectDir;
        $this->handler = new SecretRegenerateHandler($this->projectDir);
    }

    protected function configure(): void
    {
        $this->addArgument('envfile', InputArgument::REQUIRED, 'The environment file to update');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $envname = $input->getArgument('envfile');

        $result = $this->handler->handle(['envfile' => $envname]);

        if ($result === Command::INVALID) {
            $io->error("Invalid environment file specified.");
        } elseif ($result === Command::FAILURE) {
            $io->error("Failed to update the environment file.");
        } else {
            $io->success('New APP_SECRET was generated and saved.');
        }

        return $result;
    }
}
