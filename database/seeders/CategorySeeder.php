<?php

namespace Database\Seeders;

use App\Models\admin\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Category::create([
            'name' => 'تذاكر الطيران',
            'status'=>1,
        ]);
        Category::create([
            'name' => 'حجز فندقي',
            'status'=>1,
        ]);

    }
}
