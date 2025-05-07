<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class ProductVartiant extends Model
{
    protected $table = 'product_vartiants';
    protected $guarded = [];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function VartiantAttributes()
    {
        return $this->hasMany(VartiantAttribute::class);
    }
}
