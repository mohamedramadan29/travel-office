<?php

namespace App\Models\admin;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Cviebrock\EloquentSluggable\Sluggable;

class Brand extends Model
{ use Sluggable;

    use HasTranslations;
    protected $guarded = [];

    protected $translatable = ['name'];


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function Products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeInActive($query)
    {
        return $query->where('status', 0);
    }

    public function getStatus()
    {
        if (Config::get('app.locale') == 'ar') {
            return $this->status == 1 ? 'مفعل' : 'غير مفعل';
        } else {
            return $this->status == 1 ? 'Active' : 'Inactive';
        }
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d/m/Y h:i A', strtotime($value));
    }

    ######### Get Logo Accessories With FullPath

    public function getLogoAttribute($logo){
        return 'uploads/brands/'.$logo;
    }

}
