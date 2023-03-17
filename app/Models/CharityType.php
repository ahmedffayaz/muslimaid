<?php

namespace App\Models;

use App\Models\Charity;
use App\Models\UserCashback;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CharityType extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'status',
    ];

    public function charities()
    {
        return $this->HasMany(Charity::class);
    }

    public function cashouts()
    {
        return $this->HasMany(UserCashback::class);
    }
}
