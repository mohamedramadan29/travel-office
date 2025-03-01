<?php
namespace App\Http\Repositories\Dashboard;

use App\Models\admin\Brand;

class BrandRepository
{

    ###### Get All Brands

    public function getBrands()
    {
        $brands = Brand::withCount('Products')->latest()->get();
        return $brands;
    }

    ######### Get Brand

    public function getBrand($id)
    {

        $brand = Brand::find($id);
        return $brand;

    }

    ############## Store Brand ####################

    public function createBrand($data)
    {
        $brand = Brand::create($data);
        return $brand;
    }

    ############ Update Brand
    public function updateBrand($brand, $data)
    {
        $brand = $brand->update($data);
        return $brand;
    }

    public function deleteBrand($brand)
    {
        return $brand->delete();
    }


}
