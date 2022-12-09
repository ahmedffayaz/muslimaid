<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreAddress extends Model
{
    use HasFactory;
    protected $fillable = [
     'store_id',
     'address',
     'latitude',
     'longitude' 
    ];
}
