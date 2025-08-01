<?php

namespace App\Http\Controllers\dashboard;

use App\Models\admin\Client;
use Illuminate\Http\Request;
use App\Models\admin\Supplier;
use App\Http\Controllers\Controller;
use App\Models\admin\PurcheInvoice;
use App\Models\admin\SaleInvoice;

class WelcomeController extends Controller
{
    public function index(){
        $suppliers = Supplier::count();
        $clients = Client::count();
        $purchases = PurcheInvoice::count();
        $sales = SaleInvoice::count();
      return view("admin.welcome",compact("suppliers","clients","purchases","sales"));
    }
}
