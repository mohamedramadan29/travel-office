<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\admin\Safe;
use App\Models\admin\Client;
use App\Models\admin\Supplier;
use App\Models\admin\Category;
use App\Models\admin\SellingInvoice;
use App\Models\admin\PurcheInvoice;
use App\Models\admin\ClientTransaction;
use App\Models\admin\SaleInvoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SellingInvoiceEdit extends Component
{
    public $suppliers;
    public $safes;
    public $categories;
    public $clients;
    public $purchesInvoices;
    public $supplier_id;
    public $supplier_mobile;
    public $supplier_whatsapp;
    public $supplier_email;
    public $supplier_address;
    public $client_id;
    public $client_mobile;
    public $client_whatsapp;
    public $client_email;
    public $client_address;

    // Invoice properties
    public $invoice;
    public $invoice_id;
    public $bayan_txt;
    public $referance_number;
    public $category_id;
    public $qyt;
    public $selling_price;
    public $total_price;
    public $paid;
    public $remaining;
    public $safe_id;

    public function mount(SaleInvoice $invoice)
    {
        $this->invoice = $invoice;
        $this->invoice_id = $invoice->id;

        // جلب بيانات العملاء والموردين والخزائن والتصنيفات
        $this->clients = Client::active()->get();
        $this->suppliers = Supplier::active()->get();
        $this->safes = Safe::active()->get();
        $this->categories = Category::active()->get();
        $this->purchesInvoices = PurcheInvoice::all();

        // تحميل بيانات الفاتورة الموجودة
        $this->loadInvoiceData();
    }

    public function loadInvoiceData()
    {
        $this->bayan_txt = $this->invoice->bayan_txt;
        $this->referance_number = $this->invoice->referance_number;
        $this->client_id = $this->invoice->client_id;
        $this->supplier_id = $this->invoice->supplier_id;
        $this->category_id = $this->invoice->category_id;
        $this->qyt = $this->invoice->qyt;
        $this->selling_price = $this->invoice->selling_price;
        $this->total_price = $this->invoice->total_price;
        $this->paid = $this->invoice->paid;
        $this->remaining = $this->invoice->remaining;
        $this->safe_id = $this->invoice->safe_id;

        // تحميل بيانات العميل والمورد
        if ($this->client_id) {
            $this->getClientInfo();
        }
        if ($this->supplier_id) {
            $this->getSupplierInfo();
        }
    }

    public function updatedClient_id()
    {
        if ($this->client_id) {
            $this->getClientInfo();
        } else {
            $this->resetClientInfo();
        }
    }

    public function updatedSupplier_id()
    {
        if ($this->supplier_id) {
            $this->getSupplierInfo();
        } else {
            $this->resetSupplierInfo();
        }
    }

    public function getClientInfo()
    {
        if (!$this->client_id) {
            $this->resetClientInfo();
            return;
        }

        $client = Client::find($this->client_id);
        if ($client) {
            $this->client_mobile = $client->mobile;
            $this->client_whatsapp = $client->whatsapp;
            $this->client_email = $client->email;
            $this->client_address = $client->address;

            // Emit event to update JavaScript
            $this->dispatch('client-updated');
        } else {
            $this->resetClientInfo();
        }
    }

    public function resetClientInfo()
    {
        $this->client_mobile = '';
        $this->client_whatsapp = '';
        $this->client_email = '';
        $this->client_address = '';
    }

    public function getSupplierInfo()
    {
        if (!$this->supplier_id) {
            $this->resetSupplierInfo();
            return;
        }

        $supplier = Supplier::find($this->supplier_id);
        if ($supplier) {
            $this->supplier_mobile = $supplier->mobile;
            $this->supplier_whatsapp = $supplier->whatsapp;
            $this->supplier_email = $supplier->email;
            $this->supplier_address = $supplier->address;

            // Emit event to update JavaScript
            $this->dispatch('supplier-updated');
        } else {
            $this->resetSupplierInfo();
        }
    }

    public function resetSupplierInfo()
    {
        $this->supplier_mobile = '';
        $this->supplier_whatsapp = '';
        $this->supplier_email = '';
        $this->supplier_address = '';
    }

    public function refreshClients()
    {
        $this->clients = Client::active()->get();
    }

    public function refreshSuppliers()
    {
        $this->suppliers = Supplier::active()->get();
    }

    public function updatedQyt()
    {
        $this->calculateTotalPrice();
    }

    public function updatedSellingPrice()
    {
        $this->calculateTotalPrice();
    }

    public function updatedPaid()
    {
        $this->calculateRemaining();
    }

    public function calculateTotalPrice()
    {
        $qyt = is_numeric($this->qyt) ? (float)$this->qyt : 0;
        $selling_price = is_numeric($this->selling_price) ? (float)$this->selling_price : 0;
        $this->total_price = $qyt * $selling_price;
        $this->calculateRemaining();
    }

    public function calculateRemaining()
    {
        $paid = is_numeric($this->paid) ? (float)$this->paid : 0;
        $total_price = is_numeric($this->total_price) ? (float)$this->total_price : 0;

        if ($paid > $total_price) {
            $this->paid = $total_price;
            $paid = $total_price;
        }

        $this->remaining = $total_price - $paid;
    }

    protected function validateInvoiceData()
    {
        $rules = [
            'bayan_txt' => 'required|string|max:255',
            'referance_number' => 'required|string|max:100|unique:sale_invoices,referance_number,' . $this->invoice->id,
            'client_id' => 'required|exists:clients,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'category_id' => 'required|exists:categories,id',
            'qyt' => 'required|numeric|min:1',
            'selling_price' => 'required|numeric|min:0',
            'paid' => 'nullable|numeric|min:0',
        ];

        // Only require safe_id if paid amount is greater than 0
        if ($this->paid > 0) {
            $rules['safe_id'] = 'required|exists:safes,id';
        }

        return $this->validate($rules, [
            'bayan_txt.required' => 'البيان مطلوب',
            'referance_number.required' => 'الرقم المرجعي مطلوب',
            'referance_number.unique' => 'الرقم المرجعي موجود مسبقاً',
            'client_id.required' => 'العميل مطلوب',
            'client_id.exists' => 'العميل المحدد غير موجود',
            'supplier_id.required' => 'المورد مطلوب',
            'supplier_id.exists' => 'المورد المحدد غير موجود',
            'category_id.required' => 'التصنيف مطلوب',
            'category_id.exists' => 'التصنيف المحدد غير موجود',
            'qyt.required' => 'الكمية مطلوبة',
            'qyt.numeric' => 'الكمية يجب أن تكون رقماً',
            'qyt.min' => 'الكمية يجب أن تكون أكبر من صفر',
            'selling_price.required' => 'سعر البيع مطلوب',
            'selling_price.numeric' => 'سعر البيع يجب أن يكون رقماً',
            'selling_price.min' => 'سعر البيع يجب أن يكون أكبر من أو يساوي صفر',
            'paid.numeric' => 'المبلغ المدفوع يجب أن يكون رقماً',
            'paid.min' => 'المبلغ المدفوع يجب أن يكون أكبر من أو يساوي صفر',
            'safe_id.required' => 'الخزينة مطلوبة عند دفع مبلغ',
            'safe_id.exists' => 'الخزينة المحددة غير موجودة'
        ]);
    }

    public function updateInvoice()
    {
        try {
            // التحقق من صحة البيانات
            $validatedData = $this->validateInvoiceData();

            DB::beginTransaction();

            // إعداد البيانات للتحديث
            $invoiceData = [
                'bayan_txt' => $validatedData['bayan_txt'],
                'referance_number' => $validatedData['referance_number'],
                'client_id' => $validatedData['client_id'],
                'supplier_id' => $validatedData['supplier_id'],
                'category_id' => $validatedData['category_id'],
                'qyt' => $validatedData['qyt'],
                'selling_price' => $validatedData['selling_price'],
                'total_price' => $this->total_price,
                'paid' => $validatedData['paid'] ?? 0,
                'remaining' => $this->remaining,
                'safe_id' => $validatedData['safe_id'] ?? null,
                // 'updated_by' => Auth::id(),
                // 'updated_at' => now()
            ];

            // حساب الفرق في المبلغ المدفوع للتعامل مع المعاملات المالية
            $oldPaid = $this->invoice->paid;
            $newPaid = $invoiceData['paid'];
            $paidDifference = $newPaid - $oldPaid;

            // تحديث الفاتورة
            $this->invoice->update($invoiceData);

            // التعامل مع المعاملات المالية إذا تغير المبلغ المدفوع
            if ($paidDifference != 0 && $invoiceData['safe_id']) {
                $this->handlePaymentChange($paidDifference, $invoiceData['safe_id']);
            }

            DB::commit();

            session()->flash('message', 'تم تحديث فاتورة البيع بنجاح');
            // return redirect()->route('dashboard.sale_invoices.index');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'حدث خطأ أثناء تحديث الفاتورة: ' . $e->getMessage());
        }
    }

    private function handlePaymentChange($paidDifference, $safe_id)
    {
        if ($paidDifference > 0) {
            // مبلغ إضافي تم دفعه - إضافة معاملة دفع جديدة
            ClientTransaction::create([
                'client_id' => $this->client_id,
                'sale_invoice_id' => $this->invoice->id,
                'safe_id' => $safe_id,
                'amount' => $paidDifference,
                'type' => 'debit',
                // 'payment_method' => 'cash',
                'description' => 'دفعة إضافية - تعديل فاتورة بيع رقم ' . $this->invoice->id,
                // 'created_by' => Auth::id()
            ]);

            // تحديث رصيد الخزينة (إضافة رصيد)
            $safe = \App\Models\admin\Safe::find($safe_id);
            if ($safe) {
                $safe->increment('balance', $paidDifference);
            }
        } elseif ($paidDifference < 0) {
            // مبلغ تم إرجاعه - إضافة معاملة إرجاع
            $returnAmount = abs($paidDifference);

            ClientTransaction::create([
                'client_id' => $this->client_id,
                'sale_invoice_id' => $this->invoice->id,
                'safe_id' => $safe_id,
                'amount' => $returnAmount,
                'type' => 'credit',
                // 'payment_method' => 'cash',
                'description' => 'استرداد - تعديل فاتورة بيع رقم ' . $this->invoice->id,
                // 'created_by' => Auth::id()
            ]);

            // تحديث رصيد الخزينة (خصم رصيد)
            $safe = \App\Models\admin\Safe::find($safe_id);
            if ($safe) {
                $safe->decrement('balance', $returnAmount);
            }
        }
    }

    public function render()
    {
        $this->purchesInvoices = PurcheInvoice::all();
        return view('livewire.dashboard.selling-invoice-edit');
    }
}
