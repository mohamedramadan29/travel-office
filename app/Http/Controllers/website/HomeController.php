<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Http\Services\Front\HomeService;
use App\Models\admin\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    protected $homeService;
    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }
    public function index()
    {
        $sliders = $this->homeService->getSliders();
        $categories = $this->homeService->getCategories(8);
        $products = $this->homeService->getProducts();
        $brands = $this->homeService->getBrands(8);
        $newArrivalsProducts = $this->homeService->newArrivalsProducts(8);
        $flashProducts = $this->homeService->getFlashProducts(8);
        $flashProductsWithTimer = $this->homeService->getFlashProductsWithTimer(8);
        return view('front.index',
        compact('sliders','categories','products','brands','newArrivalsProducts','flashProducts','flashProductsWithTimer'));
    }
}
