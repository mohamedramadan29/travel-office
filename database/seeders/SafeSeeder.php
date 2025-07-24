<?php

namespace Database\Seeders;

use App\Models\admin\Safe;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SafeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Safe::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Safe::create([
            'name' => 'الخزنة الرئيسي',
            'balance' => 0,
            'status'=>1,
        ]);
        Safe::create([
            'name' => 'الخزنة الثاني',
            'balance' => 0,
            'status'=>1,
        ]);
        Safe::create([
            'name' => 'الخزنة الثالث',
            'balance' => 0,
            'status'=>1,
        ]);
    }
}
