<?php

namespace Database\Seeders;

use App\Models\admin\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setting = Setting::firstOrCreate([
            'site_name' => 'Travel Office',
            'site_desc' => 'Travel Office',
            'site_phone' => '123456789',
            'site_email' => 'mr319242@gmail.com',
            'site_address' => '123 Main St',
            'email_support' => 'mr319242@gmail.com',
            'facebook_url' => 'https://www.facebook.com',
            'twitter_url' => 'https://www.twitter.com',
            'youtube_url' => 'https://www.youtube.com',
            'favicon' => 'favicon.ico',
            'logo' => 'logo.png',
            'meta_description' => 'Travel Office',
            'site_copyright' => 'Travel Office',
            'promotion_video_url' => 'https://www.youtube.com/watch?v=123456789',
        ]);
    }
}
