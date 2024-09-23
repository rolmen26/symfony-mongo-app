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
        try {
            foreach ($messages as $message) {
                $EnqueueMessage = $this->buildMessage($message);
                $this->producer->sendEvent($_ENV['RABBITMQ_QUEUE_NAME'], $EnqueueMessage);
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Build a message to be sent to RabbitMQ
     *
     * @param $message
     *
     * @return Message
     */
    private function buildMessage($message): Message
    {
        $EnqueueMessage = new Message($message->getPayload());
        $EnqueueMessage->setMessageId($message->getMessageId());
        $EnqueueMessage->setTimestamp((int) $message->getProperties()['timestamp']);
        $EnqueueMessage->setHeaders($message->getProperties());
        return $EnqueueMessage;
    }
}