<?php

namespace App\Http\Enums\PaymentGateway;

enum GoldenpayPaymentStatus: string
{
    case NEW = "new";
    case PENDING = "pending";
    case COMPLETED = "completed";
    case EXPIRED = "expired";
    case REJECTED = "rejected";

}
