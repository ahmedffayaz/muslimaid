<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ImportedCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [ 'name', 'status','parent_id','mapped_to', 'network_id'];

    public function mappedTo(){

        return $this->belongsTo(Category::class, 'mapped_to');
    }
    public function network(){
        
        return $this->belongsTo(Network::class);
    }
    public function parent(){

        return $this->belongsTo(ImportedCategory::class, 'parent_id');

    }
    public function childs() {
        return $this->hasMany(ImportedCategory::class,'parent_id','id');
    }
    public function stores(){

        return $this->belongsToMany(Store::class);
    }

}
