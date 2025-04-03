<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Http\Services\Dashboard\SettingService;
use App\Http\Traits\Message_Trait;
use App\Models\admin\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use Message_Trait;
    protected $settingService;
    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index()
    {
        return view('admin.setting.index');
    }
    public function update(SettingRequest $request,$id)
    {
        $data = $request->except('_token','_method');
        $setting = $this->settingService->update($data, $id);
        if (!$setting) {
            return $this->Error_message(' لم يتم تحديث البيانات بنجاح');
        }
        return $this->Success_message('تم تحديث البيانات بنجاح');
    }
}
