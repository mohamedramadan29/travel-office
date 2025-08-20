<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    protected $guarded = [];
    protected $table = 'employee_salaries';
    public function employee()
    {
        return $this->belongsTo(Admin::class,'admin_id');
    }
}
