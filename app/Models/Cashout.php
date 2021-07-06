<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


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

}

