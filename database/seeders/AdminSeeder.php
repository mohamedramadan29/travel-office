<?php

namespace Database\Seeders;

use App\Models\admin\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Admin::create([
            "name"=> "mohamed",
            "email"=> "mr319242@gmail.com",
            "password"=> bcrypt("11111111"),
           
        ]);
    }
}
