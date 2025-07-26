<?php

namespace App\Http\Controllers\dashboard;

use App\Models\admin\Safe;
use App\Models\admin\Client;
use Illuminate\Http\Request;
use App\Models\admin\SaleInvoice;
use App\Http\Traits\Message_Trait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\admin\ClientTransaction;
use Illuminate\Support\Facades\Validator;

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
      return $this->success_message('تم اضافة المورد بنجاح');
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
        return $this->success_message('تم تحديث المورد بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        return $this->success_message('تم حذف المورد بنجاح');
    }

    public function ChangeStatus($id){

        $client = Client::findOrFail($id);
        $client->update([
            'status' => $client->status == 'نشط' ? '0' : '1',
        ]);
        return $this->success_message('تم تغير الحالة بنجاح');
    }

    public function transactions($id){

    // جلب العميل
    $client = Client::findOrFail($id);
    $safes = Safe::active()->get();

    // جلب جميع المعاملات
    $transactions = ClientTransaction::where('client_id', $client->id)
        ->with('saleInvoice') // لعرض تفاصيل فاتورة البيع إذا وجدت
        ->get();

    // جلب إجمالي قيم فواتير البيع
    $total_invoices = SaleInvoice::where('client_id', $client->id)
        ->sum('total_price'); // إجمالي قيم الفواتير (مثلًا 1000 د.ل)

    // حساب إجمالي المدفوع (Credit)
    $total_credit = $transactions->where('type', 'credit')->sum('amount'); // 200 + 600 = 800 د.ل

    // الرصيد المستحق = إجمالي الفواتير - إجمالي المدفوع
    $balance = $total_invoices - $total_credit; // 1000 - 800 = 200 د.ل
    $invoices = SaleInvoice::where('client_id', $client->id)->get();

    return view('admin.clients.transactions', compact('client', 'transactions', 'total_invoices', 'total_credit', 'balance','safes','invoices'));

    }

    public function AddTransaction(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $data = $request->all();
        $rules = [
            'amount' => 'required|numeric|min:0.01',
            'invoice_id' => 'required|exists:sale_invoices,id',
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

        try {
            DB::beginTransaction();

            // تسجيل المعاملة
            $transaction = new ClientTransaction();
            $transaction->client_id = $client->id;
            $transaction->amount = $data['amount'];
            $transaction->sale_invoice_id = $data['invoice_id'];
            $transaction->safe_id = $data['safe_id'];
            $transaction->type = 'credit';
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

}
