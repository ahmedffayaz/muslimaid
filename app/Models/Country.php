<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'iso_code', 'region_id', 'currency_id', 'upload_type', 'type_value', 'status'];

    public function region() {
        return $this->belongsTo(Region::class);
    }
}
