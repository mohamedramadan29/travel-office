<?php

namespace App\Livewire\General;


use Livewire\Component;
use App\Models\admin\City;
use App\Models\admin\Country;
use App\Models\admin\GovernRate;

class CountGoverCityComp extends Component
{
    public $country_id;
    public $governorate_id;
    public $city_id;

    public $countries;
    public $governorates = [];
    public $cities = [];

    public function mount(){
        $this->countries = Country::all();
    }
    public function updated($value){
            $this->governorates = $this->country_id ? GovernRate::where('country_id',$this->country_id)->get() : [];
            $this->cities = $this->governorate_id ? City::where('governrate_id',$this->governorate_id)->get() : [];
    }
    public function render()
    {
        return view('livewire.general.count-gover-city-comp');
    }
}
