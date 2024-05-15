<?php

namespace App\Common\Infrastructure\Repository;

use App\Common\Model\BaseEvent;
use App\Common\Model\OutboxMessage;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

class OutboxMessageRepository extends ServiceDocumentRepository
{

    /**
     * @inheritDoc
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OutboxMessage::class);
    }

    /**
     * @param BaseEvent $event
     *
     * @return void
     *
     * @throws \Exception
     */
    public function storeEvent(OutboxMessage $message): void
    {
        try {
            $this->dm->persist($message);
            $this->dm->flush();
        } catch (\Exception $e) {
            throw new \Exception('Error storing event: ' . $e->getMessage());
        }
    }

    /**
     * @param int $limit
     *
     * @return void
     *
     * @throws \Exception
     */
    public function getUnsentMessages(int $limit = 10): void
    {
        try {
            $this->dm->createQueryBuilder(OutboxMessage::class)
                ->field('dispatchedAt')->equals(null)
                ->limit($limit)
                ->sort('id', 'ASC')
                ->getQuery()
                ->execute();
        } catch (\Exception $e) {
            throw new \Exception('Error getting unsent messages: ' . $e->getMessage());
        }
    }
}