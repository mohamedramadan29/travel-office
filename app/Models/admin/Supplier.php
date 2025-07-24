<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [];

    public function getStatusAttribute($value){
        return $value == 1 ? 'نشط' : 'غير نشط';
    }
    public function scopeActive($query){
        return $query->where('status', 1);
    }
}
