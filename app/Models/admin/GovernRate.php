<?php

namespace App\Models\admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\ShippingGovernrate;
use Spatie\Translatable\HasTranslations;

class GovernRate extends Model
{
    use HasTranslations;
    public $translatable = ['name'];
    protected $fillable = ['name','country_id','is_active'];
    public $timestamps = false;


    public function country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }


    public function cities(){ 	//governrate_id
        return $this->hasMany(City::class,'governrate_id');
    }

    public function Users(){
        return $this->hasMany(User::class,'governrate_id');
    }

    public function getisActiveAttribute($value){
        return $value ? 'مفعل' : 'غير مفعل';
    }

    public function ShippingPrice(){
        return $this->hasOne(ShippingGovernrate::class,'governrate_id');
    }


}
