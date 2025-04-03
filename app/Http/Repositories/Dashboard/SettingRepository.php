<?php
namespace App\Http\Repositories\Dashboard;

use App\Models\admin\Setting;

class SettingRepository
{
    public function getSetting($id)
    {
        $setting = Setting::find($id);
        return $setting;
    }

    public function Update($data, $setting)
    {
        $setting->update($data);
        return $setting;
    }
}
