<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected $fillable = [
        'site_name',
        'site_desc',
        'site_phone',
        'site_email',
        'site_address',
        'email_support',
        'facebook_url',
        'twitter_url',
        'youtube_url',
        'favicon',
        'logo',
        'meta_description',
        'site_copyright',
        'promotion_video_url'
    ];
    public $translatable = [
        'site_name',
        'site_desc',
        'site_address',
        'meta_description',
        'site_copyright',
        'promotion_video_url'
    ];

    public function getFaviconAttribute()
    {
        return asset('uploads/settings/' . $this->attributes['favicon']);
    }
    public function getLogoAttribute()
    {
        return asset('uploads/settings/' . $this->attributes['logo']);
    }
    public function getPromotionVideoUrlAttribute()
    {
        return asset('uploads/settings/' . $this->attributes['promotion_video_url']);
    }


}
