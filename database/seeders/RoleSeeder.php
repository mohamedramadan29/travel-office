<?php

namespace Database\Seeders;

use App\Models\admin\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
