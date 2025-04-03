<?php
namespace App\Http\Services\Dashboard;

use App\Http\Repositories\Dashboard\SettingRepository;
use App\Http\Utils\Imagemanager;
class SettingService
{
    protected $settingRepository, $imageManager;
    public function __construct(SettingRepository $settingRepository, Imagemanager $imageManager)
    {
        $this->imageManager = $imageManager;
        $this->settingRepository = $settingRepository;
    }

    public function getSetting($id)
    {
        $setting =  $this->settingRepository->getSetting($id);
        if(!$setting){
            abort(404, 'Setting not found');
        }
        return $setting;
    }
    public function update($data, $id)
    {
        $setting = $this->getSetting($id);
        if(array_key_exists('logo', $data) && $data['logo'] != null){
            ### Delete Old Logo
            $this->imageManager->deleteImageFromLocal($setting->logo);
            ### Upload New Logo
            $filename = $this->imageManager->UploadSingleImage('/', $data['logo'], 'settings');
            $data['logo'] = $filename;
        }
        if(array_key_exists('favicon', $data) && $data['favicon'] != null){
            ### Delete Old Favicon
            $this->imageManager->deleteImageFromLocal($setting->favicon);
            ### Upload New Favicon
            $filename = $this->imageManager->UploadSingleImage('/', $data['favicon'], 'settings');
            $data['favicon'] = $filename;
        }
        return $this->settingRepository->Update($data, $setting);
    }

}
