<?php

namespace App\Models\admin;

use App\Models\admin\SaleInvoice;
use Illuminate\Support\Facades\DB;
use App\Models\admin\ClientTransaction;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = [];

    public function getStatusAttribute($value){
        return $value == 1 ? 'نشط' : 'غير نشط';
    }
    public function scopeActive($query){
        return $query->where('status',1);
    }
    public function transactions(){
        return $this->hasMany(ClientTransaction::class);
    }
    public function sale_invoices(){
        return $this->hasMany(SaleInvoice::class);
    }
    // public function balance()
    // {
    //     return $this->transactions()->sum('amount * (type = "credit" ? -1 : 1)');
    // }
    public function balance()
    {
        return $this->transactions()->sum(DB::raw('CASE WHEN type = "credit" THEN -amount ELSE amount END'));
    }
}
