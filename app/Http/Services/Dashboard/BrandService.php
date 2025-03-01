<?php
namespace App\Http\Services\Dashboard;

use App\Http\Utils\Imagemanager;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Repositories\Dashboard\BrandRepository;

class BrandService
{
    protected $brandRepository, $imageManager;
    public function __construct(BrandRepository $brandRepository, Imagemanager $imagemanager)
    {
        $this->brandRepository = $brandRepository;
        $this->imageManager = $imagemanager;
    }

    ########## Get All Brands ##########
    public function getBrands()
    {

        $brands = $this->brandRepository->getBrands();

        return DataTables::of($brands)
            ->addIndexColumn()
            ->addColumn('name', function ($brand) {
                return $brand->getTranslation('name', app()->getLocale());
            })
            ->addColumn('status', function ($brand) {
                return $brand->getStatus();
            })
            ->addColumn('logo', function ($brand) {
                return view('admin.brands.datatables.logo', compact('brand'));
            })
            ->addColumn('products_count', function ($brand) {
                return $brand->products_count == 0 ? 'لا يوجد' : $brand->products_count;
            })
            ->addColumn('action', function ($brand) {
                return view('admin.brands.datatables.actions', compact('brand'));
            })
            ->rawColumns(['action', 'logo']) // الموضوع دا بستخدمة لو همرر html كود علشان يظهر بشكل صحيح
            ->make(true);

    }

    ######## Get Brand

    public function getBrand($id)
    {
        $brand = $this->brandRepository->getBrand($id);
        if (!$brand) {
            abort(404);
        }
        return $brand;
    }

    #################### Store Brand ########################

    public function createBrand($data)
    {
        if ($data['logo'] != null) {
            $file_name = $this->imageManager->UploadSingleImage('/', $data['logo'], 'brands');
            $data['logo'] = $file_name;
        }
        $this->CashBrand();
        $brand = $this->brandRepository->createBrand($data);
        return $brand;
    }

    ############# Update Brand

    public function updateBrand($id, $data)
    {
        $brand = self::getBrand($id);
        if (!$brand) {
            abort(404);
        }
        if($data['logo'] !=null){
            ###### Delete Old Logo
            $this->imageManager->deleteImageFromLocal($brand->logo);
            $filename = $this->imageManager->UploadSingleImage('/', $data['logo'],'brands');
            $data['logo'] = $filename;
        }
        $brand = $this->brandRepository->updateBrand($brand, $data);
        return $brand;
    }

    public function deleteBrand($id)
    {
        $brand = $this->getBrand($id);
        ####### Check If Has Logo
        if ($brand->logo != null) {
            $this->imageManager->deleteImageFromLocal($brand->logo);
        }
        $brand =  $this->brandRepository->deleteBrand($brand);
        $this->CashBrand();
        return $brand;
    }

    public function CashBrand(){
        Cache::forget('BrandCount');
    }
}

?>
