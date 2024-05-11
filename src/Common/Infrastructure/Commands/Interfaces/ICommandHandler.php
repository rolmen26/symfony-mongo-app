<?php

namespace App\Common\Infrastructure\Commands\Interfaces;

interface ICommandHandler
{
    public function handle(array $options): int;
}