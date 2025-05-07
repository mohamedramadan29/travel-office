<?php
namespace App\Http\Services\Dashboard;

use App\Models\admin\Product;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Repositories\Dashboard\ProductRepository;

class ProductService
{
    protected $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getProducts()
    {
        $products = $this->productRepository->getProducts();
        return DataTables::of($products)
            ->addIndexColumn()
            ->addColumn('name', function ($product) {
                return $product->getTranslation('name', app()->getLocale());
            })
            ->addColumn('has_variant', function ($product) {
                return $product->hasVariantTranslated();
            })
            ->addColumn('images', function ($product) {
                return view('admin.products.datatables.images', compact('product'));

            })
            ->addColumn('status', function ($product) {
                return $product->getProductStatus();
            })
            ->addColumn('sku', function ($product) {
                return $product->sku;
            })
            ->addColumn('available_for', function ($product) {
                return $product->available_for;
            })

            ->addColumn('category', function ($product) {
                return $product->category->name;
            })
            ->addColumn('brand', function ($product) {
                return $product->brand->name;
            })
            ->addColumn('price', content: function ($product) {
                return $product->price;
            })
            ->addColumn('qty', content: function ($product) {
                return $product->qty;
            })
            ->addColumn('action', function ($product) {
                return view('admin.products.datatables.actions', compact('product'));
            })
            ->rawColumns(['action', 'logo']) // الموضوع دا بستخدمة لو همرر html كود علشان يظهر بشكل صحيح
            ->make(true);
    }

    public function getProduct($id)
    {
        $product = $this->productRepository->getProduct($id);
        if (!$product) {
            abort(404);
        }
        return $product;
    }

    public function getProductWithIgerloading($id)
    {
        $product = $this->productRepository->getProductWithIgerloading($id);
        return $product??abort(404);
    }

    public function ChangeStatus($request)
    {
        $product = $this->getProduct($request->id);
        $product->status = 1 ? $status = 0 : $status = 1;
        return $this->productRepository->ChangeStatus($product, $status);
    }

    public function deleteProduct($id){
        $product = $this->getProduct($id);
        return $this->productRepository->deleteProduct($product);
    }
}
