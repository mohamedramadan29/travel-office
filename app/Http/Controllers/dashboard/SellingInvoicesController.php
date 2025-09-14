<?php

namespace App\Http\Controllers\dashboard;

use Mpdf\Mpdf;
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
use App\Models\admin\SafeTransaction;
use App\Exports\SellingInvoicesExport;
use App\Models\admin\ClientTransaction;
use App\Models\admin\SaleInvoiceReturn;
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
        $purchesInvoices = PurcheInvoice::where('status','available')->get();
        //dd($purchesInvoices);

        return view('admin.invoices.selling.create', compact('suppliers', 'safes','categories','clients','purchesInvoices'));
    }


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
                // 'payment_method' => 'required',
                // 'safe_id' => 'required',
            ];
            // لو المدفوع أكبر من صفر أضف القواعد
            if (!empty($data['paid']) && $data['paid'] > 0) {
              //  $rules['payment_method'] = 'required';
                $rules['safe_id'] = 'required';
            }
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
       // 'payment_method.required'=>'طريقة الدفع مطلوبة',
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
        //$invoice->payment_method = $data['payment_method'] ?? null;
        $invoice->safe_id = $data['safe_id'] ?? null;
        $invoice->category_id = $data['category_id'];
        $invoice->admin_id = Auth::user()->id;
        $invoice->save();
        $purchesInvoice = PurcheInvoice::where('referance_number', $data['referance_number'])->first();
        $purchesInvoice->status = 'sold';
        $purchesInvoice->save();
        ############################################# Add Transction To Client #############################
     //   if($data['remaining'] > 0) {
            ClientTransaction::create([
                'client_id' => $data['client_id'],
                'sale_invoice_id' => $invoice->id,
                'amount' => $data['total_price'],
                'type' => 'debit', // المبلغ المستحق من العميل  مدين
                'description' => 'مبلغ مستحق من فاتورة بيع #' . $invoice->id,
            ]);
      //  }
        if ($data['paid'] > 0) {
            ClientTransaction::create([
                'client_id' => $data['client_id'],
                'sale_invoice_id' => $invoice->id,
                'amount' => $data['paid'],
                'type' => 'credit', // المبلغ المدفوع من العميل  دائن
                //'payment_method' => $data['payment_method'],
                'safe_id' => $data['safe_id'],
                'description' => 'دفعة لفاتورة بيع #' . $invoice->id,
            ]);
        }
        if($data['paid'] > 0){
            ############################################# Start Add Transaction To Safe ############################
            $safeTransaction = new SafeTransaction();
            $safeTransaction->safe_id = $data['safe_id'];
            $safeTransaction->sale_invoice_id = $invoice->id;
            $safeTransaction->amount = $data['paid'];
            $safeTransaction->type = 'deposit';
           // $safeTransaction->payment_method = $data['payment_method'];
            $safeTransaction->description = ' اضافة دفعة من العميل [ ' . $invoice->client->name . ' ]' . ' من فاتورة بيع الرقم المرجعي :  ' . $invoice->referance_number;
            $safeTransaction->save();
            ############################################ End Add Transaction To Safe ###############################
            ################## Update Safe Balance #########
            $safe = Safe::findOrFail($data['safe_id']);
            $oldSafeBalance =  $safe->balance;
            $newSafeBalance = $oldSafeBalance + $data['paid'];
            $safe->update([
                'balance' => $newSafeBalance,
            ]);
            ################ End Update Safe Balance ########
           }
        DB::commit();
        return $this->success_message(' تم اضافة فاتورة بيع بنجاح  ');
       }catch(\Exception $e){
        DB::rollBack();
        return $this->exception_message($e);
       }
    }


    public function show(string $id)
    {
        $invoice = SaleInvoice::findOrFail($id);
        return view('admin.invoices.selling.show', compact('invoice'));
    }


    public function edit(string $id)
    {
        $selling_invoice = SaleInvoice::findOrFail($id);
        $suppliers = Supplier::active()->get();
        $safes = Safe::active()->get();
        $categories = Category::active()->get();
        $clients = Client::active()->get();
        return view('admin.invoices.selling.edit', compact('selling_invoice','suppliers','safes','categories','clients'));
    }

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
               // 'payment_method' => 'required',
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
      //  'payment_method.required'=>'طريقة الدفع مطلوبة',
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
          //  "payment_method" => $data['payment_method'],
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
               // 'payment_method' => $data['payment_method'],
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
                $additional_profit = $data['return_price'] - $selling_invoice['total_price'];
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
              //   $returnSelling->payment_method = $selling_invoice['payment_method'];
                 $returnSelling->safe_id = $selling_invoice['safe_id'];
                 $returnSelling->category_id = $selling_invoice['category_id'];
                 $returnSelling->admin_id =Auth::guard('admin')->id();
                 $returnSelling->sale_invoice_id = $selling_invoice['id'];
                 $returnSelling->return_price = $data['return_price'];
                 $returnSelling->additional_profit = $additional_profit;
                 $returnSelling->save();
                 ############ Update Selling Invoice Status
                 $selling_invoice->return_status = 'returned';

                 $selling_invoice->save();
                 ############# Update Purches Invoice
                 $purchesInvoice = PurcheInvoice::where('referance_number', $selling_invoice['referance_number'])->first();
                 $purchesInvoice->status = 'available';
                 $purchesInvoice->save();
                 ###################################
                ############################################# Add Transction To Client #############################
                ClientTransaction::create([
                    'client_id' => $selling_invoice['client_id'],
                    'sale_invoice_id' => $selling_invoice->id,
                  //  'amount' => $data['return_price'],
                    'amount'=>$selling_invoice->total_price,
                    'type' => 'credit', // المبلغ المدفوع من العميل  دائن
                  //  'payment_method' => $selling_invoice['payment_method'],
                    'safe_id' => $selling_invoice['safe_id'],
                    'description' => 'إرجاع لفاتورة بيع #' . $selling_invoice->id,
                ]);

                ######## If return Price  < total Price Add New Trasactin depit to client ( More Liked  )

                if($data['return_price'] < $selling_invoice->total_price){
                    $additional_profit = $selling_invoice->total_price - $data['return_price'];
                    ClientTransaction::create([
                        'client_id' => $selling_invoice['client_id'],
                        'sale_invoice_id' => $selling_invoice->id,
                      //  'amount' => $data['return_price'],
                      'amount'=>$additional_profit,
                        'type' => 'debit',  // مبلغ اضافي مستحق من العميل عند رجوع الفاتورة
                      //  'payment_method' => $selling_invoice['payment_method'],
                        'safe_id' => $selling_invoice['safe_id'],
                        'description' => ' مبلغ  مستحق للشركة من فاتورة رجوع  #' . $selling_invoice->id,
                    ]);
                }

                if($data['return_price'] > $selling_invoice->total_price){
                    $additional_profit = $data['return_price']  - $selling_invoice['total_price'] ;
                    ClientTransaction::create([
                        'client_id' => $selling_invoice['client_id'],
                        'sale_invoice_id' => $selling_invoice->id,
                      //  'amount' => $data['return_price'],
                      'amount'=>$additional_profit,
                        'type' => 'credit',  // مبلغ هينضاف الي العميل
                      //  'payment_method' => $selling_invoice['payment_method'],
                        'safe_id' => $selling_invoice['safe_id'],
                        'description' => ' مبلغ مستحق للعميل من الشركة في فاتورة رجوع  #' . $selling_invoice->id,
                    ]);
                }
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

    public function InterimInvoices(){
        $invoices = PurcheInvoice::where('status','sold')->where('type','فاتورة مؤقتة')->latest()->paginate(10);
        $query = $invoices->map(function ($invoice){
            $selling_invoice = SaleInvoice::where('referance_number',$invoice->referance_number)->first();
           // $invoice->selling_invoice = $selling_invoice;
            $invoice->client = $selling_invoice->client->name ?? 'لا يوجد';
            return $invoice;
        });
    //    dd($query);
        return view('admin.invoices.selling.interim',compact('invoices'));
    }

        ########################################### Generate All Selling Invoices  Pdf ##########################################
        public function SellingInvoicesPdf(){
            $invoices = SaleInvoice::latest()->get();
            // إعداد محتوى HTML
            $html = '
            <html lang="ar" dir="rtl">
            <head>
                <style>
                    body {
                        font-family: "Cairo", sans-serif; /* اختر خط يدعم اللغة العربية */
                        text-align: right; /* محاذاة النصوص لليمين */
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th, td {
                        border: 1px solid #000;
                        padding: 8px;
                        text-align: right; /* لمحاذاة النصوص داخل الجدول */
                    }
                    th {
                        background-color: #f2f2f2; /* لون خلفية للرأس */
                    }
                </style>
            </head>
            <body>
            <div style="text-align:center; margin:auto;display:block">
                <img  src="' . url('assets/admin/images/logo.png') . '" style="width:120px;" alt="Logo">
                <h4>   تقرير عن  فواتير البيع   </h4>
                </div>
                <table>
                    <thead>
                        <tr>
                           <th> العميل </th>
                            <th> البيان </th>
                            <th> الرقم المرجعي </th>
                            <th> المورد </th>
                            <th> التصنيف </th>
                            <th> الكمية </th>
                            <th> السعر الكلي </th>
                            <th> تاريخ الانشاء </th>
                        </tr>
                    </thead>
                    <tbody>';

            // تعبئة البيانات داخل الجدول
            foreach ($invoices as $invoice) {

                $html .= '
                        <tr>
                            <td>' . $invoice->client->name . '</td>
                            <td>' . $invoice->bayan_txt . '</td>
                            <td>' . $invoice->referance_number . '</td>
                            <td>' . $invoice->supplier->name . '</td>
                            <td>' . $invoice->category->name . '</td>
                            <td>' . $invoice->qyt . '</td>
                            <td>' . number_format($invoice->total_price, 2) . ' دينار</td>
                            <td>' . $invoice->created_at->format('Y-m-d H:i') . '</td>
                        </tr>';
            }
            $html .= '
                    </tbody>
                </table>
            </body>
            </html>';

            // إعداد mPDF
            $mpdf = new Mpdf([
                'default_font' => 'Cairo', // خط يدعم اللغة العربية
            ]);

            // تحميل المحتوى إلى ملف PDF
            $mpdf->WriteHTML($html);
            // توليد ملف PDF وإرساله للتنزيل
            return $mpdf->Output('تقرير عن فواتير البيع.pdf', 'I'); // 'I' لعرض الملف في المتصفح

        }

        ######################################### Generate Selling Invoices Excel ############################

        public function SellingInvoicesExcel(){
            return (new SellingInvoicesExport())->download('SellingInvoices.xlsx');
        }
}
