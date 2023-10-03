<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashoutMeta extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'cashout_id', 'type', 'value'];
}
