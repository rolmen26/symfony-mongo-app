<?php

namespace App\Common\Model\Interfaces;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

interface LifecycleCallbacks
{
    #[MongoDB\PrePersist]
    public function prePersist(): void;

    #[MongoDB\PreUpdate]
    public function preUpdate(): void;
}