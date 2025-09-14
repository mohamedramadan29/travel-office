<?php

namespace App\Http\Controllers\dashboard;

use App\Exports\SuppliersExport;
use App\Models\admin\Safe;
use Illuminate\Http\Request;
use App\Models\admin\Supplier;
use App\Http\Traits\Message_Trait;
use Illuminate\Support\Facades\DB;
use App\Models\admin\PurcheInvoice;
use App\Http\Controllers\Controller;
use App\Models\admin\SafeTransaction;
use App\Models\admin\SupplierTransaction;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Mpdf\Mpdf;
class SuppliersController extends Controller
{
  use Message_Trait;
    public function index()
    {
        $suppliers = Supplier::paginate(10);
        return view('admin.suppliers.index', compact('suppliers'));
    }
    public function create()
    {
        return view('admin.suppliers.create');
    }
    public function store(Request $request)
    {
        $data = $request->all();
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:suppliers,email|max:255',
            'mobile' => 'nullable|string|max:255|unique:suppliers,mobile',
            'telegram' => 'nullable|string|max:255|unique:suppliers,telegram',
            'whatsapp' => 'nullable|string|max:255|unique:suppliers,whatsapp',
            'status' => 'required|numeric',
            'address' => 'nullable|string|max:255',
        ];
        $messages = [
            'name.required' => 'الاسم مطلوب',
            'email.unique' => 'البريد الالكتروني موجود بالفعل',
            'mobile.unique' => 'رقم الهاتف موجود بالفعل',
            'telegram.unique' => 'رقم التيلغرام موجود بالفعل',
            'whatsapp.unique' => 'رقم الواتساب موجود بالفعل',
            'status.required' => 'الحالة مطلوبة',
           // 'address.required' => 'العنوان مطلوب',
        ];
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $supplier = new Supplier();
        $supplier->name = $data['name'];
        $supplier->email = $data['email'];
        $supplier->mobile = $data['mobile'];
        $supplier->telegram = $data['telegram'];
        $supplier->whatsapp = $data['whatsapp'];
        $supplier->status = $data['status'];
        $supplier->address = $data['address'];
        $supplier->save();
      return $this->success_message('تم اضافة المورد بنجاح');
    }
    public function show(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.suppliers.show', compact('supplier'));
    }

    public function edit(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.suppliers.edit', compact('supplier'));
    }
    public function update(Request $request, string $id)
    {
        $supplier = Supplier::findOrFail($id);

        $data = $request->all();
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:suppliers,email,' . $supplier->id,
            'mobile' => 'nullable|string|max:255|unique:suppliers,mobile,' . $supplier->id,
            'telegram' => 'nullable|string|max:255|unique:suppliers,telegram,' . $supplier->id,
            'whatsapp' => 'nullable|string|max:255|unique:suppliers,whatsapp,' . $supplier->id,
            'status' => 'required|numeric',
            'address' => 'nullable|string|max:255',
        ];
        $messages = [
            'name.required' => 'الاسم مطلوب',
            'email.unique' => 'البريد الالكتروني موجود بالفعل',
            'mobile.unique' => 'رقم الهاتف موجود بالفعل',
            'telegram.unique' => 'رقم التيلغرام موجود بالفعل',
            'whatsapp.unique' => 'رقم الواتساب موجود بالفعل',
            'status.required' => 'الحالة مطلوبة',
           // 'address.required' => 'العنوان مطلوب',
        ];
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $supplier->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'telegram' => $data['telegram'],
            'whatsapp' => $data['whatsapp'],
            'status' => $data['status'],
            'address' => $data['address'],
        ]);
        return $this->success_message('تم تحديث المورد بنجاح');
    }
    public function destroy(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return $this->success_message('تم حذف المورد بنجاح');
    }

    public function ChangeStatus($id){

        $supplier = Supplier::findOrFail($id);
        $supplier->update([
            'status' => $supplier->status == 'نشط' ? '0' : '1',
        ]);
        return $this->success_message('تم تغير الحالة بنجاح');
    }


    public function transactions(Request $request, $id)
    {
        $safes = Safe::active()->get();
        $supplier = Supplier::findOrFail($id);

        if (!$supplier) {
            abort(404);
        }

        // جلب معلمات التاريخ من الطلب
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        // التحقق من صحة التواريخ
        if ($fromDate && $toDate) {
            try {
                $fromDate = Carbon::parse($fromDate)->startOfDay();
                $toDate = Carbon::parse($toDate)->endOfDay();
                if ($fromDate > $toDate) {
                    return redirect()->back()->withErrors('تاريخ البداية يجب أن يكون قبل تاريخ النهاية');
                }
            } catch (\Exception $e) {
                return redirect()->back()->withErrors('تنسيق التاريخ غير صالح');
            }
        }

        // جلب الفواتير بناءً على الفترة الزمنية
        $query = PurcheInvoice::where('supplier_id', $supplier->id);
        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        }
        $invoices = $query->orderBy('created_at', 'desc')->get();

        // جلب إجمالي قيم الفواتير ضمن الفترة
        $total_invoices = $invoices->sum('total_price');
        $total_returned = $invoices->where('return_status','returned')->sum('total_price');

        // حساب الرصيد الافتتاحي (إجمالي الفواتير قبل from_date)
        $opening_balance = 0; // القيمة الافتراضية إذا لم يكن هناك from_date
        if ($fromDate) {
            $opening_balance = PurcheInvoice::where('supplier_id', $supplier->id)
                ->where('created_at', '<', $fromDate)
                ->sum('total_price');
        }

        // جلب جميع المعاملات
        $transactions = SupplierTransaction::where('supplier_id', $supplier->id)
            ->with('purchaseInvoice')
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                return $query->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->orderBy('id', 'desc')
            ->get();

        // حساب إجمالي المدفوع (Debit)
        $total_debit = $transactions->where('type', 'debit')->sum('amount');

        $total_credit = $transactions->where('type', 'credit')->sum('amount');

        // الرصيد المستحق = إجمالي الفواتير - إجمالي المدفوع
        $balance = $total_invoices - $total_debit;

        $supplier_balance = $total_credit - $total_debit;

        return view('admin.suppliers.transactions', compact(
            'supplier',
            'transactions',
            'total_invoices',
            'total_returned',
            'total_debit',
            'total_credit',
            'balance',
            'supplier_balance',
            'invoices',
            'safes',
            'fromDate',
            'toDate',
            'opening_balance'
        ));
    }



    public function AddTransaction(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $data = $request->all();
        $rules = [
            'amount' => 'required|numeric|min:0.01',
          //  'invoice_id' => 'nullable|exists:purche_invoices,id',
            'safe_id' => 'required|exists:safes,id',
        ];
        $messages = [
            'amount.required' => 'المبلغ مطلوب',
            'amount.numeric' => 'المبلغ يجب أن يكون رقمًا',
            'amount.min' => 'المبلغ يجب أن يكون أكبر من 0',
          //  'invoice_id.exists' => 'الفاتورة غير موجودة',

          'safe_id.required'=>' من فضلك حدد الخزينة  ',
           'safe_id.exists' => 'الخزنة غير موجودة',
        ];

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $safe = Safe::findOrFail($data['safe_id']);
        $oldSafeBalance =  $safe->balance;
        $newSafeBalance = $oldSafeBalance - $data['amount'];
        if($newSafeBalance < 0){
            return redirect()->back()->withErrors(['amount' => ' رصيد الخزينة غير كافي لتسديد الدفعة الي المورد  ']);
        }
        if( isset($data['invoice_id']) && $data['invoice_id'] !=null){
            $invoice = PurcheInvoice::findOrFail($data['invoice_id']);
            if ($invoice->supplier_id != $supplier->id) {
                return redirect()->back()->withErrors(['invoice_id' => 'لا يمكن إضافة المعاملة لفاتورة مورد آخر']);
            }
            // حساب إجمالي المدفوع (Debit) والرصيد المستحق
        $total_balance = SupplierTransaction::where('purchase_invoice_id', $invoice->id)
        ->where('type', 'debit')
        ->sum('amount');
    $remaining_balance = $invoice->total_price - $total_balance;

    // التحقق من وجود رصيد مستحق
    if ($remaining_balance <= 0) {
        return redirect()->back()->withErrors(['amount' => 'تم تسديد الفاتورة بالكامل']);
    }

    // التحقق من أن المبلغ المدخل مش أكبر من الرصيد المستحق
    if ($data['amount'] > $remaining_balance) {
        return redirect()->back()->withErrors(['amount' => 'المبلغ المدخل أكبر من الرصيد المستحق (' . $remaining_balance . ' د.ل)']);
    }
        }



        try {
            DB::beginTransaction();
            // تسجيل المعاملة
            $transaction = new SupplierTransaction();
            $transaction->supplier_id = $supplier->id;
            $transaction->amount = $data['amount'];
            $transaction->purchase_invoice_id = isset($data['invoice_id']) ? $data['invoice_id'] : null;
            $transaction->safe_id = $data['safe_id'];
            $transaction->type = 'debit';
            $transaction->description = isset($data['invoice_id']) && $data['invoice_id']
            ? 'تسديد دفعة لفاتورة #' . $data['invoice_id']
            : 'تسديد دفعة عامة';
            $transaction->save();

            if(isset($data['invoice_id']) && $data['invoice_id'] !=null){
            // تحديث حالة الفاتورة (اختياري)
            $new_balance = $remaining_balance - $data['amount'];
            $invoice->update([
                'paid' => $total_balance + $data['amount'],
                'remaining' => $new_balance,
            ]);
            }
            ############################################# Start Add Transaction To Safe ############################
            $safeTransaction = new SafeTransaction();
            $safeTransaction->safe_id = $data['safe_id'];
            $safeTransaction->supplier_id = $supplier->id;
            $safeTransaction->amount = $data['amount'];
            $safeTransaction->type = 'withdraw';
            $safeTransaction->description = ' اضافة دفعة الي المورد [ ' . $supplier->name . ' ]';
            $safeTransaction->save();
            ############################################ End Add Transaction To Safe ###############################
            ################## Update Safe Balance #########
            $safe = Safe::findOrFail($data['safe_id']);
            $oldSafeBalance =  $safe->balance;
            $newSafeBalance = $oldSafeBalance - $data['amount'];
            $safe->balance = $newSafeBalance;
            $safe->save();
            ################ End Update Safe Balance ########
            DB::commit();
            return $this->success_message('تم إضافة المعاملة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['general' => 'حدث خطأ: ' . $e->getMessage()])->withInput();
        }
    }

    ########################################### Generate Suppliers Pdf ##########################################
    public function SuppliersPdf(){
        $suppliers = Supplier::latest()->get();

        $html = '
        <html lang="ar" dir="rtl">
        <head>
            <style>
                body {
                    font-family: "tajawal", sans-serif;
                    text-align: right;
                    direction: rtl;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #000;
                    padding: 8px;
                    text-align: right;
                }
                th {
                    background-color: #f2f2f2;
                }
            </style>
        </head>
        <body>
        <div style="text-align:center; margin:auto;display:block">
            <img  src="' . url('assets/admin/images/logo.png') . '" style="width:120px;" alt="Logo">
            <h4>تقرير عن الموردين</h4>
        </div>
            <table>
                <thead>
                    <tr>
                        <th> الاسم </th>
                        <th> رقم الهاتف </th>
                        <th> رقم التيلغرام </th>
                        <th> رقم الواتساب </th>
                        <th> الحالة </th>
                        <th> تاريخ الانشاء </th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($suppliers as $supplier) {
            $html .= '
                    <tr>
                        <td>' . $supplier->name . '</td>
                        <td>' . $supplier->mobile . '</td>
                        <td>' . $supplier->telegram . '</td>
                        <td>' . $supplier->whatsapp . '</td>
                        <td>' . $supplier->status . '</td>
                        <td>' . $supplier->created_at->format('Y-m-d') . '</td>
                    </tr>';
        }

        $html .= '
                </tbody>
            </table>
        </body>
        </html>';

        $mpdf = new \Mpdf\Mpdf([
            'default_font' => 'tajawal', // خط fallback أساسي
        ]);

        $mpdf->WriteHTML($html);
        return $mpdf->Output('تقرير عن الموردين.pdf', 'I');
    }


    ######################################### Generate Suppliers Excel ############################

    public function SuppliersExcel(){
        return (new SuppliersExport())->download('Suppliers.xlsx');
    }



}
