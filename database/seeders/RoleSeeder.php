<?php

namespace Database\Seeders;

use App\Models\admin\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ####### لحل مشكلة لو فية علاقات بين الجداول
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $permisions = [];
        foreach (config('permissions_ar') as $key => $value) {
            $permisions[] = $key;
        }
        Role::create([
            'role' => [
                'ar' => 'مدير',
                'en' => 'Manager',
            ],
            'permission' => json_encode($permisions)
        ]);
    }
}
