<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = ['store_id','description','image','click_url','sale_commission','destination','link_id','link_name','link_type','coupon_code','promotion_type','promotion_end_date','promotion_start_date'];

    public function store(){

        return $this->belongsTo(Store::class);
    }
}
