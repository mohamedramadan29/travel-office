<?php

namespace App\Models\admin;
use App\Models\admin\Safe;
use App\Models\admin\Admin;
use Illuminate\Database\Eloquent\Model;

class SafeMovement extends Model
{
    protected $guarded = [];

    public function safe(){
        return $this->belongsTo(Safe::class,'safe_id');
    }
    public function admin(){
        return $this->belongsTo(Admin::class,'admin_id');
    }
    public function getMovmentTypeAttribute($value){
        return $value == 'deposit' ? 'إيداع' : 'سحب';
    }
    public function getCreatedAtAttribute($value){
        return date('d/m/Y h:i A', strtotime($value));
    }
}
