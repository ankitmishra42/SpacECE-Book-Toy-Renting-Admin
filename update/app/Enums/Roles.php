<?php

namespace App\Enums;

enum Roles: string
{
    case ROOT = 'root';
    case ADMIN = 'admin';
    case VENDOR = 'vendor';
    case STORE = 'store';
    case EMPLOYE = 'employe';
    case CUSTOMER = 'customer';
    case VISITOR = 'visitor';
    case DRIVER = 'driver';
}
