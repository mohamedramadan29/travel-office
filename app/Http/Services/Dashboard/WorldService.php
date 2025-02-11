<?php
namespace App\Http\Services\Dashboard;

use App\Http\Repositories\Dashboard\WorldRepository;


class WorldService
{
    ####### World Repository Construct
    protected $worldRepository;

    public function __construct(WorldRepository $worldRepository)
    {
        $this->worldRepository = $worldRepository;
    }
    ######### Get The Country
    public function CountryByID($id)
    {
        $country = $this->worldRepository->CountryByID($id);
        if (!$country) {
            abort(404);
        }
        return $country;
    }
    ######## Get The Governorate
    public function GovernorateByID($id)
    {
        $governrate = $this->worldRepository->GovernrateByID($id);
        //dd($governrate);
        if (!$governrate) {
            abort(404);
        }
        return $governrate;
    }

    ####### Get All Country
    public function AllCountry()
    {
        $countries = $this->worldRepository->AllCountry();
        return $countries;
    }
    ########### Get All Governorate
    public function GovernorateByCountry($country_id)
    {
        $country = self::CountryByID($country_id);
        $governorates = $this->worldRepository->GovernorateByCountry($country);
        return $governorates;
    }

    ############ Get All Citizen in Governorate
    public function GovernrateCitizen($governrate_id)
    {
        $governrate = self::GovernorateByID($governrate_id);
        $citizens = $this->worldRepository->GovernrateCitizen($governrate);
        return $citizens;
    }


    public function UpdateStatus($country_id)
    {
        $country = $this->CountryByID($country_id);
        $country = $this->worldRepository->UpdateStatus($country);
        if (!$country) {
            return false;
        } else {
            return true;
        }
    }

    ########### Update Governrate status
    public function UpdateGovernrateStatus($governrate_id)
    {
        $governrate = $this->GovernorateByID($governrate_id);
        $governrate = $this->worldRepository->UpdateGovernrateStatus($governrate);
        if (!$governrate) {
            return false;
        } else {
            return true;
        }
    }

    ########### Change Governrate Price
    public function GovernrateChangePrice($governrate_id)
    {
        $governrate = $this->GovernorateByID($governrate_id);
        $governrate = $this->worldRepository->GovernrateChangePrice($governrate);
        if (!$governrate) {
            return false;
        } else {
            return true;
        }
    }
}
