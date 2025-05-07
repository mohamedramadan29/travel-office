<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    //

    protected $guarded = [];

    public function Product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
