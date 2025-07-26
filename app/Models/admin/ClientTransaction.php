<?php

namespace App\Models\admin;

use App\Models\admin\Client;
use App\Models\admin\SaleInvoice;
use App\Models\admin\Safe;
use Illuminate\Database\Eloquent\Model;

class ClientTransaction extends Model
{
    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function saleInvoice()
    {
        return $this->belongsTo(SaleInvoice::class);
    }

    public function safe()
    {
        return $this->belongsTo(Safe::class);
    }
}
