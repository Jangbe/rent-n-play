<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public $casts = ['order_datetime' => 'datetime'];

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, TransactionDetail::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
