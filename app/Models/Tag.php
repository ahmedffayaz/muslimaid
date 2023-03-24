<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    use HasFactory;
    public function stores(): MorphToMany
    {
        return $this->morphedByMany(Store::class, 'taggable');
    }
    public function charities(): MorphToMany
    {
        return $this->morphedByMany(Charity::class, 'taggable');
    }
}
