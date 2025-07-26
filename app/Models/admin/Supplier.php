<?php

namespace App\Models\admin;

use App\Models\admin\PurcheInvoice;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\SupplierTransaction;

class Supplier extends Model
{
    protected $guarded = [];

    public function getStatusAttribute($value){
        return $value == 1 ? 'نشط' : 'غير نشط';
    }
    public function scopeActive($query){
        return $query->where('status', 1);
    }
    public function transactions(){
        return $this->hasMany(SupplierTransaction::class);
    }
    public function purche_invoices(){
        return $this->hasMany(PurcheInvoice::class);
    }
    public function balance()
    {
        return $this->transactions()->sum('amount * (type = "credit" ? 1 : -1)');
    }
}
