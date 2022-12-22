<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Store;
use App\Models\SiteCategory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Category extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [ 'name','slug','description','sort','logo_type','logo_upload','logo_link','banner_type','banner_upload','banner_link', 'status','parent_id','mapped_to', 'network_id','feature_homepage','feature_sidebar'];
    public function stores(){

        return $this->belongsToMany(Store::class);
    }
    public function mappedTo(){

        return $this->belongsTo(SiteCategory::class, 'mapped_to');
    }
    public function parent(){

        return $this->belongsTo(Category::class, 'parent_id');

    }
    public function childs() {
        return $this->hasMany(Category::class,'parent_id','id');
    }
    public function picks() {
        return $this->hasMany(EditorPick::class);
    }
}