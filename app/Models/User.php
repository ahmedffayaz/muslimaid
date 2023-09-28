<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Store;
use App\Models\Favorite;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes, HasApiTokens;

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
        'email_verified_at',
        'date_of_birth',
        'registration_type',
        'phone',
        'address',
        'address_2',
        'street',
        'country_id',
        'postal_code',
        'intro',
        'avatar',
        'status',
        'otp',
        'provider',
        'provider_id',
        'referred_by',
        'referred_at',
        'is_email_verified',
        'email_preference',
        'title',
        'short_ref_id'
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

    public function paymentInfo()
    {
        return $this->hasOne(PaymentInfo::class);
    }

    public function paypalInfo()
    {
        return $this->paymentInfo()->where('payment_method', 'paypal');
    }

    public function bankInfo()
    {
        return $this->paymentInfo()->where('payment_method', 'bank');
    }

    public function cashbacks()
    {
        return $this->hasMany(UserCashback::class);
    }

    public function balance()
    {
        return $this->cashbacks()->where('status', '=', '3');
    }

    public function bonus()
    {
        return $this->hasOne(Bonus::class);
    }

    public function availableBalance($status = null)
    {
        $total_cashback = 0;
        $cashback = $this->cashbacks();
        if ($status != null) {
            if ($status == 5) {
                $total_cashback = $cashback->get()->where('status', 6)->sum('amount');
            }
            $cashback->where('status', $status);
        }
        $total_cashback += $cashback->sum('amount');
        return $total_cashback;
    }

    public function clicks()
    {
        return $this->hasMany(ExitClick::class)->orderByDesc('created_at');
    }

    public function cashouts()
    {
        return $this->hasMany(Cashout::class);
    }

    public function claims()
    {
        return $this->hasMany(Ticket::class)->where('ticket_type', 'claim')->orderByDesc('created_at');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function favoriteStores(): MorphToMany
    {
        return $this->morphedByMany(Store::class, 'favoritable', 'favorites');
    }
    public function formattedAddress()
    {
        $address = '';

        if (!empty($this->address)) {
            $address .= $this->address;
        }

        if (!empty($this->address_line_2)) {
            $address .= ' ' . $this->address_line_2;
        }

        if (!empty($this->street)) {
            $address .= ' ' . $this->street;
        }

        if (!empty($this->country_id)) {
            $address .= ' ' . optional($this->country)->name;
        }

        if (!empty($this->postal_code)) {
            $address .= ' ' . $this->postal_code;
        }

        return $address;
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function devices()
    {
        return $this->hasMany(UserDevice::class);
    }

    public function metaData()
    {
        return $this->hasMany(UserMeta::class);
    }
}
