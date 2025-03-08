<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;
    protected $table = 'coupons';
    protected $guarded = [];

    public function getCreatedAtAttribute($value)
    {
        return date('d/m/Y h:i A', strtotime($value));
    }

    public function scopeValid($query)
    {

        return $query->where('is_active', 1)
            ->where('time_used', '<', 'limit')
            ->where('end_date', '>=', now());

    }

    public function scopeInvalid($query)
    {
        return $query->where('is_active', 0)
            ->orWhere('time_used', '>=', 'limit')
            ->orWhere('end_date', '<', now());
    }

    public function couponIsValid()
    {
        return $this->is_active == 1 && $this->time_used < $this->limit && $this->end_date > now();
    }



}
