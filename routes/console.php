<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Artisan::command('total-employe-salary-in-month', function () {
//     $this->call('app:total-employe-salary-in-month');
// })->purpose('Total employe salary in month')->everyMinute();
############ Stopped 
// Artisan::command('total-employe-salary-in-month', function () {
//     $this->call('app:total-employe-salary-in-month');
// })->purpose('Total employe salary in month')
//   ->monthlyOn(1, '00:00'); // يشتغل يوم 1 من كل شهر الساعة 12:00 صباحًا



//   * * * * * cd /path/to/laravel && php artisan schedule:run >> /dev/null 2>&1
