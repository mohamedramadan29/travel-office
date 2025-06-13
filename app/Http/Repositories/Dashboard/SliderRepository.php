<?php

namespace App\Http\Repositories\Dashboard;

use App\Models\admin\Slider;
use Illuminate\Http\Request;

class SliderRepository
{
    public function all()
    {
        return Slider::all();
    }

    public function getSlider($id)
    {
        $slider = Slider::find($id);
        return $slider;
    }

    public function createSlider($data)
    {
        return Slider::create($data);
    }
    public function updateSlider($data, $slider)
    {
        $slider->update($data);
        return $slider;
    }
    public function delete($slider)
    {
        return $slider->delete();
    }
}
