<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\admin\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Supplier::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Supplier::create([
            'name' => 'المورد الرئيسي',
            'email' => 'supplier1@gmail.com',
            'mobile' => '1234567891',
            'telegram' => '1234567891',
            'whatsapp' => '1234567891',
            'status'=>1,
            'address' => 'العنوان الرئيسي',
        ]);
        Supplier::create([
            'name' => 'المورد الثاني',
            'email' => 'supplier2@gmail.com',
            'mobile' => '1234567892',
            'telegram' => '1234567892',
            'whatsapp' => '1234567892',
            'status'=>1,
            'address' => 'العنوان الثاني',
        ]);
        Supplier::create([
            'name' => 'المورد الثالث',
            'email' => 'supplier3@gmail.com',
            'mobile' => '1234567893',
            'telegram' => '1234567893',
            'whatsapp' => '1234567893',
            'status'=>1,
            'address' => 'العنوان الثالث',
        ]);
    }
}
