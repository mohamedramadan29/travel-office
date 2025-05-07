<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Attribute extends Model
{
    use HasTranslations;
    protected $fillable = ['name'];
    public $translatable = ['name'];
    public function Attributevalues()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d/m/Y h:i A', strtotime($value));
    }
}
