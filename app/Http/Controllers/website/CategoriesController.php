<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Http\Services\Front\HomeService;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    protected $homeservices;

    public function __construct(HomeService $homeservices)
    {
        $this->homeservices = $homeservices;
    }

    public function index(){
        $categories = $this->homeservices->getCategories();
        return view('front.categories',compact('categories'));
    }
    public function GetProductsByCategory($slug){
        $products = $this->homeservices->getProductsByCategory($slug);
        return view('front.products',compact('products'));
    }
}
