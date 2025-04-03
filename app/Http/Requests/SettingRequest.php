<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'site_name.*'=> 'required|string|max:255',
            'site_desc.*'=> 'required|string',
            'site_phone'=> 'required|string|max:20',
            'site_email'=> 'required|email|max:255',
            'site_address'=> 'required|string|max:255',
            'email_support'=> 'required|email|max:255',
            'facebook_url'=> 'nullable|url|max:255',
            'twitter_url'=> 'nullable|url|max:255',
            'youtube_url'=> 'nullable|url|max:255',
            'favicon'=> 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo'=> 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta_description.*'=> 'nullable|string|min:20|max:160',
            'site_copyright.*'=> 'required|string|max:255',
            'promotion_video_url'=> 'nullable|url|max:255',
        ];
    }
}
