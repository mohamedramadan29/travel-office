<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\admin\Safe;
use App\Models\admin\Client;
use App\Models\admin\Supplier;

class DoubleInvoiceCreate extends Component
{

    ######## Purches Invoice
    public $suppliers , $safes, $supplier_id, $supplier_mobile, $supplier_whatsapp, $supplier_email, $supplier_address;
    public $qyt = 1, $purches_price = 0, $total_price = 0, $paid = 0, $remaining = 0;
    public $payment_method, $safe_id;
    public $invoice;
    ######### Selling Invoice
    public $categories,$clients,$client_id,$client_mobile,$client_whatsapp;
    public $client_email,$client_address,$selling_price = 0,$selling_total_price = 0,$selling_paid = 0,$selling_remaining = 0;

    ##########################################################################


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
            $this->client_id = $this->invoice->client_id;
            $this->client_mobile = $this->invoice->client_mobile;
            $this->client_whatsapp = $this->invoice->client_whatsapp;
            $this->client_email = $this->invoice->client_email;
            $this->client_address = $this->invoice->client_address;
            $this->selling_price = $this->invoice->selling_price;
            $this->selling_total_price = $this->invoice->selling_total_price;
            $this->selling_paid = $this->invoice->selling_paid;
            $this->selling_remaining = $this->invoice->selling_remaining;
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
            $this->client_id = old('client_id');
            $this->client_mobile = old('client_mobile');
            $this->client_whatsapp = old('client_whatsapp');
            $this->client_email = old('client_email');
            $this->client_address = old('client_address');
            $this->selling_price = old('selling_price', 0);
            $this->selling_total_price = old('selling_total_price', 0);
            $this->selling_paid = old('selling_paid', 0);
            $this->selling_remaining = old('selling_remaining', 0);
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

    #####################################


    public function getClientInfo()
    {
        if (!$this->client_id) {
            $this->resetClientInfo();
            return;
        }

        $client = Client::find($this->client_id);
        if ($client) {
            $this->client_mobile = $client->mobile ?? '';
            $this->client_whatsapp = $client->whatsapp ?? '';
            $this->client_email = $client->email ?? '';
            $this->client_address = $client->address ?? '';
        } else {
            $this->resetClientInfo();
        }
    }

    private function resetClientInfo()
    {
        $this->client_mobile = '';
        $this->client_whatsapp = '';
        $this->client_email = '';
        $this->client_address = '';
    }

    public function updated($propertyName)
    {
        // تحديث السعر الكلي والباقي عند تغيير qyt أو purches_price
        if (in_array($propertyName, ['qyt', 'purches_price','selling_price'])) {
            $this->calculateTotalPrice();
            $this->calculateRemaining();
            $this->calculateSellingTotalPrice();
            $this->calculateSellingRemaining();
        }

        // تحديث الباقي عند تغيير paid
        if ($propertyName === 'paid') {
            $this->paid = $this->paid ?? 0; // التأكد من أن paid ليس null
            if($this->paid > $this->total_price){
                $this->paid = $this->total_price;
            }
            if($this->paid < 0 || $this->paid == null){
               return ;
            }
            $this->calculateRemaining();
            // $this->calculateSellingRemaining();
        }
        if ($propertyName === 'selling_paid') {
            $this->selling_paid = $this->selling_paid ?? 0; // التأكد من أن selling_paid ليس null
            if($this->selling_paid > $this->selling_total_price){
                $this->selling_paid = $this->selling_total_price;
            }
            if($this->selling_paid < 0 || $this->selling_paid == null){
               return ;
            }
            $this->calculateSellingRemaining();
        }


        // تحديث بيانات المورد عند تغيير supplier_id
        if ($propertyName === 'supplier_id') {
            $this->getSupplierInfo();
        }
        if ($propertyName === 'client_id') {
            $this->getClientInfo();
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

    ###################################### Selling Data Number ###########



    public function calculateSellingTotalPrice()
{
    // التأكد من أن qyt و selling_price أرقام
    $qyt = is_numeric($this->qyt) ? (float)$this->qyt : 0;
    $selling_price = is_numeric($this->selling_price) ? (float)$this->selling_price : 0;

    $this->selling_total_price = ($qyt && $selling_price) ? $qyt * $selling_price : 0;
}

public function calculateSellingRemaining()
{
    // التأكد من أن selling_paid ليس null وهو رقم
    $this->selling_paid = is_numeric($this->selling_paid) ? (float)$this->selling_paid : 0;

    // التأكد من أن selling_total_price رقم
    $selling_total_price = is_numeric($this->selling_total_price) ? (float)$this->selling_total_price : 0;

    // حساب المتبقي
    $this->selling_remaining = ($this->selling_paid > $selling_total_price) ? 0 : $selling_total_price - $this->selling_paid;
}





    public function render()
    {
        return view('livewire.dashboard.double-invoice-create');
    }
}
