<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'Pending';
    case CONFIRM = 'Confirm';
    case PICKED_UP = 'Picked up';
    case PROCESSING = 'Processing';
    case ON_GOING = 'On Going';
    case DELIVERED = 'Delivered';
    case CANCELLED = 'Cancelled';
}
