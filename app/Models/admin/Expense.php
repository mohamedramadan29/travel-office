<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $guarded = [];

    public function safe(){
        return $this->belongsTo(Safe::class,'safe_id');
    }
    public function admin(){
        return $this->belongsTo(Admin::class,'admin_id');
    }
    public function getCreatedAtAttribute($value)
    {
        return date('d/m/Y h:i A', strtotime($value));
    }
    public function category(){
        return $this->belongsTo(ExpenceCategory::class,'category_id');
    }
}
