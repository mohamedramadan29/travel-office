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
    public function safe()
    {
        return $this->belongsTo(Safe::class,'safe_id');
    }

}
