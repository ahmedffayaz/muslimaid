<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Network;
use App\Models\Category;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'network_id',
        'name',
        'slug',
        'advertiser_id',
        'tracking_url',
        'store_url',
        'description',
        'terms_conditions',
        'extra_info',
        'network_status',
        'status_description',
        'override_cashback',
        'override_categories',
        'feature_sidebar',
        'feature_homepage',
        'editor_pick',
        'custom_cashback_percentage',
        'status',
        'is_fake',
        'address',
        'city',
        'postal_code',
        'latitude',
        'longitude',
    ];

    public function network()
    {
        return $this->belongsTo(Network::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function cashback()
    {
        return $this->hasOne(StoreCashback::class)->where('default', 1);
    }

    public function cashbacks()
    {
        return $this->hasMany(StoreCashback::class);
    }

    public function images()
    {
        return $this->hasMany(StoreImage::class);
    }

    public function logo()
    {
        return $this->images()->where('title', 'logo');
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }

    public function reviews()
    {
        return $this->hasMany(StoreReview::class)->orderBy('rating', 'DESC');
    }

    public function activeReviews()
    {
        return $this->reviews()->where('status', 'active');
    }

    public function commissions()
    {
        return $this->hasMany(UserCashback::class);
    }

    public function editorPicks()
    {
        return $this->hasMany(EditorPick::class);
    }
    
    public function clicks()
    {
        return $this->hasMany(ExitClick::class);
    }

    public function storeRuleData()
    {
        return $this->hasMany(StoreSeoData::class, 'store_id', 'id');
    }

    public function storeAddress()
    {
        return $this->hasMany(StoreAddress::class, 'store_id', 'id');
    }
}
