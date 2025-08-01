<?php

namespace App\Http\Controllers\dashboard;

use App\Models\admin\Safe;
use App\Models\admin\Client;
use Illuminate\Http\Request;
use App\Models\admin\Category;
use App\Models\admin\Supplier;
use App\Models\admin\SaleInvoice;
use App\Http\Traits\Message_Trait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\admin\ClientTransaction;
use App\Models\admin\PurcheInvoice;
use App\Models\admin\SaleInvoiceReturn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SellingInvoicesController extends Controller
{
  use Message_Trait;
    public function index()
    {
        $invoices = SaleInvoice::orderBy('id','DESC')->paginate(10);
        return view('admin.invoices.selling.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::active()->get();
        $safes = Safe::active()->get();
        $categories = Category::active()->get();
        $clients = Client::active()->get();
        if($clients->count() == 0){
            return redirect()->route('dashboard.clients.create');
        }
        $purchesInvoices = PurcheInvoice::where('status','available')->where('type','فاتورة رسمية')->get();

        return view('admin.invoices.selling.create', compact('suppliers', 'safes','categories','clients','purchesInvoices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['admin_id'] = Auth::user()->id;
            $rules  = [
                'bayan_txt'=>'required',
                'referance_number'=>'required',
                'supplier_id' => 'required',
                'client_id'=>'required',
                'qyt' => 'required|numeric|min:1',
                'selling_price' => 'required|numeric|min:1',
                'total_price' => 'required|numeric|min:1',
                'paid' => 'required',
                'remaining' => 'required',
                'payment_method' => 'required',
                'safe_id' => 'required',
            ];
       $messages = [
        'bayan_txt.required'=>'البيان / الوصف مطلوب',
        'referance_number.required'=>'الرقم المرجعي مطلوب',
        'supplier_id.required'=>'المورد مطلوب',
        'client_id.required'=>'العميل مطلوب',
        'qyt.required'=>'الكمية مطلوبة',
        'selling_price.required'=>'سعر البيع مطلوب',
        'selling_price.numeric'=>'سعر البيع يجب أن يكون رقمًا',
        'selling_price.min'=>'سعر البيع يجب أن يكون 1 أو أكثر',
        'total_price.required'=>'السعر الكلي مطلوب',
        'total_price.numeric'=>'السعر الكلي يجب أن يكون رقمًا',
        'total_price.min'=>'السعر الكلي يجب أن يكون 1 أو أكثر',
        'paid.required'=>'المدفوع مطلوب',
        'remaining.required'=>'الباقي مطلوب',
        'payment_method.required'=>'طريقة الدفع مطلوبة',
        'safe_id.required'=>'الخزنة مطلوبة',
       ];
       $validator = Validator::make($data,$rules,$messages);
       if($validator->fails()){
           return redirect()->back()->withErrors($validator)->withInput();
       }
       try{
        DB::beginTransaction();
        $invoice = new SaleInvoice;
        $invoice->bayan_txt = $data['bayan_txt'];
        $invoice->referance_number = $data['referance_number'];
        $invoice->supplier_id = $data['supplier_id'];
        $invoice->client_id = $data['client_id'];
        $invoice->qyt = $data['qyt'];
        $invoice->selling_price = $data['selling_price'];
        $invoice->total_price = $data['total_price'];
        $invoice->paid = $data['paid'];
        $invoice->remaining = $data['remaining'];
        $invoice->payment_method = $data['payment_method'];
        $invoice->safe_id = $data['safe_id'];
        $invoice->category_id = $data['category_id'];
        $invoice->admin_id = Auth::user()->id;
        $invoice->save();
        $purchesInvoice = PurcheInvoice::where('referance_number', $data['referance_number'])->first();
        $purchesInvoice->status = 'sold';
        $purchesInvoice->save();
        ############################################# Add Transction To Client #############################
        if($data['remaining'] > 0) {
            ClientTransaction::create([
                'client_id' => $data['client_id'],
                'sale_invoice_id' => $invoice->id,
                'amount' => $data['remaining'],
                'type' => 'debit', // المبلغ المستحق من العميل  مدين
                'description' => 'مبلغ مستحق من فاتورة بيع #' . $invoice->id,
            ]);
        }
        if ($data['paid'] > 0) {
            ClientTransaction::create([
                'client_id' => $data['client_id'],
                'sale_invoice_id' => $invoice->id,
                'amount' => $data['paid'],
                'type' => 'credit', // المبلغ المدفوع من العميل  دائن
                'payment_method' => $data['payment_method'],
                'safe_id' => $data['safe_id'],
                'description' => 'دفعة لفاتورة بيع #' . $invoice->id,
            ]);
        }
        DB::commit();
        return $this->success_message(' تم اضافة فاتورة بيع بنجاح  ');
       }catch(\Exception $e){
        DB::rollBack();
        return $this->exception_message($e);
       }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoice = SaleInvoice::findOrFail($id);
        return view('admin.invoices.selling.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $selling_invoice = SaleInvoice::findOrFail($id);
        $suppliers = Supplier::active()->get();
        $safes = Safe::active()->get();
        $categories = Category::active()->get();
        $clients = Client::active()->get();
        return view('admin.invoices.selling.edit', compact('selling_invoice','suppliers','safes','categories','clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $data = $request->all();
        $invoice = SaleInvoice::findOrFail($id);
        $data['admin_id'] = Auth::user()->id;
            $rules = [
                'bayan_txt'=>'required',
                'referance_number'=>'required',
                'supplier_id' => 'required',
                'client_id'=>'required',
                'qyt' => 'required|numeric|min:1',
                'selling_price' => 'required|numeric|min:1',
                'total_price' => 'required|numeric|min:1',
                'paid' => 'required',
                'remaining' => 'required',
                'payment_method' => 'required',
                'safe_id' => 'required',
            ];

       $messages = [
        'bayan_txt.required'=>'البيان / الوصف مطلوب',
        'referance_number.required'=>'الرقم المرجعي مطلوب',
        'referance_number.unique'=>'الرقم المرجعي موجود',
        'supplier_id.required'=>'المورد مطلوب',
        'qyt.required'=>'الكمية مطلوبة',
        'selling_price.required' => 'سعر البيع مطلوب',
        'selling_price.numeric' => 'سعر البيع يجب أن يكون رقمًا',
        'selling_price.min' => 'سعر البيع يجب أن يكون 1 أو أكثر',
        'total_price.required' => 'السعر الكلي مطلوب',
        'total_price.numeric' => 'السعر الكلي يجب أن يكون رقمًا',
        'total_price.min' => 'السعر الكلي يجب أن يكون 1 أو أكثر',
        'paid.required'=>'المدفوع مطلوب',
        'remaining.required'=>'الباقي مطلوب',
        'payment_method.required'=>'طريقة الدفع مطلوبة',
        'safe_id.required'=>'الخزنة مطلوبة',
       ];
       $validator = Validator::make($data,$rules,$messages);
       if($validator->fails()){
           return redirect()->back()->withErrors($validator)->withInput();
       }
       try{
        DB::beginTransaction();
        $invoice->update([
            "bayan_txt" => $data['bayan_txt'],
            "referance_number" => $data['referance_number'],
            "client_id" => $data['client_id'],
            "supplier_id" => $data['supplier_id'],
            "qyt" => $data['qyt'],
            "selling_price" => $data['selling_price'],
            "total_price" => $data['total_price'],
            "paid" => $data['paid'],
            "remaining" => $data['remaining'],
            "payment_method" => $data['payment_method'],
            "safe_id" => $data['safe_id'],
            "category_id" => $data['category_id'],
            "admin_id" => $data['admin_id'],
        ]);
        ######################  Delete Old Transaction And Create New ########################
        ClientTransaction::where('sale_invoice_id', $invoice->id)->delete();
        ############################################# Add Transction To Client #############################
        if ($data['remaining'] > 0) {
            ClientTransaction::create([
                'client_id' => $data['client_id'],
                'sale_invoice_id' => $invoice->id,
                'amount' => $data['remaining'],
                'type' => 'debit',
                'description' => 'مبلغ مستحق من فاتورة بيع #' . $invoice->id,
            ]);
        }
        if ($data['paid'] > 0) {
            ClientTransaction::create([
                'client_id' => $data['client_id'],
                'sale_invoice_id' => $invoice->id,
                'amount' => $data['paid'],
                'type' => 'credit',
                'payment_method' => $data['payment_method'],
                'safe_id' => $data['safe_id'],
                'description' => 'دفعة لفاتورة بيع #' . $invoice->id,
            ]);
        }
        DB::commit();
        return $this->success_message(' تم تعديل الفاتورة بنجاح  ');
       }catch(\Exception $e){
        DB::rollBack();
        return $this->exception_message($e);
       }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $invoice = SaleInvoice::findOrFail($id);
            $invoice->delete();
            return $this->success_message(' تم حذف الفاتورة بنجاح ');
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }

    public function ReturnInvoice(Request $request,$id){
        $selling_invoice = SaleInvoice::findOrFail($id);
        $suppliers = Supplier::active()->get();
        $safes = Safe::active()->get();
        $categories = Category::active()->get();
        $clients = Client::active()->get();
        if($request->isMethod('post')){
            try{
                $data = $request->all();
                DB::beginTransaction();
                 ####### Start Add Sellign Return Invoice
                 $returnSelling = new SaleInvoiceReturn();
                 $returnSelling->bayan_txt = $selling_invoice['bayan_txt'];
                 $returnSelling->referance_number = $selling_invoice['referance_number'];
                 $returnSelling->supplier_id = $selling_invoice['supplier_id'];
                 $returnSelling->client_id = $selling_invoice['client_id'];
                 $returnSelling->qyt = $selling_invoice['qyt'];
                 $returnSelling->selling_price = $selling_invoice['selling_price'];
                 $returnSelling->total_price = $selling_invoice['total_price'];
                 $returnSelling->paid = $selling_invoice['paid'];
                 $returnSelling->remaining = $selling_invoice['remaining'];
                 $returnSelling->payment_method = $selling_invoice['payment_method'];
                 $returnSelling->safe_id = $selling_invoice['safe_id'];
                 $returnSelling->category_id = $selling_invoice['category_id'];
                 $returnSelling->admin_id =Auth::guard('admin')->id();
                 $returnSelling->sale_invoice_id = $selling_invoice['id'];
                 $returnSelling->return_price = $data['return_price'];
                 $returnSelling->save();
                 ############ Update Selling Invoice Status
                 $selling_invoice->return_status = 'returned';
                 $selling_invoice->save();
                 ###################################
                ############################################# Add Transction To Client #############################
                ClientTransaction::create([
                    'client_id' => $selling_invoice['client_id'],
                    'sale_invoice_id' => $selling_invoice->id,
                    'amount' => $data['return_price'],
                    'type' => 'debit', // المبلغ المدفوع من العميل  دائن
                    'payment_method' => $selling_invoice['payment_method'],
                    'safe_id' => $selling_invoice['safe_id'],
                    'description' => 'إرجاع لفاتورة بيع #' . $selling_invoice->id,
                ]);
                 DB::commit();
                 return to_route('dashboard.selling_invoices_return.index');
            }catch(\Exception $e){
                dd($e->getMessage());
                DB::rollBack();
                return $this->exception_message($e);
            }
        }
        return view('admin.invoices.selling.return', compact('selling_invoice','suppliers','safes','categories','clients'));
    }
}
