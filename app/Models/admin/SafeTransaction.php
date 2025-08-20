<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class SafeTransaction extends Model
{
    protected $guarded = [];

    ##################

    public function safe(){
        return $this->belongsTo(Safe::class,'safe_id');
    }

    ################

    public function client(){
        return $this->belongsTo(Client::class,'client_id');
    }

    ###############

    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id');
    }
    ################

    public function SaleInvoice(){
        return $this->belongsTo(SaleInvoice::class,'sale_invoice_id');
    }

    ###############

    public function PurcheInvoice(){
        return $this->belongsTo(PurcheInvoice::class,'purchase_invoice_id');
    }

    ###################



}
