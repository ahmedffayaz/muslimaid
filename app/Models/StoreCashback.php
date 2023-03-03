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

    public function currencyData()
    {
        return $this->hasOne(Currency::class, 'id', 'currency');
    }

    public function getCashback()
    {
        $currency = ($this->type == 'fixed' && isset($this->currencyData)) ? $this->currencyData->symbol : '';
        $percentageSymbol = ($this->type == 'percentage') ? '%' : '';

        if ($this->store->custom_cashback_percentage) {
            return $currency . ($this->store->custom_cashback_percentage / 100) * $this->sale_commission . $percentageSymbol . ' Cashback';
        } else {
            return $currency . (SiteSetting()['cashback_percentage'] / 100) * $this->sale_commission . $percentageSymbol . ' Cashback';
        }
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
