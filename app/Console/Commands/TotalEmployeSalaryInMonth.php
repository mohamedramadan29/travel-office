<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\admin\MothlySalary;
use App\Models\admin\EmployeeSalary;

class TotalEmployeSalaryInMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:total-employe-salary-in-month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $totalSalary = EmployeeSalary::where('status',1)->sum('salary');
        $mothlySalary = new MothlySalary();
        $mothlySalary->total_salary = $totalSalary;
        $mothlySalary->save();
    }
}
