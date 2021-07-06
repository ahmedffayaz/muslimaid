<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\ResetPasswordNotification;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles , SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'registration_type',
        'phone',
        'address',
        'intro',
        'avatar',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function paymentInfo(){
        return $this->hasOne(PaymentInfo::class);
    }
    public function paypalInfo(){
        return $this->paymentInfo()->where('payment_method','paypal');
    }
    public function bankInfo(){
        return $this->paymentInfo()->where('payment_method','bank');
    }


    public function cashbacks(){
        return $this->hasMany(UserCashback::class);
    }
    public function balance(){
        return $this->cashbacks()->where('status','=','3');
    }  
    public function bonus(){
        return $this->hasOne(Bonus::class);
    }  
    public function availableBalance(){
        $cashback = $this->cashbacks()->where('status','=','3')->sum('amount');

        $bonus = $this->bonus()->where('status','unpaid')->first() ?$this->bonus()->where('status','unpaid')->first()->amount :0;

        return $cashback+$bonus;
    }
    public function clicks(){
        return $this->hasMany(ExitClick::class)->orderByDesc('created_at');
    } 
    public function cashouts(){
        return $this->hasMany(Cashout::class);
    }
    public function claims(){
        return $this->hasMany(Ticket::class)->where('ticket_type','claim')->orderByDesc('created_at');
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    
}
