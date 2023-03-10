<?php

namespace App\Enums;

enum Payment
{
    case PAYSTAR;

    public function payment(): string
    {
        return match ($this) {
            Payment::PAYSTAR => 'paystar'
        };
    }
}
