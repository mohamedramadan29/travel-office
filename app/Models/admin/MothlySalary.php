<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class MothlySalary extends Model
{
    protected $table = 'mothly_salaries';
    protected $fillable = [
        'total_salary',
    ];
}
