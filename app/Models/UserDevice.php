<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;
    const TYPE_WEB = 'web';
    const TYPE_API = 'api';
    protected $fillable = [
        'user_id',
        'fcm_token',
        'type'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
