<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Http\Services\Front\HomeService;
use Illuminate\Http\Request;

class BrandsController extends Controller
{
    protected $homeservice;

    public function __construct(HomeService $homeservice)
    {
        $this->homeservice = $homeservice;
    }

    public function index(){
        $brands = $this->homeservice->getBrands();
        return view('front.brands',compact('brands'));
    }

    public function GetProductsByBrand($slug){
        $products = $this->homeservice->getProductsByBrand($slug);
        return view('front.products',compact('products'));
    }
}
