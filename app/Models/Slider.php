<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slider_type',
        'auto_paly',
        'slides_per_page',
        'slider_height',
        'slider_width',
        'is_active',
    ];

    public function slides()
    {
        return $this->hasMany(Slide::class)->orderBy('order', 'ASC');
    }
}
