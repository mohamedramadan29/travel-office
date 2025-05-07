<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\admin\Product;
use Livewire\WithFileUploads;
use App\Models\admin\Attribute;
use App\Http\Utils\Imagemanager;
use App\Models\admin\ProductTag;
use App\Models\admin\ProductImage;
use App\Models\admin\ProductVartiant;
use App\Models\admin\VartiantAttribute;

// use Livewire\Features\SupportFileUploads\WithFileUploads;

class CreateProduct extends Component
{
    use WithFileUploads;
    ########## Variable in Form
    // public $name, $description, $price, $qty;

    public $categories, $brands;
    public $currentStep = 1;
    public $successMessage = '';
    ######### Variables In Form

    ## Step one Variables
    public $name_ar, $name_en, $description_ar, $description_en,
    $small_desc_ar, $small_desc_en, $category_id, $brand_id, $sku, $available_for, $tags;


    #### Step Two Variables
    public $has_variant = 0, $manage_stock = 0, $has_discount = 0;
    public $price, $qty, $discount, $start_discount, $end_discount;
    ######## Step Three Variables
    public $images;
    public $fullScreenImages = '';

    ####### Variables In Form
    public $prices = [], $quantities = [], $attributeValues = [];
    public $ValuerowCount = 1;
    public function mount($categories, $brands)
    {
        $this->categories = $categories;
        $this->brands = $brands;
    }


    protected function rules()
    {
        return [
            'name_ar' => ['required', 'string', 'min:5'],
            'name_en' => ['required', 'string', 'min:5'],
            'description_ar' => ['required', 'string', 'max:1000'],
            'description_en' => ['required', 'string', 'max:1000'],
            'small_desc_ar' => ['required', 'string', 'max:150'],
            'small_desc_en' => ['required', 'string', 'max:150'],
            'sku' => ['required', 'string', 'max:30'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
            'tags' => ['required', 'string'],
            'available_for' => ['required', 'date'],
        ];
    }

    ######## Delete Image Before Upload

    public function deleteImage($key)
    {
        unset($this->images[$key]);
    }

    public function showFullScreen($key)
    {
        $this->fullScreenImages = $this->images[$key]->temporaryUrl();
        $this->dispatch('showFullScreenModel');
    }

    ######### Live Validate
    public function updated()
    {
        $this->validate();
    }

    public function firststep()
    {
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
            'tags' => ['required', 'string'],
            'available_for' => ['required', 'date'],
        ]);
        $this->currentStep = 2;
    }

    public function secondStepSubmit()
    {
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
                'attributeValues' => ['required', 'array', 'min:1'],
                'prices.*' => ['required', 'numeric', 'min:1', 'max:1000000'],
                'quantities.*' => ['required', 'numeric', 'min:1', 'max:1000000'],
                'attributeValues.*' => ['required', 'array'],
                'attributeValues.*.*' => ['required', 'integer', 'exists:attribute_values,id'],
            ];
        }
        $this->validate($data);
        $this->currentStep = 3;
    }

    public function addvartant()
    {
        $this->prices[] = null;
        $this->quantities[] = null;
        $this->attributeValues[] = [];
        $this->ValuerowCount = count($this->prices);
        // $this->ValuerowCount++;
    }
    public function removevartant()
    {
        if ($this->ValuerowCount > 1) {
            $this->ValuerowCount--;
            array_pop($this->prices);
            array_pop($this->quantities);
            array_pop($this->attributeValues);
        }
    }

    public function thirdStepSubmit()
    {
        $this->validate([
            'images' => ['required', 'array'],
        ]);
        $this->currentStep = 4;
    }
    public function backstep($newstep)
    {
        $this->currentStep = $newstep;
    }

    public function submitForm()
    {
        //   $product = new Product();
        ### Create Simple Product
        $product = Product::create([
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
        ######## Create Variants
        if ($this->has_variant == 1) {
            foreach ($this->prices as $index => $price) {
                $vartiant = ProductVartiant::create([
                    'product_id' => $product->id,
                    'price' => $price,
                    'stock' => $this->quantities[$index] ?? 0,
                ]);
                foreach ($this->attributeValues[$index] as $attributeValueId) {
                    VartiantAttribute::create([
                        'product_vartiant_id' => $vartiant->id,
                        'attribute_value_id' => $attributeValueId
                    ]);
                }
            }
        }
        ######## Store Images

        $imageManager = new Imagemanager();
        $imageManager->UploadImages($this->images, $product, 'products');

        ######## Store Tags

        $this->resetExcept([
            'categories','brands','successMessage'
        ]);

        $this->successMessage = ' تم اضافة المنتج بنجاح  ';
        // $this->reset();
        $this->currentStep = 1;

    }

    public function render()
    {
        $attributes = Attribute::with('attributeValues')->get();
        return view(
            'livewire.dashboard.create-product',
            [
                'attributes' => $attributes,
            ]
        );
    }

}
