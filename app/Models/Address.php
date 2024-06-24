<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type', 'street_name', 'contact_name', 'contact_phone', 'detail', 'lat', 'lng'];
}
