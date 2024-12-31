<?php

namespace App\Enums;

enum WithdrawStatus: string
{
    case PENDING = 'pending';
    case CONFIRM = 'confirm';
    case CANCLE = 'cancle';
}
