<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slider_id','store_id', 'logo', 'banner', 'cashback_title' ,'description','order','slider_type','link'];


    public function slider()
    {
        return $this->belongsTo(Slider::class);
    }
    public function store(){
        return $this->belongsTo(Store::class);
    }
}
