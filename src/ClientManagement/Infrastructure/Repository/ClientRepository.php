<?php

namespace App\ClientManagement\Infrastructure\Repository;

use App\ClientManagement\Domain\Model\Client;
use App\ClientManagement\Domain\Repository\ClientRepositoryInterface;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use MongoDB\Driver\Session;

class ClientRepository extends ServiceDocumentRepository implements ClientRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function __construct(ManagerRegistry $registry, $class)
    {
        parent::__construct($registry, $class);
    }

    /**
     * Register a new client
     *
     * @param Client $client
     *
     * @throws \Exception
     *
     * @return void
     */
    public function add(Client $client): void
    {
        try {
            $this->dm->persist($client);
            $this->dm->flush();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function update(Client $client): void
    {
        // TODO: Implement update() method.
    }

    public function delete(Client $client): void
    {
        // TODO: Implement delete() method.
    }
}