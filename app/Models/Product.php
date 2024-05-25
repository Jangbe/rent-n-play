<?php

namespace App\Models;

use App\Casts\AvatarCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'description', 'amount', 'price', 'picture'];

    public $appends = ['availableStock'];
    public $casts = ['picture' => AvatarCast::class];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function getAvailableStockAttribute()
    {
        return $this->amount - $this->transactionDetails()
            ->join('transactions', 'transactions.id', '=', 'transaction_detail.transaction_id')
            ->where('status', 'ongoing')->count();
    }
}
