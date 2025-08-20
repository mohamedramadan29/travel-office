<?php

namespace App\Http\Controllers\dashboard;

use App\Models\admin\Safe;
use App\Models\admin\Client;
use Illuminate\Http\Request;
use App\Models\admin\Category;
use App\Models\admin\Supplier;
use App\Models\admin\SaleInvoice;
use App\Http\Controllers\Controller;
use App\Models\admin\SaleInvoiceReturn;

class SellingInvoicesReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = SaleInvoiceReturn::orderBy('id','Desc')->paginate(10);
        return view('admin.invoices.selling-returns.index',compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $selling_invoice = SaleInvoice::findOrFail($id);
        $suppliers = Supplier::active()->get();
        $safes = Safe::active()->get();
        $categories = Category::active()->get();
        $clients = Client::active()->get();
        $invoice = SaleInvoiceReturn::findOrFail($id);
        return view('admin.invoices.selling-returns.view',compact('invoice','selling_invoice','suppliers','safes','categories','clients'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
