<?php

namespace App\Models;

use App\Models\CharityType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Charity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'country',
        'logo_type',
        'logo_upload',
        'logo_link',
        'charity_types_id',
        'banner_type',
        'banner_upload',
        'banner_link',
        'description',
        'status',
    ];

    public function charity_type()
    {
        return $this->hasOne(CharityType::class, 'id', 'charity_types_id');
    }
    public function Country()
    {
        return $this->hasOne(Country::class, 'id', 'country');
    }
}
