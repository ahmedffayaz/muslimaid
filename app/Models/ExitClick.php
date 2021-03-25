<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExitClick extends Model
{
    use HasFactory;

    protected $fillable = ['store_id', 'user_id', 'status','exit_url'];

}
