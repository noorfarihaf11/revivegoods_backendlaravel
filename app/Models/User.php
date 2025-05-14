<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\DonationItem;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $primaryKey = 'id_user';
    protected $fillable = [
        'id_user',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
        * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();  // This will return the 'id_user' field since it's set as the primary key
    }

    /**
     * Get the custom claims for the JWT.
     * 
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];  // You can add any additional claims here if needed
    }
     
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function donationItems()
    {
        return $this->hasMany(DonationItem::class, 'id_user', 'id_user');
    }    

    public function pickupRequests()
    {
        return $this->hasMany(PickupRequest::class);
    }

    public function coinTransactions()
    {
        return $this->hasMany(CoinTransaction::class);
    }

    public function exchangeRequests()
    {
        return $this->hasMany(ExchangeRequest::class);
    }
}
