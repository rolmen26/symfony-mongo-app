<?php

declare(strict_types=1);

namespace App\Common\Model\Types;

enum OutboxMessageStatus: string
{
    case Pending = 'pending';
    case Dispatched = 'dispatched';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Dispatched => 'Dispatched',
            self::Failed => 'Failed',
        };
    }
}