<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CashbackStatusChange extends Model
{
    use HasFactory;

    protected $fillable = ['user_cashback_id', 'cashback_status_id'];



    public function status(){
        return $this->belongsTo(CashbackStatus::class,'cashback_status_id');
    }
}
