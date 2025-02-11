<?php

namespace App\Models\admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Country extends Model
{
    use HasTranslations;
    public $translatable = ['name'];
    protected $fillable = ['name','phone_code','is_active'];
    public $timestamps = false;


    public function governorates()
    {
        return $this->hasMany(GovernRate::class,'country_id');
    }

    public function Users(){
        return $this->hasMany(User::class,'country_id');
    }

    public function getisActiveAttribute($value){
        return $value ? 'مفعل' : 'غير مفعل';
    }
}
