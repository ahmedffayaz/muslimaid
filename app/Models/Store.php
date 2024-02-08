<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Network;
use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
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
        'deeplink_url',
        'store_url',
        'description',
        'terms_conditions',
        'extra_info',
        'network_status',
        'status_description',
        'override_cashback',
        'override_categories',
        'override_network',
        'editor_pick',
        'custom_cashback_percentage',
        'status',
        'is_fake',
        'address',
        'city',
        'postal_code',
        'latitude',
        'longitude',
        'is_api',
        'competitors',
    ];

    protected $appends = ['cashback_integer', 'default_cashback'];

    public function network()
    {
        return $this->belongsTo(Network::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_store');
    }

    public function cashback()
    {
        return $this->hasOne(StoreCashback::class)->where('default', 1);
    }

    public function cashbacks()
    {
        return $this->hasMany(StoreCashback::class, 'store_id');
    }
    public function images()
    {
        return $this->hasMany(StoreImage::class);
    }

    public function logo()
    {
        return $this->images()->where('title', 'logo');
    }

    public function largeLogo()
    {
        return $this->images()->where('title', 'large logo');
    }

    public function smallBanner()
    {
        return $this->images()->where('title', 'Cover');
    }

    public function largeBanner()
    {
        return $this->images()->where('title', 'large cover');
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

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function favorites(): MorphToMany
    {
        return $this->morphToMany(Favorite::class, 'favoritable');
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

    public function toArray()
    {
        $array = parent::toArray();

        // Hide the appended attributes when converting to an array
        $this->makeHidden(['cashback_integer', 'default_cashback']);

        return $array;
    }

    public function getCashbackIntegerAttribute()
    {
        if ($this->custom_cashback_percentage && !empty($this->cashback->sale_commission)) {
            if (!is_numeric($this->custom_cashback_percentage))
                $this->custom_cashback_percentage = substr($this->custom_cashback_percentage, 0, -1);

            return ($this->custom_cashback_percentage / 100) * $this->cashback->sale_commission;
        } elseif (!empty($this->cashback->sale_commission)) {
            return (SiteSetting()['cashback_percentage'] / 100) * $this->cashback->sale_commission;
        }
        return null;
    }

    public function getDefaultCashbackAttribute()
    {
        if (isset($this->cashback)) {
            if ($this->cashback->type === 'fixed') {
                if ($this->cashbacks_count > 1)
                    return 'Up to £' . number_format((float) $this->cashback_integer, 2, '.', '') . ' Cashback';
                return '£' . number_format((float) $this->cashback_integer, 2, '.', '') . ' Cashback';
            }

            if ($this->cashback->type === 'percentage') {
                if ($this->cashbacks_count > 1)
                    return 'Up to ' . number_format((float) $this->cashback_integer, 2, '.', '') . '% Cashback';
                return number_format((float) $this->cashback_integer, 2, '.', '') . '% Cashback';
            }
        }
        return null;
    }
}
