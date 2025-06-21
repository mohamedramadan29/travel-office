<?php
namespace App\Http\Services\Front;

use App\Models\admin\Product;

class ProductService{
    public function getProductBySlug($slug){
        $product = Product::
        with('brand','category','images')
        ->where('slug',$slug)
        ->active()
        ->first();
        return $product;
    }

    public function getNewArrivalsProducts($limit = null){
       $products = Product::with('brand','category','images')
       ->active()
       ->select('id','name','slug','price','category_id','brand_id','has_variant','price','has_discount')
       ->latest()
       ->paginate($limit);
       return $products;
    }

    public function getFlashProducts($limit = null){
        $products = Product::with('brand','category','images')
       ->active()
       ->where('has_discount',1)
       ->select('id','name','slug','price','category_id','brand_id','has_variant','price','has_discount')
       ->latest()
       ->paginate($limit);
       return $products;
    }
    public function getFlashProductsWithTimer($limit = null){
        $products = Product::with('brand','category','images')
        ->active()
        ->where('available_for',date('Y-m-d'))->whereNotNull('available_for')
        ->where('has_discount',1)
        ->select('id','name','slug','price','category_id','brand_id','has_variant','price','has_discount')
        ->latest()
        ->paginate($limit);
        return $products;
    }
}
