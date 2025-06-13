<?php

namespace App\Models\admin;

use App\Models\admin\GovernRate;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    use HasTranslations;
    public $translatable = ['name'];
    protected $fillable = ['name','governrate_id'];
    public $timestamps = false;
    public function governorate(){ 	//governrate_id
        return $this->belongsTo(GovernRate::class,'governrate_id');
    }
}
