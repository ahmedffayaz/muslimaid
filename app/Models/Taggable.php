<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taggable extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'taggable';

    public function taggable()
    {
        return $this->morphTo();
    }
}
