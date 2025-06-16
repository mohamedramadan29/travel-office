<?php

namespace App\Providers;

use App\Models\admin\Faq;
use App\Models\admin\Admin;
use App\Models\admin\Brand;
use App\Models\admin\Coupon;
use App\Models\admin\Contact;
use App\Models\admin\Setting;
use App\Models\admin\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        view()->composer('admin.*', function () {
            ####### CategoryCount
            if (!Cache::has("CategoryCount")) {
                Cache::remember('CategoryCount', 60, function () {
                    return Category::count();
                });
            }

            ########## Brand Count
            if (!Cache::has('BrandCount')) {
                Cache::remember('BrandCount', 60, function () {
                    return Brand::count();
                });
            }

            ########## Coupon Count
            if (!Cache::has('CouponCount')) {
                Cache::remember('CouponCount', 60, function () {
                    return Coupon::count();
                });
            }
            ########## Faq Count
            if (!Cache::has('FaqCount')) {
                Cache::remember('FaqCount', 60, function () {
                    return Faq::count();
                });
            }
            ########## Admin Count
            if (!Cache::has('AdminCount')) {

                Cache::remember('AdminCount', 60, function () {
                    return Admin::count();
                });
            }
            ########## Contact Count
            if (!Cache::has('ContactCount')) {
                Cache::remember('ContactCount', 60, function () {
                    return Contact::where('is_read', 0)->count();
                });
            }
            view()->share([
                'CategoryCount' => Cache::get('CategoryCount'),
                'BrandCount' => Cache::get('BrandCount'),
                'AdminCount' => Cache::get('AdminCount'),
                'CouponCount' => Cache::get('CouponCount'),
                'FaqCount' => Cache::get('FaqCount'),
                'ContactCount' => Cache::get('ContactCount'),
            ]);
        });

        ///// Get Settings And Share

        $setting = $this->getSettingOrCreate();
        view()->share([
            'setting' => $setting
        ]);
    }
    /**
     * Get the settings or create a new one if it doesn't exist.
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     */

    public function getSettingOrCreate()
    {
        $getsetting = Setting::firstOr(function () {
            return Setting::create([
                'site_name' => [
                    'en' => 'Ecommerce',
                    'ar' => 'تجارة إلكترونية',
                ],
                'site_desc' => [
                    'en' => 'Ecommerce',
                    'ar' => 'تجارة إلكترونية',
                ],
                'site_phone' => '01011642731',
                'site_email' => 'b1YtM@example.com',
                'site_address' => [
                    'en' => 'cairo',
                    'ar' => 'القاهرة',
                ],
                'email_support' => 'b1YtM@example.com',
                'facebook_url' => 'https://www.facebook.com/',
                'twitter_url' => 'https://twitter.com/',
                'youtube_url' => 'https://www.youtube.com/',
                'favicon' => '/uploads/settings/favicon.png',
                'logo' => '/uploads/settings/logo.png',
                'meta_description' => [
                    'en' => 'Ecommerce',
                    'ar' => 'تجارة إلكترونية',
                ],
                'site_copyright' => [
                    'en' => 'Ecommerce',
                    'ar' => 'تجارة إلكترونية',
                ],
                'promotion_video_url' => 'https://www.youtube.com/watch?v=example',
            ]);
        });
        return $getsetting;
    }
}
