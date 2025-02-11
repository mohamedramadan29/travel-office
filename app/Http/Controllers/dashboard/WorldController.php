<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Services\Dashboard\WorldService;
use App\Http\Traits\Message_Trait;
use Illuminate\Http\Request;

class WorldController extends Controller
{
    use Message_Trait;

    protected $worldService;
    public function __construct(WorldService $worldService)
    {
        $this->worldService = $worldService;
    }
    ######### Get All Countries
    public function AllCountry()
    {
       // return request();
        $countries = $this->worldService->AllCountry();
        return view('admin.world.countries', compact('countries'));
    }
    ####### Get The Governorate By Country ID
    public function GovernorateByCountry($country_id)
    {
        $governorates = $this->worldService->GovernorateByCountry($country_id);
        return view('admin.world.governrates', compact('governorates'));
    }

    ########### Get Citizen By Governorate ID

    public function GovernrateCitizen($governrate_id)
    {
        $citizens = $this->worldService->GovernrateCitizen($governrate_id);
        return view('admin.world.citizens', compact('citizens'));
    }

    public function CountryByID($id)
    {
        $country = $this->worldService->CountryByID($id);

    }

    public function GovernorateByID($id){
        $governrate = $this->worldService->GovernorateByID($id);
    }



    public function UpdateStatus($country_id)
    {

        $country = $this->worldService->UpdateStatus($country_id);
        if (!$country) {
            return response()->json(['status' => false, 'message' => ' لم يتم تغيير حالة الدولة ']);
        } else {
            $country = $this->worldService->CountryByID($country_id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => ' تم تغيير حالة الدولة ',
                    'data' => $country
                ],
                200
            );
        }
    }

    ############## Update Governrate Status
    public function UpdateGovernrateStatus($governrate_id)
    {
        $governrate = $this->worldService->UpdateGovernrateStatus($governrate_id);
        //dd($governrate);
        if (!$governrate) {
            return response()->json(['status' => false, 'message' => ' لم يتم تغيير حالة المحافظة ']);
        } else {
            $governrate = $this->worldService->GovernorateByID($governrate_id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => ' تم تغيير حالة المحافظة ',
                    'data' => $governrate
                ],
                200
            );
        }
    }

    ############### Change Governrate Price

    public function GovernrateChangePrice($governrate_id)
    {
        $governrate = $this->worldService->GovernrateChangePrice($governrate_id);
        if (!$governrate) {
            return response()->json(['status' => false, 'message' => ' لم يتم تغيير سعر المحافظة ']);
        } else {
            return $this->success_message( 'تم تغيير سعر المحافظة بنجاح');
            // $governrate = $this->worldService->GovernorateByID($governrate_id);
            // return response()->json(
            //     [
            //         'status' => 'success',
            //         'message' => ' تم تغيير سعر المحافظة ',
            //         'data' => $governrate
            //     ],
            //     200
            // );
        }
    }
}
