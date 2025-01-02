<?php

namespace App\Enums;

enum PaymentType: string
{
    case CASHONDELIVERY = 'Cash Payment';
    case ONLINEPAYMENT = 'Online Payment';
}
