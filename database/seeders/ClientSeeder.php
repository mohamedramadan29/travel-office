<?php

namespace Database\Seeders;

use App\Models\admin\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Client::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Client::create([
            'name' => 'Mohamed',
            'email' => 'client1@gmail.com',
            'mobile' => '1234567891',
            'telegram' => '1234567891',
            'whatsapp' => '1234567891',
            'status'=>1,
            'address' => 'العنوان الرئيسي',
        ]);
        Client::create([
            'name' => 'Ahmed',
            'email' => 'client2@gmail.com',
            'mobile' => '1234567892',
            'telegram' => '1234567892',
            'whatsapp' => '1234567892',
            'status'=>1,
            'address' => 'العنوان الثاني',
        ]);
        Client::create([
            'name' => 'Sayed',
            'email' => 'client3@gmail.com',
            'mobile' => '1234567893',
            'telegram' => '1234567893',
            'whatsapp' => '1234567893',
            'status'=>1,
            'address' => 'العنوان الثالث',
        ]);
    }
}
