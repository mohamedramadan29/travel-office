<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\admin\Safe;
use App\Models\admin\Supplier;
use App\Models\admin\Category;
use App\Models\admin\PurcheInvoice;
use App\Models\admin\SupplierTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchesInvoiceEdit extends Component
{
    public $suppliers;
    public $safes;
    public $categories;
    public $supplier_id;
    public $supplier_mobile;
    public $supplier_whatsapp;
    public $supplier_email;
    public $supplier_address;

    // Invoice properties
    public $invoice;
    public $invoice_id;
    public $bayan_txt;
    public $referance_number;
    public $category_id;
    public $type;
    public $qyt;
    public $purches_price;
    public $total_price;
    public $paid;
    public $remaining;
    public $safe_id;

    public function mount(PurcheInvoice $invoice)
    {
        $this->invoice = $invoice;
        $this->invoice_id = $invoice->id;

        // جلب بيانات الموردين والخزائن والتصنيفات
        $this->suppliers = Supplier::active()->get();
        $this->safes = Safe::active()->get();
        $this->categories = Category::active()->get();

        // تحميل بيانات الفاتورة الموجودة
        $this->loadInvoiceData();
    }

    public function loadInvoiceData()
    {
        $this->bayan_txt = $this->invoice->bayan_txt;
        $this->referance_number = $this->invoice->referance_number;
        $this->type = $this->invoice->type;
        $this->supplier_id = $this->invoice->supplier_id;
        $this->category_id = $this->invoice->category_id;
        $this->qyt = $this->invoice->qyt;
        $this->purches_price = $this->invoice->purches_price;
        $this->total_price = $this->invoice->total_price;
        $this->paid = $this->invoice->paid;
        $this->remaining = $this->invoice->remaining;
        $this->safe_id = $this->invoice->safe_id;

        // تحميل بيانات المورد
        if ($this->supplier_id) {
            $this->getSupplierInfo();
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

    public function refreshSuppliers()
    {
        $this->suppliers = Supplier::active()->get();
    }

    public function updatedQyt()
    {
        $this->calculateTotalPrice();
    }

    public function updatedPurchesPrice()
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
        $purches_price = is_numeric($this->purches_price) ? (float)$this->purches_price : 0;
        $this->total_price = $qyt * $purches_price;
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
        return $this->validate([
            'bayan_txt' => 'required|string|max:255',
            'referance_number' => 'nullable|string|max:100',
            'supplier_id' => 'required|exists:suppliers,id',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:فاتورة مؤقتة,فاتورة رسمية',
            'qyt' => 'required|numeric|min:1',
            'purches_price' => 'required|numeric|min:0',
            'paid' => 'nullable|numeric|min:0',
            'safe_id' => 'required_if:paid,>,0|exists:safes,id'
        ], [
            'bayan_txt.required' => 'البيان مطلوب',
            'supplier_id.required' => 'المورد مطلوب',
            'supplier_id.exists' => 'المورد المحدد غير موجود',
            'category_id.required' => 'التصنيف مطلوب',
            'category_id.exists' => 'التصنيف المحدد غير موجود',
            'type.required' => 'نوع الفاتورة مطلوب',
            'type.in' => 'نوع الفاتورة غير صحيح',
            'qyt.required' => 'الكمية مطلوبة',
            'qyt.numeric' => 'الكمية يجب أن تكون رقماً',
            'qyt.min' => 'الكمية يجب أن تكون أكبر من صفر',
            'purches_price.required' => 'سعر الشراء مطلوب',
            'purches_price.numeric' => 'سعر الشراء يجب أن يكون رقماً',
            'purches_price.min' => 'سعر الشراء يجب أن يكون أكبر من أو يساوي صفر',
            'paid.numeric' => 'المبلغ المدفوع يجب أن يكون رقماً',
            'paid.min' => 'المبلغ المدفوع يجب أن يكون أكبر من أو يساوي صفر',
            'safe_id.required_if' => 'الخزينة مطلوبة عند دفع مبلغ',
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
                'supplier_id' => $validatedData['supplier_id'],
                'category_id' => $validatedData['category_id'],
                'type' => $validatedData['type'],
                'qyt' => $validatedData['qyt'],
                'purches_price' => $validatedData['purches_price'],
                'total_price' => $this->total_price,
                'paid' => $validatedData['paid'] ?? 0,
                'remaining' => $this->remaining,
                'safe_id' => $validatedData['safe_id'] ?? null,
                // 'updated_by' => Auth::id(),
                'updated_at' => now()
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

            session()->flash('message', 'تم تحديث الفاتورة بنجاح');
            return redirect()->route('dashboard.purches_invoices.index');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'حدث خطأ أثناء تحديث الفاتورة: ' . $e->getMessage());
        }
    }

    private function handlePaymentChange($paidDifference, $safe_id)
    {
        if ($paidDifference > 0) {
            // مبلغ إضافي تم دفعه - إضافة معاملة دفع جديدة
            SupplierTransaction::create([
                'supplier_id' => $this->supplier_id,
                'purchase_invoice_id' => $this->invoice->id,
                'safe_id' => $safe_id,
                'amount' => $paidDifference,
                'transaction_type' => 'payment',
                'payment_method' => 'cash',
                'notes' => 'دفعة إضافية - تعديل فاتورة رقم ' . $this->invoice->id,
                'created_by' => Auth::id()
            ]);

            // تحديث رصيد الخزينة
            $safe = \App\Models\admin\Safe::find($safe_id);
            if ($safe) {
                $safe->decrement('balance', $paidDifference);
            }

        } elseif ($paidDifference < 0) {
            // مبلغ تم إرجاعه - إضافة معاملة إرجاع
            $returnAmount = abs($paidDifference);

            SupplierTransaction::create([
                'supplier_id' => $this->supplier_id,
                'purchase_invoice_id' => $this->invoice->id,
                'safe_id' => $safe_id,
                'amount' => $returnAmount,
                'transaction_type' => 'refund',
                'payment_method' => 'cash',
                'notes' => 'استرداد - تعديل فاتورة رقم ' . $this->invoice->id,
                'created_by' => Auth::id()
            ]);

            // تحديث رصيد الخزينة
            $safe = \App\Models\admin\Safe::find($safe_id);
            if ($safe) {
                $safe->increment('balance', $returnAmount);
            }
        }
    }

    public function render()
    {
        return view('livewire.dashboard.purches-invoice-edit');
    }
}
