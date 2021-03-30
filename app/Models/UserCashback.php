<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Store;
use App\Models\ExitClick;

class UserCashback extends Model
{
    use HasFactory;

    protected $fillable = ['store_id', 'user_id', 'amount','status', 'exit_click_id','click_date','event_date'];

    public function store(){

        return $this->belongsTo(Store::class);
    }
    public function exitClick(){

        return $this->belongsTo(ExitClick::class,'exit_click_id');
    }

}
