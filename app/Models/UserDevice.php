<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;
    const TYPE_WEB = 'web';
    protected $fillable = [
        'user_id',
        'fcm_token',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
