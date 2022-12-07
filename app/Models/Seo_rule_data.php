<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seo_rule_data extends Model
{
    use HasFactory;
    protected $fillable = [ 'seo_rule_id','meta_keyword','meta_description'];
}
