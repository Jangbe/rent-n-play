<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public $casts = ['order_datetime' => 'datetime'];

    public $appends = ['total', 'subtotal'];

    public function getTotalAttribute()
    {
        $total = $this->transactionDetails->reduce(fn ($a, $b) => $a + $b->total, 0);
        $days = $this->extraTimes->reduce(fn ($a, $b) => $a + $b->days, $this->days);
        return $total * $days + $this->delivery_fee;
    }

    public function getSubtotalAttribute()
    {
        return $this->transactionDetails->reduce(fn ($a, $b) => $a + $b->total, 0);
    }

    public function extraTimes()
    {
        return $this->hasMany(ExtraTime::class);
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

    public function testimonial()
    {
        return $this->hasOne(Testimonial::class);
    }
}
