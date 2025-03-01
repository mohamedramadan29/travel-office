<?php

namespace Database\Seeders;

use App\Models\admin\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ####### لحل مشكلة لو فية علاقات بين الجداول
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Brand::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'name' => ['ar' => 'ابل', 'en' => 'apple'],
                'logo' => 'https://khamsat.hsoubcdn.com/assets/images/logo-73045c76e830509d4dbe03ea6172d22f047c708fed5435e93ffd47f80ee5ffa4.png'
            ],
            [
                'name' => ['ar' => 'سامسونج', 'en' => 'samsung'],
                'logo' => 'https://khamsat.hsoubcdn.com/assets/images/logo-73045c76e830509d4dbe03ea6172d22f047c708fed5435e93ffd47f80ee5ffa4.png'
            ],
            [
                'name' => ['ar' => 'هواوي', 'en' => 'huawei'],
                'logo' => 'https://khamsat.hsoubcdn.com/assets/images/logo-73045c76e830509d4dbe03ea6172d22f047c708fed5435e93ffd47f80ee5ffa4.png'
            ]
        ];

        foreach ($data as $item) {
            Brand::create($item);
        }

    }
}
