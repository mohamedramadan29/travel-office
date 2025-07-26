<?php

namespace App\Models\admin;

use App\Models\admin\Safe;
use App\Models\admin\Admin;
use App\Models\admin\Category;
use App\Models\admin\Supplier;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\SupplierTransaction;

class PurcheInvoice extends Model
{
    protected $table='purche_invoices';
    protected $guarded = [];

    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id');
    }
    public function admin(){
        return $this->belongsTo(Admin::class,'admin_id');
    }
    public function safe(){
        return $this->belongsTo(Safe::class,'safe_id');
    }
    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }
    public function transactions()
    {
        return $this->hasMany(SupplierTransaction::class);
    }
}
