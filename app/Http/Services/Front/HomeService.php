<?php
namespace App\Http\Services\Front;

use App\Models\admin\Brand;
use App\Models\admin\Slider;
use App\Models\admin\Product;
use App\Models\admin\Category;

class HomeService{


    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function getSliders(){
        $sliders = Slider::all();
        return $sliders;
    }
    public function getCategories($limit = null){
        if($limit){
            $categories = Category::active()->latest()->take($limit)->get();
        }else{
            $categories = Category::active()->latest()->get();
        }
        return $categories;

    }
    public function getProducts(){
        $products = Product::latest()->take(8)->get();
        return $products;
    }
    public function getBrands($limit = null){
        if($limit){
            $brands = Brand::active()->latest()->take($limit)->get();
        }else{
            $brands = Brand::active()->latest()->get();
        }
        return $brands;
    }

    public function getProductsByCategory($slug){
        $category = Category::where('slug',$slug)->first();
        $products = Product::with('brand','category','images')
        ->where('category_id',$category->id)
        ->active()
        ->select('id','name','slug','price','category_id','brand_id','has_variant','price','has_discount','discount')
        ->latest()
        ->paginate(2);
        return $products;
    }
    public function getProductsByBrand($slug){
        $brand = Brand::where('slug',$slug)->first();
        $products = Product::active()->with('brand','category','images')
        ->where('brand_id',$brand->id)
        ->select('id','name','slug','price','category_id','brand_id','has_variant','price','has_discount','discount')
        ->latest()
        ->paginate(2);
        return $products;
    }

    public function newArrivalsProducts($limit = null ){

        $products = $this->productService->getNewArrivalsProducts($limit);
        return $products;

    }

    public function getFlashProducts($limit =null){
        $products = $this->productService->getFlashProducts($limit);
        return $products;
    }

    public function getFlashProductsWithTimer($limit =null){
        $products = $this->productService->getFlashProductsWithTimer($limit);
        return $products;
    }

}




?>
