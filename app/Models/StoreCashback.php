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
        'cashback_name',
        'type',
        'value',
        'image',
        'click_url',
        'deeplink_url',
        'sale_commission',
        'cashback',
        'currency',
        'detail',
        'network_detail',
        'default',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function getDeeplinkUrl()
    {
        if (!empty($this->deeplink_url)) {
            return $this->deeplink_url;
        }
        return $this->store->deeplink_url;
    }
}
