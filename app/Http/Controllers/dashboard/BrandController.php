<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Traits\Message_Trait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Http\Services\Dashboard\BrandService;

class BrandController extends Controller
{
    use Message_Trait;

    protected $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }
    public function index()
    {

        return view('admin.brands.index');
    }

    public function BrandsAll()
    {
        $brands = $this->brandService->getBrands();
        return $brands;
    }


    public function store(BrandRequest $request)
    {
        $data = $request->only('name', 'logo', 'status');
        if (!$this->brandService->createBrand($data)) {
            return $this->Error_message(' حدث خطا من فضلك حاول مرة اخري  ');
        }
        return $this->success_message(' تم اضافة العلامة التجارية بنجاح ');

    }


    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $brand = $this->brandService->getBrand($id);
        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, string $id)
    {
        $data = $request->only('name', 'status', 'logo', 'id');
        $brand = $this->brandService->updateBrand($id, $data);
        if (!$brand) {
            return $this->Error_message('لم يتم تعديل العلامة التجارية بنجاح ');
        }
        return $this->success_message('تم تعديل العلامة التجارية بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!$this->brandService->deleteBrand($id)) {
            return $this->Error_message(' لم يتم الحذف بنجاح ، الرجاء المحاولة مرة اخرى ');
        }
        return $this->Success_message('تم الحذف بنجاح');
    }
}
