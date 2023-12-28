<?php

namespace App\Http\Enums\PaymentGateway;

enum SuperpayPaymentStatus: string
{
    case CREATED = 'created';
    case INPROGRESS = 'inprogress';
    case PAID = 'paid';
    case EXPIRED = 'expired';
    case REJECTED = 'rejected';

    /*
     * Этого статуса нет в условиях, но хэш для тестовых данных указан для него.
     * Чтобы не пересчитывать хэш вручную, добавил статус
     * */
    case COMPLETED = 'completed';


}
