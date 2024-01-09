<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'store_id',
        'network_id',
        'advertiser_id',
        'name',
        'tracking_url',
        'deeplink_url',
        'description',
        'promotion_type',
        'coupon_code',
        'promotion_start_date',
        'promotion_end_date',
        'image',
        'status'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function exitClicks()
    {
        return $this->hasMany(ExitClick::class);
    }

    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class);
    }
}
