<?php
namespace App\Http\Repositories\Dashboard;

use App\Models\admin\City;
use App\Models\admin\Country;
use App\Models\admin\GovernRate;
use App\Models\admin\ShippingGovernrate;

class WorldRepository
{

    //////// Get The Country
    public function CountryByID($id)
    {
        $country = Country::find($id);
        return $country;
    }

    ///////// Get The Governorate
    public function GovernrateByID($id)
    {
        $governrate = GovernRate::find($id);
        return $governrate;
    }

    ##### Get All
    public function AllCountry()
    {

        $countries = Country::withCount(['governorates','Users'])->when(!empty(request()->keyword), function ($query) {
            $query->where('name', 'like', '%' . request()->keyword . '%');
        })->paginate(10);
        //dd($countries);
        return $countries;
    }
    /////// All Governorate
    public function GovernorateByCountry($country)
    {
        $governorates = $country->governorates()
        ->withCount(['cities','Users'])
        ->with(['country','shippingPrice'])
        ->paginate(5);
        //dd($governorates);
        return $governorates;
    }
    ############## All Citizen
    public function GovernrateCitizen($governrate)
    {
        $citizens = $governrate->cities;
        return $citizens;
    }


    ///////// Update Coutnry status

    public function UpdateStatus($country)
    {
        $country = $country->update(
            [
                'is_active' => $country->is_active == 'مفعل' ? 0 : 1
            ]
        );
        return $country;
    }

    /////////// Update Governrate status
    public function UpdateGovernrateStatus($governrate)
    {
        $governrate = $governrate->update(
            [
                'is_active' => $governrate->is_active == 'مفعل' ? 0 : 1
            ]
        );
        //dd($governrate);
        return $governrate;
    }

    ########## Change Governrate Price
    public function GovernrateChangePrice($governrate)
    {
        $shippingGovernrate = $governrate->ShippingPrice;
        if(!$shippingGovernrate){
            $shippingGovernrate = new ShippingGovernrate();
            $shippingGovernrate->create([
                'price'=>request()->price,
                'governrate_id' => $governrate->id,
            ]);
        }
        $shippingGovernrate->update(
            [
                'price' => request()->price
            ]
        );
        return $shippingGovernrate;
    }
}
