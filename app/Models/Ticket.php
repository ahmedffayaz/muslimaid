<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Ticket extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'user_id', 'category_id', 'ticket_id', 'title', 'priority', 'message','new_ticket','closing_time','closed_by', 'status'
    ];

    public function category()
    {
        return $this->belongsTo(TicketCategory::class);
    }
    public function user(){
        return $this->belongsTo(User::class);

    }
    
    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }

    public function closedByUser(){
        return $this->belongsTo(User::class, 'closed_by');

    }

    public function newReply(){
        return $this->replies()->where('checked',0);
    }
    
    public function lastReply(){
        return $this->hasOne(TicketReply::class)->latest();
    }
}
