<?php

namespace App\Models;

use App\Models\CharityType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cashout extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'charity_types_id',
        'amount',
        'payment_method',
        'new_cashout',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cashbacks()
    {
        return $this->hasMany(UserCashback::class);
    }

    public function bonus()
    {
        return $this->hasOne(Bonus::class);
    }

    public function charity_type()
    {
        return $this->hasOne(CharityType::class, 'id', 'charity_types_id');
    }

    public function metaData()
    {
        return $this->hasMany(CashoutMeta::class);
    }
}
