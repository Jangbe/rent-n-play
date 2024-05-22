<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public $casts = ['order_datetime' => 'datetime'];

    public $appends = ['total'];

    public function getTotalAttribute()
    {
        $total = $this->transactionDetails->reduce(fn ($a, $b) => $a + $b->total, 0);
        return $total * $this->days + $this->delivery_fee;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
