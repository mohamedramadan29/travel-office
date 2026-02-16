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

    public function storeQuick(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:suppliers,email',
            'mobile' => 'required|string|max:20|unique:suppliers,mobile',
            'whatsapp' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ], [
            'name.required' => 'الاسم مطلوب',
            'mobile.required' => 'رقم الهاتف مطلوب',
            'mobile.unique' => 'رقم الهاتف مستخدم من قبل',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل'
        ]);

        $supplier = new Supplier();
        $supplier->name = $data['name'];
        $supplier->email = $data['email'];
        $supplier->mobile = $data['mobile'];
        $supplier->whatsapp = $data['whatsapp'];
        $supplier->address = $data['address'];
        $supplier->status = 1; // Active by default
        $supplier->save();

        return response()->json([
            'success' => true,
            'message' => 'تم اضافة المورد بنجاح',
            'supplier' => $supplier
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->get('q', '');

        $suppliers = Supplier::active()
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('mobile', 'like', '%' . $search . '%')
            ->limit(10)
            ->get(['id', 'name', 'mobile', 'email', 'whatsapp', 'address']);

        $results = $suppliers->map(function ($supplier) {
            return [
                'id' => $supplier->id,
                'text' => $supplier->name . (isset($supplier->mobile) ? ' - ' . $supplier->mobile : ''),
                'name' => $supplier->name,
                'mobile' => $supplier->mobile ?? '',
                'email' => $supplier->email ?? '',
                'whatsapp' => $supplier->whatsapp ?? '',
                'address' => $supplier->address ?? ''
            ];
        });

        return response()->json([
            'results' => $results
        ]);
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

    public function ChangeStatus($id)
    {

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
        $total_returned = $invoices->where('return_status', 'returned')->sum('total_price');

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
        // dd($transactions);

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

    public function SuppliersReport(Request $request)
    {
        $suppliers = Supplier::latest()->get();
        return view('admin.suppliers.report', compact('suppliers'));
    }

    public function SuppliersReportPdf(Request $request)
    {
        $query = Supplier::latest();

        if ($request->has('supplier_ids')) {
            if (is_array($request->supplier_ids)) {
                $query->whereIn('id', $request->supplier_ids);
            } else {
                $query->whereIn('id', explode(',', $request->supplier_ids));
            }
        }

        $suppliers = $query->get();
        // إعداد محتوى HTML
        $html = '
        <html lang="ar" dir="rtl">
        <head>
            <style>
                body {
                    font-family: "tajawal", sans-serif; /* اختر خط يدعم اللغة العربية */
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
            <h4>تقرير عن الموردين </h4>
        </div>
            <table>
                <thead>
                    <tr>
                        <th> الاسم </th>
                        <th> رقم الهاتف </th>
                        <th> رقم التيلغرام </th>
                        <th> رقم الواتساب </th>
                        <th> الرصيد  </th>
                        <th> دائن / مدين  </th>
                        <th> الحالة </th>
                        <th> تاريخ الانشاء </th>
                    </tr>
                </thead>
                <tbody>';

        // تعبئة البيانات داخل الجدول
        foreach ($suppliers as $supplier) {
            $html .= '
                    <tr>
                        <td>' . $supplier->name . '</td>
                        <td>' . $supplier->mobile . '</td>
                        <td>' . $supplier->telegram . '</td>
                        <td>' . $supplier->whatsapp . '</td>
                        <td>' . number_format($supplier->balance(), 2) . '</td>
                        <td>' . ($supplier->balance() > 0 ? 'دائن' : ($supplier->balance() < 0 ? 'مدين' : '')) . '</td>
                        <td>' . $supplier->status . '</td>
                        <td>' . $supplier->created_at->format('Y-m-d') . '</td>
                    </tr>';
        }
        $html .= '
                </tbody>
            </table>
        </body>
        </html>';

        // إعداد mPDF
        $mpdf = new Mpdf([
            'default_font' => 'tajawal', // خط يدعم اللغة العربية
        ]);

        // تحميل المحتوى إلى ملف PDF
        $mpdf->WriteHTML($html);
        // توليد ملف PDF وإرساله للتنزيل
        return $mpdf->Output('تقرير عن الموردين.pdf', 'I'); // 'I' لعرض الملف في المتصفح
    }

    public function SuppliersReportExcel(Request $request)
    {
        $supplier_ids = null;
        if ($request->has('supplier_ids')) {
            if (is_array($request->supplier_ids)) {
                $supplier_ids = $request->supplier_ids;
            } else {
                $supplier_ids = explode(',', $request->supplier_ids);
            }
        }
        return (new \App\Exports\SuppliersReportExport($supplier_ids))->download('Suppliers_Report.xlsx');
    }

    public function UpdateTransaction(Request $request, $id)
    {
        $transaction = SupplierTransaction::findOrFail($id);

        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'safe_id' => 'required|exists:safes,id',
            'description' => 'nullable|string',
        ], [
            'amount.required' => 'المبلغ مطلوب',
            'amount.numeric' => 'المبلغ يجب أن يكون رقمًا',
            'amount.min' => 'المبلغ يجب أن يكون أكبر من 0',
            'safe_id.required' => 'الخزنة مطلوبة',
            'safe_id.exists' => 'الخزنة غير موجودة',
        ]);

        if ($transaction->type != 'debit') {
            return redirect()->back()->withErrors(['general' => 'لا يمكن تعديل هذه المعاملة']);
        }

        DB::beginTransaction();
        try {
            // 1. Revert Old Safe Balance (Assuming Debit = Withdraw from Safe)
            $oldSafe = Safe::findOrFail($transaction->safe_id);
            $oldSafe->balance += $transaction->amount;
            $oldSafe->save();

            // 2. Update Transaction
            $transaction->amount = $data['amount'];
            $transaction->safe_id = $data['safe_id'];
            $transaction->description = $data['description'];
            $transaction->save();

            // 3. Apply New Safe Balance
            $newSafe = Safe::findOrFail($data['safe_id']);
            $newSafe->balance -= $data['amount'];
            $newSafe->save();

            DB::commit();
            return redirect()->back()->with('success', 'تم تعديل المعاملة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['general' => 'حدث خطأ: ' . $e->getMessage()]);
        }
    }

    public function PrintTransaction($id)
    {
        $transaction = SupplierTransaction::with('supplier', 'safe')->findOrFail($id);
        $setting = \App\Models\admin\Setting::first();
        return view('admin.suppliers.print_transaction', compact('transaction', 'setting'));
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

            'safe_id.required' => ' من فضلك حدد الخزينة  ',
            'safe_id.exists' => 'الخزنة غير موجودة',
        ];

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $safe = Safe::findOrFail($data['safe_id']);
        $oldSafeBalance =  $safe->balance;
        $newSafeBalance = $oldSafeBalance - $data['amount'];
        if ($newSafeBalance < 0) {
            return redirect()->back()->withErrors(['amount' => ' رصيد الخزينة غير كافي لتسديد الدفعة الي المورد  ']);
        }
        if (isset($data['invoice_id']) && $data['invoice_id'] != null) {
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
            $transaction->description =  $data['description'];
            $transaction->save();

            if (isset($data['invoice_id']) && $data['invoice_id'] != null) {
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
            $safeTransaction->description = $data['description'];
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
    ########################################### Generate Suppliers Pdf ##########################################
    public function SuppliersPdf(Request $request)
    {
        $query = Supplier::latest();
        if ($request->has('supplier_ids')) {
            $supplier_ids = explode(',', $request->supplier_ids);
            if (is_array($request->supplier_ids)) {
                $query->whereIn('id', $request->supplier_ids);
            } else {
                $query->whereIn('id', explode(',', $request->supplier_ids));
            }
        }
        $suppliers = $query->get();

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
                        <th> الرصيد  </th>
                        <th> دائن / مدين  </th>
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
                        <td>' . number_format($supplier->balance(), 2) . '</td>
                        <td>' . ($supplier->balance() > 0 ? 'دائن' : ($supplier->balance() < 0 ? 'مدين' : '')) . '</td>
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

    public function SuppliersExcel(Request $request)
    {
        $supplier_ids = null;
        if ($request->has('supplier_ids')) {
            if (is_array($request->supplier_ids)) {
                $supplier_ids = $request->supplier_ids;
            } else {
                $supplier_ids = explode(',', $request->supplier_ids);
            }
        }
        return (new SuppliersExport($supplier_ids))->download('Suppliers.xlsx');
    }
}
