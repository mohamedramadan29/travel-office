<?php

namespace App\Http\Controllers\dashboard;

use App\Models\admin\Brand;
use Illuminate\Http\Request;
use App\Models\admin\Product;
use App\Models\admin\Category;
use App\Http\Traits\Message_Trait;
use App\Http\Controllers\Controller;
use App\Models\admin\ProductVartiant;
use App\Http\Services\Dashboard\BrandService;
use App\Http\Services\Dashboard\ProductService;
use App\Http\Services\Dashboard\AttributeService;
use App\Http\Services\Dashboard\CategoriesService;

class ProductController extends Controller
{
    use Message_Trait;

    protected $productService;
    protected $categoriesService;
    protected $brandService;
    protected $attributeService;
    public function __construct(ProductService $productService,CategoriesService $categoriesService,BrandService $brandService,AttributeService $attributeService)
    {
        $this->productService = $productService;
        $this->categoriesService = $categoriesService;
        $this->brandService = $brandService;
        $this->attributeService = $attributeService;

    }
    public function index()
    {
        return view('admin.products.index');
    }

    public function ProductAll()
    {
        return $this->productService->getProducts();
    }

    public function ChangeStatus(Request $request)
    {
        if ($this->productService->ChangeStatus($request)) {
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Status changed successfully.'
                ]
            );
        } else {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Failed to change status.'
                ]
            );
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->productService->getProduct($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $productId = $id;
        $categories  = $this->categoriesService->getAllServices();
        $brands = $this->brandService->getAll();
        $attributes = $this->attributeService->getAll();

        return view('admin.products.edit', compact('productId','attributes', 'categories', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if($this->productService->deleteProduct($id)){
            return $this->success_message('تم حذف المنتج بنجاح');
        }
    }

    public function DeleteVartiant($vartiant_id){
        $vartiant = ProductVartiant::find($vartiant_id);
        $product = $vartiant->product;
        if($product->vartians()->count() == 1){
            return $this->Error_message(' لا يمكن حذف اخر متغير في المنتج  ');
        }
        $vartiant->delete();
        return $this->success_message('تم حذف المتغير بنجاح');

    }
}
