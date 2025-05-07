<?php
namespace App\Http\Repositories\Dashboard;

use App\Models\admin\Product;
use Illuminate\Support\Facades\Request;

class ProductRepository
{


    public function getProducts()
    {
        $products = Product::latest()->get();
        return $products;
    }
    public function getProduct($id)
    {
        $product = Product::find($id);
        return $product;
    }

    public function getProductWithIgerloading($id)
    {
        return Product::with('images','Vartians.vartiantAttributes')->find($id);
    }

    public function ChangeStatus($product, $status)
    {
        $product->status = $status;
        return $product->save();
    }

    public function deleteProduct($product)
    {
        return $product->delete();
    }
}
