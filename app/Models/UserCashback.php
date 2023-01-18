<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Store;
use App\Models\ExitClick;
use App\Models\CashbackStatus;
use Illuminate\Database\Eloquent\SoftDeletes;


class UserCashback extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = ['store_id', 'user_id','cashout_id', 'amount','status', 'detalis','network_commission','order_value', 'exit_click_id','network_order_id','network_commission_id','click_date','event_date', 'type'];

    public function store(){

        return $this->belongsTo(Store::class);
    }
    public function exitClick(){

        return $this->belongsTo(ExitClick::class,'exit_click_id');
    }
    public function user(){

        return $this->belongsTo(User::class);
    }
    public function statusMap(){

        return $this->belongsTo(CashbackStatus::class, 'status');
    }

    public function statusHistory(){
        return $this->hasMany(CashbackStatusChange::class);
    }

}
