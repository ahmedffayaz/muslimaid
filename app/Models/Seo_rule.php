<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seo_rule extends Model
{
    use HasFactory;

    protected $fillable = [ 'url'];

    public function ruleData()
    {
        return $this->hasMany(Seo_rule_data::class,'seo_rule_id','id');
    }
}
