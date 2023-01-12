<?php

namespace App\Models;

use App\Models\CharityType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Cashout extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'user_id',
        'amount',
        'cashout_type',
        'paypal_email',
        'address',
        'city',
        'postcode',
        'country',
        'account_name',
        'bank_title',
        'account_number',
        'bank_sort_code',
        'bic',
        'payment_method', 
        'new_cashout', 
        'charity_types_id',
        'status'];

    public function user(){

        return $this->belongsTo(User::class);
    }
    public function cashbacks(){
        return $this->hasMany(UserCashback::class);
    }
    public function bonus(){
        return $this->hasOne(Bonus::class);
    }
    public function charity_type(){

        return $this->hasOne(CharityType::class,'id','charity_types_id');
    }
}

