<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'name_customer',
        'ref_num',
        'tracking_code',
        'amount',
        'payment_amount',
    ];
}
