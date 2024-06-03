<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraTime extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public $casts = ['is_paid' => 'boolean'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
