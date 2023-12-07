<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporterSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'network_id',
        'import_stores',
        'import_vouchers',
        'import_cashbacks',
        'import_categories',
        'last_import_at',
        'status',
    ];
}
