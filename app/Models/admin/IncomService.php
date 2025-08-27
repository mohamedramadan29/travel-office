<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class IncomService extends Model
{
    protected $guarded = [];
    public function category()
    {
        return $this->belongsTo(IncomServiceCategory::class);
    }
    public function safe()
    {
        return $this->belongsTo(Safe::class);
    }
}
