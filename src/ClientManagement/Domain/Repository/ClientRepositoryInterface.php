<?php

namespace App\ClientManagement\Domain\Repository;

use App\ClientManagement\Domain\Model\Client;
use MongoDB\Driver\Session;

interface ClientRepositoryInterface
{
    public function add(Client $client): void;
    public function update(Client $client): void;
    public function delete(Client $client): void;
}