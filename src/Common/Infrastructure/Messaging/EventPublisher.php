<?php

namespace App\Common\Infrastructure\Messaging;

use Doctrine\ODM\MongoDB\Iterator\Iterator;
use Enqueue\Client\Message;
use Enqueue\Client\ProducerInterface;

class EventPublisher
{
    private ProducerInterface  $producer;

    public function __construct(ProducerInterface $producer)
    {
        $this->producer = $producer;
    }

    /**
     * Publish a message to RabbitMQ
     *
     * @param Iterator $messages
     *
     * @return void
     *
     * @throws \Exception
     */
    public function publish(Iterator $messages): void
    {
        try{
            foreach ($messages as $message) {
                $EnqueueMessage = new Message($message->getPayload(), $message->getProperties());
                $this->producer->sendEvent($_ENV['RABBITMQ_EXCHANGE_NAME'], $EnqueueMessage);
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}