<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Store;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'status','parent_id'];


    public function stores(){

        return $this->belongsToMany(Store::class);
    }
}
