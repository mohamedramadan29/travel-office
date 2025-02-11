<?php

namespace Database\Seeders;

use App\Models\admin\GovernRate;
use App\Models\admin\ShippingGovernrate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GovernrateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //Truncate Governate
     //   DB::table('govern_rates')->truncate();
        $governates = [
            [
                'country_id' => 1,
                'name' => ['ar' => 'القاهرة', 'en' => 'Cairo'],
            ],
            [
                'country_id' => 1,
                'name' => ['ar' => 'الجيزة', 'en' => 'Giza'],
            ],
            [
                'country_id' => 1,
                'name' => ['ar' => 'الأسكندرية', 'en' => 'Alexandria'],
            ],
            [
                'country_id' => 1,
                'name' => ['ar' => 'الدقهلية', 'en' => 'Dakahlia'],
            ],
            [
                'country_id' => 1,
                'name' => ['ar' => 'البحر الأحمر', 'en' => 'Red Sea'],
            ],
            [
                'country_id' => 1,
                'name' => ['ar' => 'البحيرة', 'en' => 'Behira'],
            ],
            [
                'country_id' => 1,
                'name' => ['ar' => 'الفيوم', 'en' => 'Fayoum'],
            ],
            [
                'country_id' => 1,
                'name' => ['ar' => 'الغربية', 'en' => 'Gharbiya'],
            ],
            [
                'country_id' => 1,
                'name' => ['ar' => 'الإسماعلية', 'en' => 'Ismailia'],
            ],
            [
                'country_id' => 1,
                'name' => ['ar' => 'المنوفية', 'en' => 'Menofia'],
            ],
            [
                'country_id' => 1,
                'name' => ['ar' => 'المنيا', 'en' => 'Minya'],
            ],
            [
                'country_id' => 1,
                'name' => ['ar' => 'القليوبية', 'en' => 'Qaliubiya'],
            ],
            [
                'country_id' => 1,
                'name' => ['ar' => 'الوادي الجديد', 'en' => 'New Valley'],
            ],
            #######Suadia Governarate
            [
                'country_id'=>2,
                'name'=>['ar'=>'الرياض','en'=>'Riyad']
            ],
            [
                'country_id'=>2,
                'name'=>['ar'=>'مكة المكرمة','en'=>'Makka']
            ]

            ###########
        ];
        foreach($governates as $governate){
            GovernRate::create($governate);

            ########## Add Shipping Foreach Goverate

            ShippingGovernrate::create([
                'governrate_id'=>$governate['country_id'],
                'price'=>100
            ]);
        }
    }
}
