<?php

namespace App\Common\Model\Types;

enum InboxMessageStatus: string
{
    case Pending = 'pending';
    case Processed = 'processed';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Processed => 'Processed',
            self::Failed => 'Failed',
        };
    }

}
