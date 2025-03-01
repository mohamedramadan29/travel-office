<?php

namespace Database\Seeders;

use App\Models\admin\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ####### لحل مشكلة لو فية علاقات بين الجداول
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $data = [
            [
                'name' => ['ar' => 'الكترونيات', 'en' => 'Electronics'],
                'status' => 1,
                'parent' => null
            ],
            [
                'name' => ['ar' => 'الاثاث', 'en' => 'Furniture'],
                'status' => 1,
                'parent' => null
            ],
            [
                'name' => ['ar' => 'المواد التصنيعية', 'en' => 'Raw materials'],
                'status' => 1,
                'parent' => null
            ],
            [
                'name' => ['ar' => 'المواد التجارية', 'en' => 'Business materials'],
                'status' => 1,
                'parent' => null
            ]
        ];

        foreach ($data as $item) {
            $category = new Category();
            $category->create($item);
        }

    }
}
