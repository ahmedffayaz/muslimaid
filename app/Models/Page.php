<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use VanOns\Laraberg\Models\Gutenbergable;

class Page extends Model
{
    use HasFactory, Gutenbergable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'excerpt',
        'lb_content',
        'status',
        'description',
        'meta_description',
        'meta_keyword',
        'meta_title',
    ];
}
