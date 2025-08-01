<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\PurcheInvoice;
use App\Models\admin\PurcheInvoiceReturn;
use Illuminate\Http\Request;

class PurchesInvoiceReturnController extends Controller
{
    use Message_Trait;
    public function index()
    {
        $invoices = PurcheInvoiceReturn::orderBy('id','Desc')->paginate();
        return view('admin.invoices.purches-returns.index',compact('invoices'));
    }

    public function create()
    {


    }


    public function store(Request $request,$id)
    {

    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {

    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        $invoice = PurcheInvoiceReturn::findOrFail($id);
        $invoice->delete();
        return $this->success_message('تم حذف الفاتورة بنجاح');
    }
}
