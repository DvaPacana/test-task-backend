<?php

namespace App\Enums;

use App\Traits\EnumsCommon;

enum PaymentStatuses: string
{
    use EnumsCommon;

    case NEW = 'new';
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case EXPIRED = 'expired';
    case REJECTED = 'rejected';

}
