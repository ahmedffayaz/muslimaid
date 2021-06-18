<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Store;
use Illuminate\Database\Eloquent\SoftDeletes;


class StoreCashback extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['store_id', 'cashback_name','type', 'value', 'image','click_url','sale_commission','cashback','currency','detail','network_detail','default'];

    public function store(){

        return $this->belongsTo(Store::class);
    }
}
