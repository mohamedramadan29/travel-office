<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Services\Dashboard\SliderService;
use App\Http\Traits\Message_Trait;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    use Message_Trait;

    protected $slider_service;

    public function __construct(SliderService $slider_service)
    {
        $this->slider_service = $slider_service;
    }

    public function index()
    {
        return view('admin.sliders.index');
    }
    public function getAll()
    {
        return $this->slider_service->getSlidersForDatatable();
    }
    public function store(Request $request)
    {
        $data = $request->all();
        $slider = $this->slider_service->createSlider($data);
        if (!$slider) {
            return $this->Error_message(' لم يتم اضافة ال slider ');
        }
        return $this->success_message(' تمت الاضافة بنجاح  ');
    }

    public function update(Request $request, $id)
    {
        $slider = $this->slider_service->getSlider($id);
        $data = $request->all();
        $slider = $this->slider_service->updateSlider($data, $slider);
        if (!$slider) {
            return $this->Error_message(' لم يتم تحديث ال slider ');
        }
        return $this->success_message(' تمت التحديث بنجاح  ');
    }

    public function destroy($id)
    {
        $slider = $this->slider_service->getSlider($id);
        if (!$slider) {
            abort(404);
        }
        if (! $this->slider_service->delete($slider)) {
            return $this->Error_message(' لم يتم حذف ال slider ');
        }
        return $this->success_message(' تمت الحذف بنجاح  ');
    }
}
