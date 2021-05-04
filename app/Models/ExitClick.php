<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Store;
use App\Models\User;

class ExitClick extends Model
{
    use HasFactory;

    protected $fillable = ['store_id', 'user_id', 'status','exit_url'];

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
