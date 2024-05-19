<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_number', 'user_id', 'delivery', 'delivery_fee', 'addresss_id', 'total', 'status', 'order_datetime' ];
}
