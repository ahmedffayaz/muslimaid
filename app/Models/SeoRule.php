<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoRule extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'is_enabled'];

    public function ruleData()
    {
        return $this->hasMany(SeoRuleData::class, 'seo_rule_id', 'id');
    }
}
