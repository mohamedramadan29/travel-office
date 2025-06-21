<?php

namespace App\Http\Services\Dashboard;

use App\Http\Utils\Imagemanager;
use Yajra\DataTables\DataTables;
use App\Http\Repositories\Dashboard\SliderRepository;

class SliderService
{
    protected $sliderRepository, $imagemanager;
    public function __construct(SliderRepository $sliderRepository, Imagemanager $imagemanager)
    {
        $this->sliderRepository = $sliderRepository;
        $this->imagemanager = $imagemanager;
    }

    public function getSlider($id)
    {
        $slider =  $this->sliderRepository->getSlider($id);
        if (!$slider) {
            abort(404);
        }
        return $slider;
    }

    public function all()
    {
        return $this->sliderRepository->all();
    }
    public function getSlidersForDatatable()
    {
        $sliders = $this->all();
        return DataTables::of($sliders)
            ->addIndexColumn()
            ->addColumn('note', function ($slider) {
                return $slider->getTranslation('note', app()->getLocale());
            })
            ->addColumn('file_name', function ($slider) {
                return view('admin.sliders.datatables.image', compact('slider'));
            })
            ->addColumn('action', function ($slider) {
                return view('admin.sliders.datatables.actions', compact('slider'));
            })
            ->make(true);
    }
    public function createSlider($data)
    {
        if ($data['file_name'] != null) {
            $file_name = $this->imagemanager->UploadSingleImage('/', $data['file_name'], 'sliders');
            $data['file_name'] = $file_name;
        }
        return $this->sliderRepository->createSlider($data);
    }
    public function updateSlider($data, $slider)
    {


        return $this->sliderRepository->updateSlider($data, $slider);
    }
    public function delete($slider)
    {
        return $this->sliderRepository->delete($slider);
    }
}
