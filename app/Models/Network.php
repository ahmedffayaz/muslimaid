<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Store;

class Network extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'click_ref'];


    public function stores(){

        return $this->hasMany(Store::class);
    }

}
