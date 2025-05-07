<?php

namespace Database\Seeders;

use App\Models\admin\Attribute;
use App\Models\admin\AttributeValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ####### لحل مشكلة لو فية علاقات بين الجداول
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Attribute::truncate();
        AttributeValue::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $size_attribute = Attribute::create([
            'name' => [
                'en' => 'size',
                'ar' => 'حجم',
            ]
        ]);
        $color_attribute = Attribute::create([
            'name' => [
                'en' => 'color',
                'ar' => 'لون',
            ]
        ]);
        $size_attribute->Attributevalues()->createMany([
            [
                'value' => [
                    'en' => 'small',
                    'ar' => 'صغير',
                ],
            ],
            [
                'value' => [
                    'en' => 'medium',
                    'ar' => 'متوسط',
                ],
            ],
            [
                'value' => [
                    'en' => 'large',
                    'ar' => 'كبير',
                ],
            ]
        ]);
        $color_attribute->Attributevalues()->createMany([
            [
                'value' => [
                    'en' => 'red',
                    'ar' => 'احمر',
                ],
            ],
            [
                'value' => [
                    'en' => 'green',
                    'ar' => 'اخضر',
                ],
            ],
            [
                'value' => [
                    'en' => 'blue',
                    'ar' => 'ازرق',
                ],
            ]
        ]);
    }
}
