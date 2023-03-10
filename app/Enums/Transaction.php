<?php

namespace App\Enums;

enum Transaction: string
{
    case OK = '1';

    case FAILED = '0';

    case WAITING = '2';
}
