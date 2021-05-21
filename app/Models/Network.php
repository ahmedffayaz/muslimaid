<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Store;
use Illuminate\Database\Eloquent\SoftDeletes;


class Network extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = ['name', 'description', 'click_ref'];


    public function stores(){

        return $this->hasMany(Store::class);
    }

    public function importerSetting()
    {
        return $this->hasOne(ImporterSetting::class);

    }

}
