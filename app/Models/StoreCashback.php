<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Store;

class StoreCashback extends Model
{
    use HasFactory;

    protected $fillable = ['store_id', 'cashback_name','type', 'value', 'image','click_url','sale_commission'];

    public function store(){

        return $this->belongsTo(Store::class);
    }
}
