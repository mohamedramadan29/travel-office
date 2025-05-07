<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class VartiantAttribute extends Model
{
    //

    protected $guarded = [];

    public function ProductVartiant()
    {
        return $this->belongsTo(ProductVartiant::class);
    }

    public function AttributeValue(){
        return $this->belongsTo(AttributeValue::class);
    }
}
