<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Network;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['network_id', 'name', 'tracking_url','store_url', 'status'];

    public function network(){

        return $this->belongsTo(Network::class);
    }

}
