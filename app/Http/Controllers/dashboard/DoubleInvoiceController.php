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
use App\Models\admin\PurcheInvoice;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\admin\ClientTransaction;
use App\Models\admin\SupplierTransaction;
use Illuminate\Support\Facades\Validator;

class DoubleInvoiceController extends Controller
{

    use Message_Trait;

    public function create(){
        $suppliers = Supplier::active()->get();
        $safes = Safe::active()->get();
        $categories = Category::active()->get();
        $clients = Client::active()->get();
        if($clients->count() == 0){
            return redirect()->route('dashboard.clients.create');
        }
        return view('admin.invoices.dobule.create', compact('suppliers', 'safes','categories','clients'));
    }
    public function store(Request $request){
        $data = $request->all();
        $data['admin_id'] = Auth::user()->id;
        $rules = [
            'bayan_txt'=>'required',
            'referance_number'=>'required|unique:purche_invoices,referance_number',
            'supplier_id' => 'required',
            'qyt' => 'required',
            'purches_price' => 'required',
            'total_price' => 'required',
            'paid' => 'required',
            'remaining' => 'required',
            'type'=>'required',
            'client_id'=>'required',
            'selling_price' => 'required|numeric|min:1',
            'selling_total_price' => 'required|numeric|min:1',
            'selling_paid' => 'required',
            'selling_remaining' => 'required',
            //'payment_method' => 'required',
            'safe_id' => 'required',
        ];
        $messages = [
            'bayan_txt.required'=>'البيان / الوصف مطلوب',
            'referance_number.required'=>'الرقم المرجعي مطلوب',
            'referance_number.unique'=>'الرقم المرجعي موجود',
            'supplier_id.required'=>'المورد مطلوب',
            'qyt.required'=>'الكمية مطلوبة',
            'purches_price.required'=>'سعر الشراء مطلوب',
            'total_price.required'=>'السعر الكلي مطلوب',
            'paid.required'=>'المدفوع مطلوب',
            'remaining.required'=>'الباقي مطلوب',
            //'payment_method.required'=>'طريقة الدفع مطلوبة',
            'safe_id.required'=>'الخزنة مطلوبة',
            'type.required'=>'النوع مطلوب',
        'client_id.required'=>'العميل مطلوب',
        'selling_price.required'=>'سعر البيع مطلوب',
        'selling_price.numeric'=>'سعر البيع يجب أن يكون رقمًا',
        'selling_price.min'=>'سعر البيع يجب أن يكون 1 أو أكثر',
        'selling_total_price.required'=>'سعر البيع الكلي مطلوب',
        'selling_total_price.numeric'=>'سعر البيع الكلي يجب أن يكون رقمًا',
        'selling_total_price.min'=>'سعر البيع الكلي يجب أن يكون 1 أو أكثر',
        'selling_paid.required'=>'المدفوع مطلوب',
        'selling_remaining.required'=>'الباقي مطلوب',
        ];
        $validator = Validator::make($data,$rules,$messages);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try{
            ####### Start Add Purche Invoice
            DB::beginTransaction();
            $invoice = new PurcheInvoice;
            $invoice->type = $data['type'];
            $invoice->bayan_txt = $data['bayan_txt'];
            $invoice->referance_number = $data['referance_number'];
            $invoice->supplier_id = $data['supplier_id'];
            $invoice->qyt = $data['qyt'];
            $invoice->purches_price = $data['purches_price'];
            $invoice->total_price = $data['total_price'];
            $invoice->paid = $data['paid'];
            $invoice->remaining = $data['remaining'];
           // $invoice->payment_method = $data['payment_method'];
            $invoice->safe_id = $data['safe_id'];
            $invoice->category_id = $data['category_id'];
            $invoice->admin_id = Auth::user()->id;
            $invoice->status = 'sold';
            $invoice->save();
            ################################################ Add Transaction In Supplier Account ##############

        if($data['type'] == 'فاتورة رسمية'){

            if ($data['remaining'] > 0) {
                SupplierTransaction::create([
                    'supplier_id' => $data['supplier_id'],
                    'purchase_invoice_id' => $invoice->id,
                    'amount' => $data['remaining'],
                    'type' => 'credit', // المبلغ المستحق للمورد   الدائن
                    'description' => 'مبلغ مستحق من فاتورة شراء #' . $invoice->id,
                ]);
            }
            if ($data['paid'] > 0) {
               SupplierTransaction::create([
                    'supplier_id' => $data['supplier_id'],
                    'purchase_invoice_id' => $invoice->id,
                    'amount' => $data['paid'],
                    'type' => 'debit', // المبلغ المدفوع للمورد  مدين
                   // 'payment_method' => $data['payment_method'],
                    'safe_id' => $data['safe_id'],
                    'description' => 'دفعة لفاتورة شراء #' . $invoice->id,
                ]);
            }
        }
            ################# end Purche Invoice

            ################# Start Selling Invoice ################
            $sale_invoice = new SaleInvoice;
            $sale_invoice->bayan_txt = $data['bayan_txt'];
            $sale_invoice->referance_number = $data['referance_number'];
            $sale_invoice->supplier_id = $data['supplier_id'];
            $sale_invoice->client_id = $data['client_id'];
            $sale_invoice->qyt = $data['qyt'];
            $sale_invoice->selling_price = $data['selling_price'];
            $sale_invoice->total_price = $data['selling_total_price'];
            $sale_invoice->paid = $data['selling_paid'];
            $sale_invoice->remaining = $data['selling_remaining'];
           // $sale_invoice->payment_method = $data['payment_method'];
            $sale_invoice->safe_id = $data['safe_id'];
            $sale_invoice->category_id = $data['category_id'];
            $sale_invoice->admin_id = Auth::user()->id;
            $sale_invoice->save();

             ############################################# Add Transction To Client #############################
        if($data['selling_remaining'] > 0) {
            ClientTransaction::create([
                'client_id' => $data['client_id'],
                'sale_invoice_id' => $sale_invoice->id,
                'amount' => $data['selling_remaining'],
                'type' => 'debit', // المبلغ المستحق من العميل  مدين
                'description' => 'مبلغ مستحق من فاتورة بيع #' . $sale_invoice->id,
            ]);
        }
        if ($data['selling_paid'] > 0) {
            ClientTransaction::create([
                'client_id' => $data['client_id'],
                'sale_invoice_id' => $sale_invoice->id,
                'amount' => $data['selling_paid'],
                'type' => 'credit', // المبلغ المدفوع من العميل  دائن
               // 'payment_method' => $data['payment_method'],
                'safe_id' => $data['safe_id'],
                'description' => 'دفعة لفاتورة بيع #' . $sale_invoice->id,
            ]);
        }

            ################ End Selling Invoice ###########
            DB::commit();
            return $this->success_message('تم إضافة  فاتورة شراء وفاتورة بيع بنجاح');
        }catch(\Exception $e){
            DB::rollBack();
            return $this->exception_message($e);
        }
    }
}
