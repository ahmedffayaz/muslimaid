<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Store;
use App\Models\SiteCategory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'status','parent_id','mapped_to', 'network_id'];


    public function stores(){

        return $this->belongsToMany(Store::class);
    }
    public function mappedTo(){

        return $this->belongsTo(SiteCategory::class, 'mapped_to');
    }
    public function parent(){

        return $this->belongsTo(Category::class, 'parent_id');

    }
}
