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
        $clients = Client::paginate(10);
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
        $client = new Client();
        $client->name = $data['name'];
        $client->email = $data['email'];
        $client->mobile = $data['mobile'];
        $client->telegram = $data['telegram'];
        $client->whatsapp = $data['whatsapp'];
        $client->status = $data['status'];
        $client->address = $data['address'];
        $client->save();
      return $this->success_message('تم اضافة العميل بنجاح');
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

    public function ChangeStatus($id){

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

        // الرصيد المستحق = إجمالي الفواتير - إجمالي المدفوع
        $balance = $total_invoices - $total_credit;

        return view('admin.clients.transactions', compact(
            'client',
            'transactions',
            'total_invoices',
            'total_credit',
            'balance',
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

        if( isset($data['invoice_id']) && $data['invoice_id'] !=null){
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
                 $safeTransaction->client_id = $client->id;
                 $safeTransaction->amount = $data['amount'];
                 $safeTransaction->type = 'deposit';
                 $safeTransaction->description = ' اضافة دفعة من  العميل  [ ' . $client->name . ' ]';
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
      public function ClientsPdf(){
        $clients = Client::latest()->get();
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

    public function ClientsExcel(){
        return (new ClientsExport())->download('Clients.xlsx');
    }


}
