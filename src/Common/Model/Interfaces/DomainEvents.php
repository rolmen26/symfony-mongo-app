<?php

namespace App\Common\Model\Interfaces;

interface DomainEvent
{
    public function getUuid();
    public function getEventName();
    public function getQueue();
    public function getHeaders();
    public function getPayload();
}