<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Network;
use App\Models\Category;
use App\Models\Store;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['network_id', 'name', 'advertiser_id', 'tracking_url','store_url', 'status'];

    public function network(){

        return $this->belongsTo(Network::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function cashback(){

        return $this->hasOne(Store::class);

    }

}
