<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use sixlive\DotenvEditor\DotenvEditor;

#[AsCommand(
    name: 'secret:regenerate-app-secret',
    description: 'Regenerates the APP_SECRET in the specified .env file.'
)]
class SecretRegenerateAppSecretCommand extends Command
{
    private const VALID_ENV_FILES = ['.env', '.env.local', '.env.dev', '.env.test'];

    protected function configure(): void
    {
        $this->addArgument('envfile', InputArgument::REQUIRED, 'The environment file to update (e.g., .env, .env.local)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $envname = $input->getArgument('envfile');

        if (!in_array($envname, self::VALID_ENV_FILES)) {
            $io->error("Invalid environment file specified.");
            return Command::INVALID;
        }

        $io->note(sprintf('Updating APP_SECRET in: %s', $envname));
        $secret = bin2hex(random_bytes(32));  // Increased the byte length
        $filepath = realpath(dirname(__FILE__) . '/../..') . '/' . $envname;

        try {
            $editor = new DotenvEditor();
            $editor->load($filepath);
            $editor->set('APP_SECRET', $secret);
            $editor->save();
            $io->success('New APP_SECRET was generated and saved: ' . $secret);
        } catch (\Exception $e) {
            $io->error("Failed to update the environment file: " . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
