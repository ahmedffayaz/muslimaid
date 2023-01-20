<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoRuleData extends Model
{
    use HasFactory;
    protected $fillable = ['seo_rule_id', 'type', 'key', 'value'];
}
