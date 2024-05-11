<?php

namespace App\Common\Infrastructure\Commands\Handlers;

use App\Common\Infrastructure\Commands\Interfaces\ICommandHandler;
use sixlive\DotenvEditor\DotenvEditor;
use Symfony\Component\Console\Command\Command;

class SecretRegenerateHandler implements ICommandHandler
{
    private const VALID_ENV_FILES = ['.env', '.env.local', '.env.dev', '.env.test', '.env.example'];

    private string $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    /**
     * Handle the command.
     *
     * @param array $options
     *
     * @return int
     */
    public function handle(array $options): int
    {
        $envname = $options['envfile'];
        $filepath = $this->projectDir . '/' . $envname;

        if (!in_array($envname, self::VALID_ENV_FILES)) {
            return Command::INVALID;
        }

        try {
            $secret = bin2hex(random_bytes(32));
            $editor = new DotenvEditor();
            $editor->load($filepath);
            $editor->set('APP_SECRET', $secret);
            $editor->save();
        } catch (\Exception $e) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
