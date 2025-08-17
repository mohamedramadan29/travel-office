<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class ExpenceCategory extends Model
{
    protected $guarded = [];
    public function expense()
    {
        return $this->hasMany(Expense::class, 'category_id');
    }
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
