<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class SaleInvoiceReturn extends Model
{
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
 public function client(){
     return $this->belongsTo(Client::class,'client_id');
 }
 public function transactions()
     {
         return $this->hasMany(ClientTransaction::class);
     }
}
