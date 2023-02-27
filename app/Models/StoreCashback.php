<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Store;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreCashback extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'store_id',
        'network_id',
        'cashback_name',
        'type',
        'value',
        'image',
        'click_url',
        'tracking_url',
        'deeplink_url',
        'sale_commission',
        'cashback',
        'currency',
        'detail',
        'network_detail',
        'default',
        'is_api',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function network()
    {
        return $this->belongsTo(Network::class);
    }

    public function getDeeplinkUrl()
    {
        if ($this->store->override_network) {
            if (!empty($this->deeplink_url))
                return $this->deeplink_url;
            return $this->store->deeplink_url;
        }
        return $this->store->deeplink_url;
    }

    public function getTrackingUrl()
    {
        if ($this->store->override_network) {
            if (!empty($this->tracking_url))
                return $this->tracking_url;
            if (!empty($this->click_url))
                return $this->click_url;
            return $this->store->tracking_url;
        }
        return $this->store->tracking_url;
    }
}
