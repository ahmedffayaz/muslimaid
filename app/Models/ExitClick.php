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

    protected $fillable = ['store_id', 'user_id', 'status','exit_url', 'current_cashback_percentage'];

    public function store(){
        return $this->belongsTo(Store::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function cashback(){
        return $this->hasOne(UserCashback::class);
    }

}
