<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasTranslations, HasFactory;

    protected $table = 'products';
    public $translatable = ['name', 'small_desc', 'description', 'meta_title', 'meta_desc', 'meta_keywords'];
    protected $guarded = [];
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    #############################
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    ###########################
    // public function ProductImages()
    // {
    //     return $this->hasMany(ProductImage::class);
    // }
    #########################
    public function ProductPreviews()
    {
        return $this->hasMany(ProductPreview::class);
    }
    #################
    public function images(){
        return $this->hasMany(ProductImage::class,'product_id');
    }
    #####################
    public function Tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags', 'product_id', 'tag_id');
    }
    #######################
    public function Vartians()
    {
        return $this->hasMany(ProductVartiant::class, 'product_id');
    }

    ####################
    public function IsSimple()
    {
        return !$this->has_variant;
    }
    ####################
    public function getCreatedAtAttribute($value)
    {
        return date('d/m/Y h:i A', strtotime($value));
    }
    ##################
    public function getUpdatedAtAttribute($value)
    {
        return date('d/m/Y h:i A', strtotime($value));
    }

    public function hasVariantTranslated(){
        return $this->has_variant == 1 ? 'متغير' : 'بسيط ';
    }


    public function getProductStatus(){
        return $this->status == 1 ? 'مفعل' : 'غير مفعل';
    }

    public function getPriceAttribute($value){
        return $this->has_variant == 0 ? $value : ' منتج متغير  ';
    }

    public function getQtyAttribute($value){
        return $this->has_variant == 0 ? $value : ' منتج متغير  ';
    }

    public function scopeActive($query){
        return $query->where('status',1);
    }

    public function getPriceAfterDiscount(){
        if ($this->has_discount && $this->discount > 0) {
            return number_format($this->getRawOriginal('price') - $this->discount, 2);
        }
        return number_format($this->getRawOriginal('price'), 2);
    }

}
