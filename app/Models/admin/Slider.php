<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Slider extends Model
{
    use HasTranslations;
    protected $fillable = [
        'file_name',
        'product_slug',
        'note',
    ];

    protected $translatable = ['note'];

    public function getFileNameAttribute($filename)
    {
        return 'uploads/sliders/' . $filename;
    }
    public function getCreatedAtAttribute()
    {
        return date('d-m-y h:i a', strtotime($this->attributes['created_at']));
    }
    public function Image(){
        return $this->file_name;
    }
}
