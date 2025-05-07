<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Http\Utils\Imagemanager;
use App\Models\admin\ProductVartiant;
use App\Models\admin\VartiantAttribute;
use App\Http\Services\Dashboard\ProductService;
use App\Models\admin\ProductImage;
use Illuminate\Support\Facades\Session;

class EditProduct extends Component
{

    use WithFileUploads;
    public $product;
    protected ProductService $productService;

    public $productId, $ProductAttributes = [], $categories, $brands;

    ################
    public $successMessage = '', $currentStep = 1;
    ######### Start First Step

    public $name_ar, $name_en, $small_desc_ar, $small_desc_en, $description_ar, $description_en, $category_id, $brand_id, $sku, $available_for;

    ######### End First Step
    ######### Start Second Step
    public $has_variant, $has_discount, $manage_stock, $qty, $price, $discount, $start_discount, $end_discount;
    public $variants, $prices, $quantities, $vartiantAttributes = [], $ValuerowCount = 1;
    ######### End Second Step

    ######## Step Three Variables
    public $images, $newImages;
    public $fullScreenImages = '';

    public function boot(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function mount($productId, $ProductAttributes, $categories, $brands)
    {
        $this->productId = $productId;
        $this->product = $this->productService->getProductWithIgerloading($productId);
        $this->ProductAttributes = $ProductAttributes;
        $this->categories = $categories;
        $this->brands = $brands;
        ######## Variable First Step
        $this->name_ar = $this->product->getTranslation('name', 'ar');
        $this->name_en = $this->product->getTranslation('name', 'en');
        $this->small_desc_ar = $this->product->getTranslation('small_desc', 'ar');
        $this->small_desc_en = $this->product->getTranslation('small_desc', 'en');
        $this->description_ar = $this->product->getTranslation('description', 'ar');
        $this->description_en = $this->product->getTranslation('description', 'en');
        $this->category_id = $this->product->category_id;
        $this->brand_id = $this->product->brand_id;
        $this->sku = $this->product->sku;
        $this->available_for = $this->product->available_for;
        ######### Variable Second Step
        $this->has_variant = $this->product->has_variant;
        $this->has_discount = $this->product->has_discount;
        $this->manage_stock = $this->product->manage_stock;
        $this->qty = $this->product->qty;
        $this->price = $this->product->price;
        $this->discount = $this->product->discount;
        $this->start_discount = $this->product->start_discount;
        $this->end_discount = $this->product->end_discount;
        $this->images = $this->product->images;
        #############
        if ($this->has_variant == 1) {
            $this->variants = $this->product->Vartians;
            $this->ValuerowCount = count($this->variants);
            foreach ($this->variants as $key => $variant) {
                $this->prices[$key] = $variant->price;
                $this->quantities[$key] = $variant->stock;
                foreach ($variant->VartiantAttributes as $Vartiantattribute) {
                    $this->vartiantAttributes[$key][$Vartiantattribute->attributeValue->attribute_id] = $Vartiantattribute->attribute_value_id;
                }
            }
            //   dd($this->vartiantAttributes);
        }

    }

    public function firststep()
    {
        ### validate inputs
        $this->validate([
            'name_ar' => ['required', 'string', 'min:5'],
            'name_en' => ['required', 'string', 'min:5'],
            'description_ar' => ['required', 'string', 'max:1000'],
            'description_en' => ['required', 'string', 'max:1000'],
            'small_desc_ar' => ['required', 'string', 'max:150'],
            'small_desc_en' => ['required', 'string', 'max:150'],
            'sku' => ['required', 'string', 'max:30'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
           // 'tags' => ['required', 'string'],
            'available_for' => ['required', 'date'],
        ]);
        $this->currentStep = 2;
    }
    public function secondStepSubmit()
    {
        ##### Validate Input
        $data = [
            'has_variant' => ['required', 'in:0,1'],
            'manage_stock' => ['required', 'in:0,1'],
            'has_discount' => ['required', 'in:0,1'],
        ];
        if ($this->has_variant == 0) {
            $data = [
                'price' => ['required', 'numeric', 'min:1', 'max:1000000'],
            ];
        }
        if ($this->manage_stock == 1) {
            $data = [
                'qty' => ['required', 'numeric', 'min:1', 'max:1000000'],
            ];
        }
        if ($this->has_discount == 1) {
            $data = [
                'discount' => ['required', 'numeric', 'min:1', 'max:100'],
                'start_discount' => ['date', 'before:end_discount'],
                'end_discount' => ['date', 'after:start_discount'],
            ];
        }
        if ($this->has_variant == 1) {
            $data = [
                'prices' => ['required', 'array', 'min:1'],
                'quantities' => ['required', 'array', 'min:1'],
                'vartiantAttributes' => ['required', 'array', 'min:1'],
                'prices.*' => ['required', 'numeric', 'min:1', 'max:1000000'],
                'quantities.*' => ['required', 'numeric', 'min:1', 'max:1000000'],
                'vartiantAttributes.*' => ['required', 'array'],
                'vartiantAttributes.*.*' => ['required', 'integer', 'exists:attribute_values,id'],
            ];
        }
        $this->validate($data);
        $this->currentStep = 3;
    }
    public function backstep($step)
    {
        $this->currentStep = $step;
    }


    public function addvartant()
    {
        $this->prices[] = null;
        $this->quantities[] = null;
        $this->vartiantAttributes[] = [];
        $this->ValuerowCount = count($this->prices); // Keep count synchronized

    }
    public function removevartant()
    {
        if ($this->ValuerowCount > 1) {
            $this->ValuerowCount--;
            array_pop($this->prices);
            array_pop($this->quantities);
            array_pop($this->vartiantAttributes);
        }
    }

    ############ Start Third Step
    public function showFullScreen($key)
    {
        $this->fullScreenImages = $this->images[$key]->temporaryUrl();
        $this->dispatch('showFullScreenModel');
    }

    public function deleteImage($imageId,$key,$file_name)
    {
        #### Delete Image From Local And DB
        $imagemanager = new Imagemanager();
        $imagemanager->deleteImageFromLocal('uploads/products/'.$file_name);
        ######## Delete Image From DB
        ProductImage::find($imageId)->delete();

        unset($this->images[$key]);
       // dd($imageId);
    }
    public function deleteNewImage($key)
    {
        unset($this->newImages[$key]);
    }
    public function thirdStepSubmit()
    {
        $this->validate([
            'images' => ['required', 'array'],
        ]);
        $this->currentStep = 4;
    }


    public function submitForm()
    {
        //   $product = new Product();
        ### Update Product
        $this->product->update([
            'name' => ['ar' => $this->name_ar, 'en' => $this->name_en],
            'slug' => Str::slug($this->name_en),
            'description' => ['ar' => $this->description_ar, 'en' => $this->description_en],
            'small_desc' => ['ar' => $this->small_desc_ar, 'en' => $this->small_desc_en],
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'sku' => $this->sku,
            'available_for' => $this->available_for,
            //'tags' => $this->tags,
            'has_variant' => $this->has_variant,
            'price' => $this->has_variant == 1 ? null : $this->price,
            'manage_stock' => $this->manage_stock,
            'qty' => $this->manage_stock == 0 ? null : $this->qty,
            'has_discount' => $this->has_discount,
            'discount' => $this->has_discount == 0 ? null : $this->discount,
            'start_discount' => $this->has_discount == 0 ? null : $this->start_discount,
            'end_discount' => $this->has_discount == 0 ? null : $this->end_discount,
            'views' => 0,
        ]);
        ########  Update Vartiants

        if ($this->has_variant == 1) {
            ####### Delete Old Vartiants
            ProductVartiant::where('product_id', $this->product->id)->delete();
            foreach ($this->prices as $index => $price) {
                $vartiant = ProductVartiant::create([
                    'product_id' => $this->product->id,
                    'price' => $price,
                    'stock' => $this->quantities[$index] ?? 0,
                ]);
                foreach ($this->vartiantAttributes[$index] as $attributeValueId) {
                    VartiantAttribute::create([
                        'product_vartiant_id' => $vartiant->id,
                        'attribute_value_id' => $attributeValueId
                    ]);
                }
            }
        }
        ######## Store Images

        if($this->newImages){
            $imageManager = new Imagemanager();
            $imageManager->UploadImages($this->newImages, $this->product, 'products');

        }
        ######## Store Tags

        Session::flash('Success_message', ' تم تعديل المنتج بنجاح  ');

        return redirect()->route('dashboard.products.index');
        // $this->resetExcept([
        //     'categories',
        //     'brands',
        //     'successMessage'
        // ]);

        // $this->successMessage = ' تم تعديل المنتج بنجاح  ';
        // // $this->reset();
        // $this->currentStep = 1;

    }



    public function render()
    {
        return view('livewire.dashboard.edit-product');
    }
}
