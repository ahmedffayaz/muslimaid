<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Network;
use App\Models\Category;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['network_id', 'name','slug', 'advertiser_id', 'tracking_url','store_url', 'description','terms_conditions','extra_info', 'status'];

    public function network(){

        return $this->belongsTo(Network::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function cashback(){

        return $this->hasOne(StoreCashback::class);

    }

    public function cashbacks(){

        return $this->hasMany(StoreCashback::class);

    }
    public function images(){

        return $this->hasMany(StoreImage::class);

    }
    public function logo(){

        return $this->images()->where('title','logo');

    }
    public function vouchers(){

        return $this->hasMany(Voucher::class);
    }
    public function reviews(){
        
        return $this->hasMany(StoreReview::class)->orderBy('id', 'DESC');
    }
    public function commissions(){
        
        return $this->hasMany(UserCashback::class);
    }

}
