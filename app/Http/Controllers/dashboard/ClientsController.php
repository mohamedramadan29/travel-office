<?php

namespace App\Http\Controllers\dashboard;

use Mpdf\Mpdf;
use App\Models\admin\Safe;
use App\Models\admin\Client;
use Illuminate\Http\Request;
use App\Exports\ClientsExport;
use App\Models\admin\SaleInvoice;
use App\Http\Traits\Message_Trait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\admin\SafeTransaction;
use App\Models\admin\ClientTransaction;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ClientsController extends Controller
{
    use Message_Trait;
    public function index()
    {
        $clients = Client::paginate(100);
        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clients,email|max:255',
            'mobile' => 'nullable|string|max:255|unique:clients,mobile',
            'telegram' => 'nullable|string|max:255|unique:clients,telegram',
            'whatsapp' => 'nullable|string|max:255|unique:clients,whatsapp',
            'status' => 'required|numeric',
            'address' => 'nullable|string|max:255',
            // Transaction fields validation
            'transaction_amount' => 'nullable|numeric|min:0',
            'safe_id' => 'nullable|exists:safes,id',
            'transaction_type' => 'nullable|in:credit,debit',
            'transaction_notes' => 'nullable|string|max:500',
        ];

        // Add conditional validation for transaction
        if ($request->filled('transaction_amount')) {
            $rules['safe_id'] = 'required|exists:safes,id';
            $rules['transaction_type'] = 'required|in:credit,debit';
        }

        $messages = [
            'name.required' => 'الاسم مطلوب',
            'email.unique' => 'البريد الالكتروني موجود بالفعل',
            'mobile.unique' => 'رقم الهاتف موجود بالفعل',
            'telegram.unique' => 'رقم التيلغرام موجود بالفعل',
            'whatsapp.unique' => 'رقم الواتساب موجود بالفعل',
            'status.required' => 'الحالة مطلوبة',
            'transaction_amount.min' => 'المبلغ يجب أن يكون أكبر من الصفر',
            'safe_id.required' => 'الخزينة مطلوبة عند إدخال مبلغ',
            'safe_id.exists' => 'الخزينة المختارة غير موجودة',
            'transaction_type.required' => 'نوع المعاملة مطلوب عند إدخال مبلغ',
            'transaction_type.in' => 'نوع المعاملة غير صحيح',
        ];
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Create client
            $client = new Client();
            $client->name = $data['name'];
            $client->email = $data['email'];
            $client->mobile = $data['mobile'];
            $client->telegram = $data['telegram'];
            $client->whatsapp = $data['whatsapp'];
            $client->status = $data['status'];
            $client->address = $data['address'];
            $client->save();

            // Create transaction if amount is provided
            if ($request->filled('transaction_amount') && $request->transaction_amount > 0) {
                $transaction = new ClientTransaction();
                $transaction->client_id = $client->id;
                $transaction->safe_id = $data['safe_id'];
                $transaction->amount = $data['transaction_amount'];
                $transaction->type = $data['transaction_type'];
                $transaction->description = $data['transaction_notes'] ?? ('معاملة أولية للعميل ' . $client->name);
                $transaction->save();
                // Update safe balance
                $safe = Safe::find($data['safe_id']);
                if ($safe) {
                    if ($data['transaction_type'] == 'refund') {
                        // Money coming from client - increase safe balance
                        $safe->increment('balance', $data['transaction_amount']);
                    } else {
                        // Money going to client - decrease safe balance
                        $safe->decrement('balance', $data['transaction_amount']);
                    }
                }
            }

            DB::commit();
            return $this->success_message('تم اضافة العميل بنجاح' . ($request->filled('transaction_amount') ? ' مع المعاملة المالية' : ''));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء الحفظ: ' . $e->getMessage()])->withInput();
        }
    }

    public function storeQuick(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:clients,email',
            'mobile' => 'required|string|max:20|unique:clients,mobile',
            'whatsapp' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ], [
            'name.required' => 'الاسم مطلوب',
            'mobile.required' => 'رقم الهاتف مطلوب',
            'mobile.unique' => 'رقم الهاتف مستخدم من قبل',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل'
        ]);

        $client = new Client();
        $client->name = $data['name'];
        $client->email = $data['email'];
        $client->mobile = $data['mobile'];
        $client->whatsapp = $data['whatsapp'];
        $client->address = $data['address'];
        $client->status = 1; // Active by default
        $client->save();

        return response()->json([
            'success' => true,
            'message' => 'تم اضافة العميل بنجاح',
            'client' => $client
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $client = Client::findOrFail($id);
        return view('admin.clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = Client::findOrFail($id);
        return view('admin.clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $client = Client::findOrFail($id);

        $data = $request->all();
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:clients,email,' . $client->id,
            'mobile' => 'nullable|string|max:255|unique:clients,mobile,' . $client->id,
            'telegram' => 'nullable|string|max:255|unique:clients,telegram,' . $client->id,
            'whatsapp' => 'nullable|string|max:255|unique:clients,whatsapp,' . $client->id,
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
        $client->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'telegram' => $data['telegram'],
            'whatsapp' => $data['whatsapp'],
            'status' => $data['status'],
            'address' => $data['address'],
        ]);
        return $this->success_message('تم تحديث العميل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        return $this->success_message('تم حذف العميل بنجاح');
    }

    #################### All Report Clients  ######################

    public function ClientsReport(Request $request)
    {
        $query = Client::latest();
        // Dropdown selection removed in favor of direct checkbox selection on the page
        // But if we want to pre-filter by link, we can keep this or just load all.
        // The requirement is to show checkboxes on the page.
        // Let's just load all clients related logic.
        $clients = $query->paginate(100);
        return view('admin.clients.report', compact('clients'));
    }

    public function ChangeStatus($id)
    {

        $client = Client::findOrFail($id);
        $client->update([
            'status' => $client->status == 'نشط' ? '0' : '1',
        ]);
        return $this->success_message('تم تغير الحالة بنجاح');
    }


    public function transactions(Request $request, $id)
    {
        // جلب العميل
        $client = Client::findOrFail($id);
        $safes = Safe::active()->get();

        // جلب معلمات التاريخ من الطلب
        $fromDate = $request->query('from_date') ?? null;
        $toDate = $request->query('to_date') ?? null;

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
        $query = SaleInvoice::where('client_id', $client->id);
        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        }
        $invoices = $query->orderBy('created_at', 'desc')->get();

        // جلب إجمالي قيم الفواتير ضمن الفترة
        $total_invoices = $invoices->sum('total_price');
        $total_returned = $invoices->where('return_status', 'returned')->sum('total_price');

        // حساب الرصيد الافتتاحي (إجمالي الفواتير قبل from_date)
        $opening_balance = 0;
        if ($fromDate) {
            $opening_balance = SaleInvoice::where('client_id', $client->id)
                ->where('created_at', '<', $fromDate)
                ->sum('total_price');
        }

        // جلب جميع المعاملات
        $transactions = ClientTransaction::where('client_id', $client->id)
            ->with('saleInvoice')
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                return $query->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->orderBy('id', 'desc')
            ->get();

        // حساب إجمالي المدفوع (Credit)
        $total_credit = $transactions->where('type', 'credit')->sum('amount');
        $total_debit = $transactions->where('type', 'debit')->sum('amount');

        // الرصيد المستحق = إجمالي الفواتير - إجمالي المدفوع
        $balance = $total_invoices - $total_credit;
        $client_balance = $total_debit - $total_credit;

        return view('admin.clients.transactions', compact(
            'client',
            'transactions',
            'total_invoices',
            'total_credit',
            'balance',
            'client_balance',
            'safes',
            'invoices',
            'fromDate',
            'toDate',
            'opening_balance'
        ));
    }

    public function AddTransaction(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $data = $request->all();
        //   dd($data);
        $rules = [
            'amount' => 'required|numeric|min:0.01',
            'invoice_id' => 'nullable|exists:sale_invoices,id',
            'safe_id' => 'required|exists:safes,id',
        ];
        $messages = [
            'amount.required' => 'المبلغ مطلوب',
            'amount.numeric' => 'المبلغ يجب أن يكون رقمًا',
            'amount.min' => 'المبلغ يجب أن يكون أكبر من 0',
            'invoice_id.exists' => 'الفاتورة غير موجودة',
            'safe_id.required' => 'الخزنة مطلوبة',
            'safe_id.exists' => 'الخزنة غير موجودة',
        ];

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (isset($data['invoice_id']) && $data['invoice_id'] != null) {
            $invoice = SaleInvoice::findOrFail($data['invoice_id']);
            if ($invoice->client_id != $client->id) {
                return redirect()->back()->withErrors(['invoice_id' => 'لا يمكن إضافة المعاملة لفاتورة عميل آخر']);
            }
            // حساب إجمالي المدفوع (Credit) والرصيد المستحق
            $total_balance = ClientTransaction::where('sale_invoice_id', $invoice->id)
                ->where('type', 'credit')
                ->sum('amount');
            $remaining_balance = $invoice->total_price - $total_balance;

            // التحقق إن الفاتورة لسه فيها رصيد مستحق
            if ($remaining_balance <= 0) {
                return redirect()->back()->withErrors(['amount' => 'تم تسديد الفاتورة بالكامل']);
            }

            // التحقق إن المبلغ المدخل مش أكبر من الرصيد المستحق
            if ($data['amount'] > $remaining_balance) {
                return redirect()->back()->withErrors(['amount' => 'المبلغ المدخل أكبر من الرصيد المستحق (' . $remaining_balance . ' د.ل)']);
            }
        }
        try {
            DB::beginTransaction();
            // تسجيل المعاملة
            $transaction = new ClientTransaction();
            $transaction->client_id = $client->id;
            $transaction->amount = $data['amount'];
            $transaction->sale_invoice_id = isset($data['invoice_id']) ? $data['invoice_id'] : null;
            $transaction->safe_id = $data['safe_id'];
            $transaction->type = 'credit';
            $transaction->description = $data['description'];
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
            $safeTransaction->client_id = $client->id;
            $safeTransaction->amount = $data['amount'];
            $safeTransaction->type = 'deposit';
            $safeTransaction->description = $data['description'];
            $safeTransaction->save();
            ############################################ End Add Transaction To Safe ###############################
            ################## Update Safe Balance #########
            $safe = Safe::findOrFail($data['safe_id']);
            $oldSafeBalance =  $safe->balance;
            $newSafeBalance = $oldSafeBalance + $data['amount'];
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

    ########################################### Generate Clients Pdf ##########################################
    public function ClientsPdf(Request $request)
    {
        $query = Client::latest();
        if ($request->has('client_ids')) {
            $client_ids = explode(',', $request->client_ids);
            if (is_array($request->client_ids)) {
                $query->whereIn('id', $request->client_ids);
            } else {
                $query->whereIn('id', explode(',', $request->client_ids));
            }
        }
        $clients = $query->get();
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
            <h4>تقرير عن العملاء </h4>
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
        foreach ($clients as $client) {
            $html .= '
                    <tr>
                        <td>' . $client->name . '</td>
                        <td>' . $client->mobile . '</td>
                        <td>' . $client->telegram . '</td>
                        <td>' . $client->whatsapp . '</td>
                        <td>' . number_format($client->balance(), 2) . '</td>
                        <td>' . ($client->balance() > 0 ? 'مدين' : ($client->balance() < 0 ? 'دائن' : '')) . '</td>
                        <td>' . $client->status . '</td>
                        <td>' . $client->created_at->format('Y-m-d') . '</td>
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
        return $mpdf->Output('تقرير عن العملاء.pdf', 'I'); // 'I' لعرض الملف في المتصفح

    }

    ######################################### Generate Clients Excel ############################

    public function ClientsExcel(Request $request)
    {
        $client_ids = null;
        if ($request->has('client_ids')) {
            if (is_array($request->client_ids)) {
                $client_ids = $request->client_ids;
            } else {
                $client_ids = explode(',', $request->client_ids);
            }
        }
        return (new ClientsExport($client_ids))->download('Clients.xlsx');
    }

    public function ClientsReportPdf(Request $request)
    {
        $query = Client::latest();

        if ($request->has('client_ids')) {
            $client_ids = explode(',', $request->client_ids); // Expecting comma-separated string from GET param if simple link, or array if form
            if (is_array($request->client_ids)) {
                $query->whereIn('id', $request->client_ids);
            } else {
                $query->whereIn('id', explode(',', $request->client_ids));
            }
        }

        $clients = $query->get();
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
            <h4>كشف شامل للعملاء </h4>
        </div>
            <table>
                <thead>
                    <tr>
                        <th> الاسم </th>
                        <th> الرصيد </th>
                        <th> دائن/مدين </th>
                    </tr>
                </thead>
                <tbody>';

        // تعبئة البيانات داخل الجدول
        foreach ($clients as $client) {
            $balance = $client->balance();
            $status = '';
            if ($balance > 0) {
                $status = 'مدين';
            } elseif ($balance < 0) {
                $status = 'دائن';
            }
            $html .= '
                    <tr>
                        <td>' . $client->name . '</td>
                        <td>' . number_format($balance, 2) . ' د.ل</td>
                        <td>' . $status . '</td>
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
        return $mpdf->Output('كشف_شامل_للعملاء.pdf', 'I'); // 'I' لعرض الملف في المتصفح
    }

    public function ClientsReportExcel(Request $request)
    {
        $client_ids = null;
        if ($request->has('client_ids')) {
            if (is_array($request->client_ids)) {
                $client_ids = $request->client_ids;
            } else {
                $client_ids = explode(',', $request->client_ids);
            }
        }
        return (new \App\Exports\ClientsReportExport($client_ids))->download('Clients_Report.xlsx');
    }

    public function UpdateTransaction(Request $request, $id)
    {
        $transaction = ClientTransaction::findOrFail($id);

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

        if ($transaction->type != 'credit') {
             return redirect()->back()->withErrors(['general' => 'لا يمكن تعديل هذه المعاملة']);
        }

        DB::beginTransaction();
        try {
            // 1. Revert Old Safe Balance (Assuming Credit = Deposit into Safe)
            $oldSafe = Safe::findOrFail($transaction->safe_id);
            $oldSafe->balance -= $transaction->amount;
            $oldSafe->save();

            // 2. Update Transaction
            $transaction->amount = $data['amount'];
            $transaction->safe_id = $data['safe_id'];
            $transaction->description = $data['description'];
            $transaction->save();

            // 3. Apply New Safe Balance
            $newSafe = Safe::findOrFail($data['safe_id']);
            $newSafe->balance += $data['amount'];
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
        $transaction = ClientTransaction::with('client', 'safe')->findOrFail($id);
        $setting = \App\Models\admin\Setting::first();
        return view('admin.clients.print_transaction', compact('transaction', 'setting'));
    }
}
