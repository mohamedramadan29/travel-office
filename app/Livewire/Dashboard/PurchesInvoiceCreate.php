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

class PurchesInvoiceCreate extends Component
{
    public $suppliers;
    public $safes;
    public $categories;
    public $supplier_id;
    public $supplier_mobile;
    public $supplier_whatsapp;
    public $supplier_email;
    public $supplier_address;

    // Multi-invoice support
    public $invoices = [];
    public $invoice_count = 1;

    // Common invoice data
    public $category_id;
    public $type = 'فاتورة مؤقتة'; // النوع الافتراضي

    // Keep original properties for backward compatibility
    public $qyt = 1;
    public $purches_price = 0;
    public $total_price = 0;
    public $paid = 0;
    public $remaining = 0;
    public $payment_method;
    public $safe_id;

    // Add Supplier Modal Properties
    public $showAddSupplierModal = false;
    public $newSupplierName = '';
    public $newSupplierMobile = '';
    public $newSupplierEmail = '';
    public $newSupplierWhatsapp = '';
    public $newSupplierAddress = '';
    public $modalErrorMessage = '';



    public function mount()
    {
        // جلب بيانات الموردين والخزائن والتصنيفات
        $this->suppliers = Supplier::active()->get();
        $this->safes = Safe::active()->get();
        $this->categories = Category::active()->get();

        // Initialize first invoice
        $this->initializeInvoices();

        // تحديد نوع الفاتورة من URL أو old values
        $urlType = request()->type;
        if($urlType == 'official') {
            $this->type = 'فاتورة رسمية';
        } else {
            $this->type = old('type', 'فاتورة مؤقتة');
        }

        $this->qyt = old('qyt', 1);
        $this->purches_price = old('purches_price', 0);
        $this->supplier_id = old('supplier_id');
        $this->paid = old('paid', 0);
        $this->total_price = old('total_price', 0);
        $this->remaining = old('remaining', 0);
        $this->payment_method = old('payment_method');
        $this->safe_id = old('safe_id');

        // تحميل بيانات المورد إذا كان هناك supplier_id
        if ($this->supplier_id) {
            $this->getSupplierInfo();
        }

        // حساب السعر الكلي والباقي عند التحميل
        $this->calculateTotalPrice();
        $this->calculateRemaining();
    }

    public function initializeInvoices()
    {
        // Initialize with one empty invoice
        $this->invoices = [
            [
                'bayan_txt' => '',
                'referance_number' => '',
                'qyt' => 1,
                'purches_price' => 0,
                'total_price' => 0,
                'paid' => 0,
                'remaining' => 0,
                'safe_id' => null
            ]
        ];
        $this->invoice_count = 1;
    }

    public function addNewInvoice()
    {
        $this->invoices[] = [
            'bayan_txt' => '',
            'referance_number' => '',
            'qyt' => 1,
            'purches_price' => 0,
            'total_price' => 0,
            'paid' => 0,
            'remaining' => 0,
            'safe_id' => null
        ];
        $this->invoice_count++;

        // Emit event to JavaScript
        $this->dispatch('invoice-added');
    }

    public function removeInvoice($index)
    {
        if (count($this->invoices) > 1) {
            unset($this->invoices[$index]);
            $this->invoices = array_values($this->invoices); // Re-index array
            $this->invoice_count--;

            // Emit event to JavaScript
            $this->dispatch('invoice-removed');
        }
    }

    public function calculateInvoiceTotal($index)
    {
        if (isset($this->invoices[$index])) {
            $qyt = is_numeric($this->invoices[$index]['qyt']) ? (float)$this->invoices[$index]['qyt'] : 0;
            $purches_price = is_numeric($this->invoices[$index]['purches_price']) ? (float)$this->invoices[$index]['purches_price'] : 0;

            $this->invoices[$index]['total_price'] = $qyt * $purches_price;
            $this->calculateInvoiceRemaining($index);
        }
    }

    public function calculateInvoiceRemaining($index)
    {
        if (isset($this->invoices[$index])) {
            $paid = is_numeric($this->invoices[$index]['paid']) ? (float)$this->invoices[$index]['paid'] : 0;
            $total_price = is_numeric($this->invoices[$index]['total_price']) ? (float)$this->invoices[$index]['total_price'] : 0;

            $this->invoices[$index]['remaining'] = ($paid > $total_price) ? 0 : $total_price - $paid;
        }
    }

    public function updatedInvoices($value, $key)
    {
        // Parse the key to get index and field
        $keyParts = explode('.', $key);
        $index = (int)$keyParts[0];
        $field = $keyParts[1] ?? null;

        if ($field === 'qyt' || $field === 'purches_price') {
            $this->calculateInvoiceTotal($index);
        } elseif ($field === 'paid') {
            $this->calculateInvoiceRemaining($index);
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
            $this->supplier_mobile = $supplier->mobile ?? '';
            $this->supplier_whatsapp = $supplier->whatsapp ?? '';
            $this->supplier_email = $supplier->email ?? '';
            $this->supplier_address = $supplier->address ?? '';
        } else {
            $this->resetSupplierInfo();
        }

        // إرسال حدث لتحديث JavaScript
        $this->dispatch('supplier-updated');
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

    public function refreshSuppliers()
    {
        $this->suppliers = Supplier::active()->get();
    }

    public function openAddSupplierModal()
    {
        $this->showAddSupplierModal = true;
        $this->resetSupplierForm();
    }

    public function closeAddSupplierModal()
    {
        $this->showAddSupplierModal = false;
        $this->resetSupplierForm();
    }

    public function resetSupplierForm()
    {
        $this->newSupplierName = '';
        $this->newSupplierMobile = '';
        $this->newSupplierEmail = '';
        $this->newSupplierWhatsapp = '';
        $this->newSupplierAddress = '';
        $this->modalErrorMessage = '';
        $this->resetValidation([
            'newSupplierName',
            'newSupplierMobile',
            'newSupplierEmail',
            'newSupplierWhatsapp',
            'newSupplierAddress'
        ]);
    }

    public function saveSupplier()
    {
        // Clear previous error message
        $this->modalErrorMessage = '';

        // Validate supplier data
        $this->validate([
            'newSupplierName' => 'required|string|max:255',
            'newSupplierMobile' => 'required|string|max:20',
            'newSupplierEmail' => 'nullable|email|max:255',
            'newSupplierWhatsapp' => 'nullable|string|max:20',
            'newSupplierAddress' => 'nullable|string|max:500',
        ], [
            'newSupplierName.required' => 'اسم المورد مطلوب',
            'newSupplierName.max' => 'اسم المورد يجب ألا يتجاوز 255 حرف',
            'newSupplierMobile.required' => 'رقم الهاتف مطلوب',
            'newSupplierMobile.max' => 'رقم الهاتف يجب ألا يتجاوز 20 رقم',
            'newSupplierEmail.email' => 'البريد الإلكتروني غير صحيح',
            'newSupplierEmail.max' => 'البريد الإلكتروني يجب ألا يتجاوز 255 حرف',
            'newSupplierWhatsapp.max' => 'رقم الواتساب يجب ألا يتجاوز 20 رقم',
            'newSupplierAddress.max' => 'العنوان يجب ألا يتجاوز 500 حرف',
        ]);

        try {
            // Create new supplier
            $supplier = Supplier::create([
                'name' => $this->newSupplierName,
                'mobile' => $this->newSupplierMobile,
                'email' => $this->newSupplierEmail ?: null,
                'whatsapp' => $this->newSupplierWhatsapp ?: null,
                'address' => $this->newSupplierAddress ?: null,
                'status' => 1,
            ]);

            // Refresh suppliers list
            $this->refreshSuppliers();

            // Set the newly created supplier as selected
            $this->supplier_id = $supplier->id;
            $this->getSupplierInfo();

            // Close modal and reset form
            $this->closeAddSupplierModal();

            // Show success message
            session()->flash('message', '✅ تم إضافة المورد بنجاح!');

            // Dispatch event to update Select2 with supplier details
            $this->dispatch('supplier-added', [
                'supplierId' => $supplier->id,
                'supplierName' => $supplier->name
            ]);

        } catch (\Exception $e) {
            // Display error in modal instead of session flash
            $this->modalErrorMessage = 'حدث خطأ أثناء إضافة المورد: ' . $e->getMessage();
        }
    }


    // public function calculateTotalPrice()
    // {
    //     $this->total_price = ($this->qyt && $this->purches_price) ? $this->qyt * $this->purches_price : 0;
    // }

    // public function calculateRemaining()
    // {
    //     $this->paid = (int) ($this->paid ?? 0); // التأكد من أن paid ليس null
    //     $this->remaining = ($this->paid > $this->total_price) ? 0 : $this->total_price - $this->paid;
    // }

    public function calculateTotalPrice()
    {
        // التأكد من أن qyt و purches_price أرقام
        $qyt = is_numeric($this->qyt) ? (float)$this->qyt : 0;
        $purches_price = is_numeric($this->purches_price) ? (float)$this->purches_price : 0;

        $this->total_price = ($qyt && $purches_price) ? $qyt * $purches_price : 0;
    }

    public function calculateRemaining()
    {
        // التأكد من أن paid ليس null وهو رقم
        $this->paid = is_numeric($this->paid) ? (float)$this->paid : 0;

        // التأكد من أن total_price رقم
        $total_price = is_numeric($this->total_price) ? (float)$this->total_price : 0;

        // حساب المتبقي
        $this->remaining = ($this->paid > $total_price) ? 0 : $total_price - $this->paid;
    }

    public function saveInvoices()
    {
        // Validate common data
        $this->validate([
            'type' => 'required|in:فاتورة مؤقتة,فاتورة رسمية',
            'supplier_id' => 'required',
            'category_id' => 'required',
        ], [
            'type.required' => 'نوع الفاتورة مطلوب',
            'type.in' => 'نوع الفاتورة غير صحيح',
            'supplier_id.required' => 'المورد مطلوب',
            'category_id.required' => 'التصنيف مطلوب',
        ]);

        // Validate each invoice
        foreach ($this->invoices as $index => $invoice) {
            $this->validate([
                "invoices.{$index}.bayan_txt" => 'required',
                "invoices.{$index}.referance_number" => 'required',
                "invoices.{$index}.qyt" => 'required|numeric|min:1',
                "invoices.{$index}.purches_price" => 'required|numeric|min:0',
            ], [
                "invoices.{$index}.bayan_txt.required" => "البيان مطلوب للفاتورة " . ($index + 1),
                "invoices.{$index}.referance_number.required" => "الرقم المرجعي مطلوب للفاتورة " . ($index + 1),
                "invoices.{$index}.qyt.required" => "الكمية مطلوبة للفاتورة " . ($index + 1),
                "invoices.{$index}.purches_price.required" => "سعر الشراء مطلوب للفاتورة " . ($index + 1),
            ]);
        }

        try {
            DB::beginTransaction();

            $createdInvoices = [];

            foreach ($this->invoices as $invoiceData) {
                // Create invoice directly
                $invoice = new PurcheInvoice;
                $invoice->type = $this->type; // استخدام النوع المحدد من المستخدم
                $invoice->bayan_txt = $invoiceData['bayan_txt'];
                $invoice->referance_number = $invoiceData['referance_number'];
                $invoice->supplier_id = $this->supplier_id;
                $invoice->qyt = $invoiceData['qyt'] ?? 0;
                $invoice->purches_price = $invoiceData['purches_price'] ?? 0;
                $invoice->total_price = $invoiceData['total_price'] ?? 0;
                $invoice->paid = $invoiceData['paid'] ?? 0;
                $invoice->remaining = $invoiceData['remaining'] ?? 0;
                $invoice->safe_id = $invoiceData['safe_id'] ?? null;
                $invoice->category_id = $this->category_id;
                $invoice->admin_id = Auth::user()->id;
                $invoice->save();

                $createdInvoices[] = $invoice;

                // Add Transaction In Supplier Account
                SupplierTransaction::create([
                    'supplier_id' => $this->supplier_id,
                    'purchase_invoice_id' => $invoice->id,
                    'amount' => $invoice->total_price,
                    'type' => 'credit', // المبلغ المستحق للمورد الدائن
                    'description' => 'مبلغ مستحق من فاتورة شراء #' . $invoice->id,
                ]);

                // إذا كان هناك مبلغ مدفوع، أضف معاملة دفع
                if ($invoice->paid > 0 && $invoice->safe_id) {
                    SupplierTransaction::create([
                        'supplier_id' => $this->supplier_id,
                        'purchase_invoice_id' => $invoice->id,
                        'amount' => $invoice->paid,
                        'type' => 'debit', // المبلغ المدفوع للمورد مدين
                        'safe_id' => $invoice->safe_id,
                        'description' => 'دفعة لفاتورة شراء #' . $invoice->id,
                    ]);

                    // Update safe balance
                    $safe = Safe::find($invoice->safe_id);
                    if ($safe) {
                        $safe->balance = $safe->balance - $invoice->paid;
                        $safe->save();
                    }
                }
            }

            DB::commit();

            // إعادة تعيين النموذج
            $this->initializeInvoices();
            $this->supplier_id = null;
            $this->category_id = null;
            $this->resetSupplierInfo();

            // رسالة نجاح
            if (count($createdInvoices) == 1) {
                session()->flash('message', '✅ تم حفظ الفاتورة بنجاح!');
            } else {
                session()->flash('message', '✅ تم حفظ ' . count($createdInvoices) . ' فواتير بنجاح!');
            }

            $this->dispatch('show-success-message');

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', '❌ حدث خطأ أثناء الحفظ: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.dashboard.purches-invoice-create');
    }
}
