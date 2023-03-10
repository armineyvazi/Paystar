<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public const OK = '1';

    public const WAITHING = '2';

    public const FAIL = '0';

    protected $fillable = [
        'user_id',
        'order_id',
        'status',
        'card_number',
        'service_transaction_id',
        'payed_amount',
        'ref_num',
        'tracking_code',
    ];
}
