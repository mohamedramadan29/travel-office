<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Role extends Model
{

   // use HasTranslations;
    protected $guarded = [];
    //public $translatable = ['role'];

    public function getPermissionAttribute($value){
        return json_decode($value);
    }

    public function admins(){
        return $this->hasMany(Admin::class,'role_id');
    }
}
