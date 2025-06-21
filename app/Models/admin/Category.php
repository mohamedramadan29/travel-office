<?php

namespace App\Models\admin;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Cviebrock\EloquentSluggable\Sluggable;

class Category extends Model
{
    use Sluggable;

    use HasTranslations;
    protected $translatable = ['name'];

    protected $guarded = [];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    // public function getStatusAttribute($value)
    // {

    // }

    public function getStatusTranslated()
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

    public function Products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
    public function Childrens()
    {
        return $this->hasMany(Category::class, 'parent');
    }
    public function Parent()
    {
        return $this->belongsTo(Category::class, 'parent');
    }

    #### Add Scop Active
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

}
