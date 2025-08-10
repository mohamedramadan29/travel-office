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
use App\Models\admin\SupplierTransaction;
use Illuminate\Support\Facades\Validator;
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

    public function transactions($id){
        $safes = Safe::active()->get();
        $supplier = Supplier::findOrFail($id);
        if(!$supplier){
            abort(404);
        }
        // جلب جميع المعاملات
        $transactions = SupplierTransaction::where('supplier_id', $supplier->id)
        ->with('purchaseInvoice')->orderBy('id', 'desc')
        ->get();

        // جلب إجمالي قيم الفواتير
        $total_invoices = PurcheInvoice::where('supplier_id', $supplier->id)
        ->sum('total_price');

        // حساب إجمالي المدفوع (Debit)
        $total_debit = $transactions->where('type', 'debit')->sum('amount');
        $invoices = PurcheInvoice::where('supplier_id',$supplier->id)->get();
        // الرصيد المستحق = إجمالي الفواتير - إجمالي المدفوع
        $balance = $total_invoices - $total_debit; // 1000 - 800 = 200 د.ل
        return view('admin.suppliers.transactions', compact('supplier','transactions','total_invoices','total_debit','balance','invoices','safes'));
    }



    public function AddTransaction(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $data = $request->all();
        $rules = [
            'amount' => 'required|numeric|min:0.01',
            'invoice_id' => 'required|exists:purche_invoices,id',
            'safe_id' => 'required|exists:safes,id',
        ];
        $messages = [
            'amount.required' => 'المبلغ مطلوب',
            'amount.numeric' => 'المبلغ يجب أن يكون رقمًا',
            'amount.min' => 'المبلغ يجب أن يكون أكبر من 0',
            'invoice_id.required' => 'رقم الفاتورة مطلوب',
            'invoice_id.exists' => 'الفاتورة غير موجودة',
            'safe_id.required' => 'الخزنة مطلوبة',
            'safe_id.exists' => 'الخزنة غير موجودة',
        ];

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

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

        try {
            DB::beginTransaction();

            // تسجيل المعاملة
            $transaction = new SupplierTransaction();
            $transaction->supplier_id = $supplier->id;
            $transaction->amount = $data['amount'];
            $transaction->purchase_invoice_id = $data['invoice_id'];
            $transaction->safe_id = $data['safe_id'];
            $transaction->type = 'debit';
            $transaction->description = 'تسديد دفعة لفاتورة #' . $data['invoice_id'];
            $transaction->save();

            // تحديث حالة الفاتورة (اختياري)
            $new_balance = $remaining_balance - $data['amount'];
            $invoice->update([
                'paid' => $total_balance + $data['amount'],
                'remaining' => $new_balance,
            ]);

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
            <h1>تقرير عن الموردين</h1>

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

        // إعداد mPDF
        $mpdf = new Mpdf([
            'default_font' => 'Cairo', // خط يدعم اللغة العربية
        ]);

        // تحميل المحتوى إلى ملف PDF
        $mpdf->WriteHTML($html);
        // توليد ملف PDF وإرساله للتنزيل
        return $mpdf->Output('تقرير عن الموردين.pdf', 'I'); // 'I' لعرض الملف في المتصفح

    }

    ######################################### Generate Suppliers Excel ############################

    public function SuppliersExcel(){
        return (new SuppliersExport())->download('Suppliers.xlsx');
    }



}
