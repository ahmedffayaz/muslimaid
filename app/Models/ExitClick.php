<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExitClick extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'store_id',
        'user_id',
        'network_id',
        'network_click_ref',
        'status',
        'exit_url',
        'current_cashback_percentage',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function network()
    {
        return $this->belongsTo(Network::class);
    }

    public function cashback()
    {
        return $this->hasOne(UserCashback::class);
    }
}
