<?php

namespace App\Models\admin;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Cviebrock\EloquentSluggable\Sluggable;

class Category extends Model
{
    protected $guarded = [];


    public function getCreatedAtAttribute($value)
    {
        return date('d/m/Y h:i A', strtotime($value));
    }
     #### Add Scop Active
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    public function saleInvoices()
    {
        return $this->hasMany(SaleInvoice::class);
    }


}
