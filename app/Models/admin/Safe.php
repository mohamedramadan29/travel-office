<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class Safe extends Model
{
    protected $guarded = [];

    public function getCreatedAtAttribute($value)
    {
        return date('d/m/Y h:i A', strtotime($value));
    }

    public function movements(){
        return $this->hasMany(SafeMovement::class,'safe_id');
    }
    public function scopeActive($query){
        return $query->where('status', 1);
    }
}
