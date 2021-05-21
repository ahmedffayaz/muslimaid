<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PaymentInfo extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'user_id',
        'first_name',
        'last_name',
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
        'status',
        ];

    public function user(){

        return $this->belongsTo(User::class);
    }

}
