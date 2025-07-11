<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      // $this->call(AdminSeeder::class);

        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            CountrySeeder::class,
            GovernrateSeeder::class,
            CitySeeder::class,
            CouponSeeder::class,
            FaqSeeder::class,
            AttributeSeeder::class,
            UserSeeder::class,
            ContactSeeder::class,
        ]);

    }
}
