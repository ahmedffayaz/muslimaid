<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreSeoData extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'url',
        'type',
        'key',
        'value',
    ];
}
