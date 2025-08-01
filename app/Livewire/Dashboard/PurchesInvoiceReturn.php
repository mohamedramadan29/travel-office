<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\admin\Safe;
use App\Models\admin\Supplier;
class PurchesInvoiceReturn extends Component
{
    public $suppliers;
    public $safes;
    public $supplier_id;
    public $supplier_mobile;
    public $supplier_whatsapp;
    public $supplier_email;
    public $supplier_address;
    public $qyt = 1;
    public $purches_price = 0;
    public $total_price = 0;
    public $paid = 0;
    public $remaining = 0;
    public $payment_method;
    public $safe_id;
    public $invoice;
    public $return_price;

    public function mount($invoice = null)
    {
        $this->invoice = $invoice;

        // جلب بيانات الموردين والخزائن
        $this->suppliers = Supplier::active()->get();
        $this->safes = Safe::active()->get();

        // إذا كانت هناك فاتورة (وضع التعديل)
        if ($this->invoice) {
            $this->qyt = $this->invoice->qyt;
            $this->purches_price = $this->invoice->purches_price;
            $this->supplier_id = $this->invoice->supplier_id;
            $this->paid = $this->invoice->paid;
            $this->total_price = $this->invoice->total_price;
            $this->remaining = $this->invoice->remaining;
            $this->payment_method = $this->invoice->payment_method;
            $this->safe_id = $this->invoice->safe_id;
            $this->return_price = $this->total_price;
        } else {
            // إذا لم تكن هناك فاتورة (وضع الإضافة)، استخدم القيم القديمة أو الافتراضية
            $this->qyt = old('qyt', 1);
            $this->purches_price = old('purches_price', 0);
            $this->supplier_id = old('supplier_id');
            $this->paid = old('paid', 0);
            $this->total_price = old('total_price', 0);
            $this->remaining = old('remaining', 0);
            $this->payment_method = old('payment_method');
            $this->safe_id = old('safe_id');
        }

        // تحميل بيانات المورد إذا كان هناك supplier_id
        if ($this->supplier_id) {
            $this->getSupplierInfo();
        }

        // حساب السعر الكلي والباقي عند التحميل
        $this->calculateTotalPrice();
        $this->calculateRemaining();
    }

    public function getSupplierInfo()
    {
        if (!$this->supplier_id) {
            $this->resetSupplierInfo();
            return;
        }

        $supplier = Supplier::find($this->supplier_id);
        if ($supplier) {
            $this->supplier_mobile = $supplier->mobile ?? '';
            $this->supplier_whatsapp = $supplier->whatsapp ?? '';
            $this->supplier_email = $supplier->email ?? '';
            $this->supplier_address = $supplier->address ?? '';
        } else {
            $this->resetSupplierInfo();
        }
    }

    private function resetSupplierInfo()
    {
        $this->supplier_mobile = '';
        $this->supplier_whatsapp = '';
        $this->supplier_email = '';
        $this->supplier_address = '';
    }

    public function updated($propertyName)
    {
        // تحديث السعر الكلي والباقي عند تغيير qyt أو purches_price
        if (in_array($propertyName, ['qyt', 'purches_price'])) {
            $this->calculateTotalPrice();
            $this->calculateRemaining();
        }

        // تحديث الباقي عند تغيير paid
        if ($propertyName === 'paid') {
            $this->calculateRemaining();
        }

        // تحديث بيانات المورد عند تغيير supplier_id
        if ($propertyName === 'supplier_id') {
            $this->getSupplierInfo();
        }
    }

    public function calculateTotalPrice()
    {
        $this->total_price = ($this->qyt && $this->purches_price) ? $this->qyt * $this->purches_price : 0;
    }

    public function calculateRemaining()
    {
        $this->paid = $this->paid ?? 0; // التأكد من أن paid ليس null
        $this->remaining = ($this->paid > $this->total_price) ? 0 : $this->total_price - $this->paid;
    }


    public function render()
    {
        return view('livewire.dashboard.purches-invoice-return');
    }
}
