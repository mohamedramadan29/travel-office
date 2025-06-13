<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\admin\City;
use App\Models\admin\Order;
use App\Models\admin\Country;
use App\Models\admin\GovernRate;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'country_id',
        'governrate_id',
        'city_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }
    public function governorate()
    {
        return $this->belongsTo(GovernRate::class,'governrate_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }
    public function status()
    {
        return $this->is_active == 1 ? 'مفعل' : 'غير مفعل';
    }
    public function orders()
    {
        return $this->hasMany(Order::class,'user_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
