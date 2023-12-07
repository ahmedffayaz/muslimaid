<?php

namespace App\Models;

use App\Models\Store;
use App\Models\SiteCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'is_map_enable',
        'visibility',
        'slug',
        'description',
        'sort',
        'logo_type',
        'logo_upload',
        'logo_link',
        'banner_type',
        'banner_upload',
        'banner_link',
        'status',
        'parent_id',
        'mapped_to',
        'network_id',
        'advertiser_id',
        'meta_description',
        'meta_keyword',
        'meta_title',
    ];

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'category_store');
    }

    public function mappedTo()
    {
        return $this->belongsTo(SiteCategory::class, 'mapped_to');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function childs()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function picks()
    {
        return $this->hasMany(EditorPick::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function enableGoogleMap()
    {
        $html = '';
        if ($this->is_map_enable == 1)
            $html = '<span class="badge badge-dim badge-pill badge-info text-capitalize"><em class="icon ni ni-done"></em>Map Enabled</span>';
        return $html;
    }

    public function network()
    {
        return $this->belongsTo(Network::class);
    }
}
