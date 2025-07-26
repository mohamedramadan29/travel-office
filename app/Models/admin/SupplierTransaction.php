<?php

namespace App\Models\admin;

use App\Models\admin\Supplier;
use App\Models\admin\PurcheInvoice;
use App\Models\admin\Safe;
use Illuminate\Database\Eloquent\Model;

class SupplierTransaction extends Model
{
    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseInvoice()
    {
        return $this->belongsTo(PurcheInvoice::class);
    }

    public function safe()
    {
        return $this->belongsTo(Safe::class);
    }
}
