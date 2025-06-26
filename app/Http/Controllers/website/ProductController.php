<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Http\Services\Front\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function showProduct($slug){
        $product = $this->productService->getProductBySlug($slug);
        if(!$product){
         abort(404);
        }
        return view('front.show',compact('product'));
    }

    public function getProductsByType($type){
        if($type == 'new-arrivals'){
            $products = $this->productService->getNewArrivalsProducts();

        }elseif($type == 'flash-sale'){
            $products = $this->productService->getFlashProducts();

        }elseif($type == 'flash-sale-timer'){
            $products = $this->productService->getFlashProductsWithTimer();

        }else{
            abort(404);
        }
        return view('front.products',
        ['products'=>$products,
        'flashtimer'=>$type == 'flash-sale-timer'  ? 'true':'false',
    ]);
    }
}
