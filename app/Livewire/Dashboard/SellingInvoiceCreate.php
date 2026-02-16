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
use App\Models\admin\SafeTransaction;

class SellingInvoiceCreate extends Component
{
    public $suppliers;
    public $safes;
    public $categories;
    public $clients;
    public $purchesInvoices;
    public $client_id;
    public $client_mobile;
    public $client_whatsapp;
    public $client_email;
    public $client_address;
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

    // Keep original properties for backward compatibility
    public $qyt = 1;
    public $selling_price = 0;
    public $total_price = 0;
    public $paid = 0;
    public $remaining = 0;
    public $payment_method;
    public $safe_id;

    // Client Modal Properties
    public $showAddClientModal = false;
    public $newClientName = '';
    public $newClientMobile = '';
    public $newClientEmail = '';
    public $newClientWhatsapp = '';
    public $newClientAddress = '';
    public $modalClientErrorMessage = '';

    // Supplier Modal Properties
    public $showAddSupplierModal = false;
    public $newSupplierName = '';
    public $newSupplierMobile = '';
    public $newSupplierEmail = '';
    public $newSupplierWhatsapp = '';
    public $newSupplierAddress = '';
    public $modalSupplierErrorMessage = '';


    public function mount()
    {
        // جلب بيانات العملاء والموردين والخزائن والتصنيفات
        $this->clients = Client::active()->get();
        $this->suppliers = Supplier::active()->get();
        $this->safes = Safe::active()->get();
        $this->categories = Category::active()->get();
        $this->purchesInvoices = PurcheInvoice::where('status', 'available')->where('return_status', 'not_returned')->get();

        // Initialize first invoice
        $this->initializeInvoices();

        $this->qyt = old('qyt', 1);
        $this->selling_price = old('selling_price', 0);
        $this->supplier_id = old('supplier_id');
        $this->client_id = old('client_id');
        $this->paid = old('paid', 0);
        $this->total_price = old('total_price', 0);
        $this->remaining = old('remaining', 0);
        $this->payment_method = old('payment_method');
        $this->safe_id = old('safe_id');

        // تحميل بيانات العميل والمورد إذا كانوا محددين
        if ($this->client_id) {
            $this->getClientInfo();
        }
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
                'category_id' => null,
                'qyt' => 1,
                'selling_price' => 0,
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
            'category_id' => null,
            'qyt' => 1,
            'selling_price' => 0,
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
            $qyt = is_numeric($this->invoices[$index]['qyt']) ? (float) $this->invoices[$index]['qyt'] : 0;
            $selling_price = is_numeric($this->invoices[$index]['selling_price']) ? (float) $this->invoices[$index]['selling_price'] : 0;

            $this->invoices[$index]['total_price'] = $qyt * $selling_price;
            $this->calculateInvoiceRemaining($index);
        }
    }

    public function calculateInvoiceRemaining($index)
    {
        if (isset($this->invoices[$index])) {
            $paid = is_numeric($this->invoices[$index]['paid']) ? (float) $this->invoices[$index]['paid'] : 0;
            $total_price = is_numeric($this->invoices[$index]['total_price']) ? (float) $this->invoices[$index]['total_price'] : 0;

            $this->invoices[$index]['remaining'] = ($paid > $total_price) ? 0 : $total_price - $paid;
        }
    }

    public function updatedInvoices($value, $key)
    {
        // Parse the key to get index and field
        $keyParts = explode('.', $key);
        $index = (int) $keyParts[0];
        $field = $keyParts[1] ?? null;

        if ($field === 'qyt' || $field === 'selling_price') {
            $this->calculateInvoiceTotal($index);
        } elseif ($field === 'paid') {
            $this->calculateInvoiceRemaining($index);
        }
    }






    ##########3 Start Check Referance Number

    public function updatedReferanceNumber()
    {
        //  dd('test');
        $this->checkReferanceNumber($this->referance_number);
    }

    public function checkReferanceNumber($referance_number)
    {
        // dd($referance_number);
        $invoice = PurcheInvoice::where('referance_number', $referance_number)->first();

        if (!$invoice) {
            $this->referance_error = 'رقم الفاتورة غير موجود';
        } elseif ($invoice->client_id) {
            $this->referance_error = 'رقم الفاتورة مرتبط بالعميل';
        }
        // elseif($invoice->type == 'فاتورة مؤقتة'){
        //  //   $this->referance_error = ' الفاتورة مؤقتة وغير رسمية الي الان  ';
        // }
        else {
            $this->referance_error = '';
            $this->invoice = $invoice;
            $this->bayan_txt = $invoice->bayan_txt;
            $this->category_id = $invoice->category_id;
            $this->supplier_id = $invoice->supplier_id;
            $this->supplier_mobile = $invoice->supplier->mobile;
            $this->supplier_whatsapp = $invoice->supplier->whatsapp;
            $this->supplier_email = $invoice->supplier->email;
            $this->supplier_address = $invoice->supplier->address;
            $this->qyt = $invoice->qyt;
        }
    }


    public function updated($propertyName)
    {
        // تحديث السعر الكلي والباقي عند تغيير qyt أو purches_price
        if (in_array($propertyName, ['qyt', 'selling_price'])) {
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

    // public function calculateTotalPrice()
    // {
    //     $this->total_price = ($this->qyt && $this->selling_price) ? $this->qyt * $this->selling_price : 0;
    // }

    // public function calculateRemaining()
    // {
    //     $this->paid = (int) ($this->paid ?? 0); // التأكد من أن paid ليس null
    //     $this->remaining = ($this->paid > $this->total_price) ? 0 : $this->total_price - $this->paid;
    // }

    public function calculateTotalPrice()
    {
        // التأكد من أن qyt و selling_price أرقام
        $qyt = is_numeric($this->qyt) ? (float) $this->qyt : 0;
        $selling_price = is_numeric($this->selling_price) ? (float) $this->selling_price : 0;

        $this->total_price = ($qyt && $selling_price) ? $qyt * $selling_price : 0;
    }

    public function calculateRemaining()
    {
        // التأكد من أن paid ليس null وهو رقم
        $this->paid = is_numeric($this->paid) ? (float) $this->paid : 0;

        // التأكد من أن total_price رقم
        $total_price = is_numeric($this->total_price) ? (float) $this->total_price : 0;

        // حساب المتبقي
        $this->remaining = ($this->paid > $total_price) ? 0 : $total_price - $this->paid;
    }

    public function saveInvoices()
    {
        // التحقق من البيانات الأساسية
        $this->validate([
            'client_id' => 'required',
            'supplier_id' => 'required',
        ], [
            'client_id.required' => 'العميل مطلوب',
            'supplier_id.required' => 'المورد مطلوب',
        ]);

        // التحقق من بيانات كل فاتورة
        foreach ($this->invoices as $index => $invoice) {
            $this->validate([
                "invoices.{$index}.bayan_txt" => 'required',
                "invoices.{$index}.referance_number" => 'required|unique:sale_invoices,referance_number',
                "invoices.{$index}.category_id" => 'required',
                "invoices.{$index}.qyt" => 'required|numeric|min:1',
                "invoices.{$index}.selling_price" => 'required|numeric|min:0',
            ], [
                "invoices.{$index}.bayan_txt.required" => "البيان مطلوب للفاتورة " . ($index + 1),
                "invoices.{$index}.referance_number.required" => "الرقم المرجعي مطلوب للفاتورة " . ($index + 1),
                "invoices.{$index}.referance_number.unique" => "الرقم المرجعي موجود مسبقاً للفاتورة " . ($index + 1),
                "invoices.{$index}.category_id.required" => "التصنيف مطلوب للفاتورة " . ($index + 1),
                "invoices.{$index}.qyt.required" => "الكمية مطلوبة للفاتورة " . ($index + 1),
                "invoices.{$index}.selling_price.required" => "سعر البيع مطلوب للفاتورة " . ($index + 1),
            ]);
        }

        try {
            DB::beginTransaction();

            $savedInvoices = [];

            foreach ($this->invoices as $invoiceData) {
                // إنشاء الفاتورة
                $sellingInvoice = SaleInvoice::create([
                    'bayan_txt' => $invoiceData['bayan_txt'],
                    'referance_number' => $invoiceData['referance_number'],
                    'client_id' => $this->client_id,
                    'supplier_id' => $this->supplier_id,
                    'qyt' => $invoiceData['qyt'] ?? 0,
                    'selling_price' => $invoiceData['selling_price'] ?? 0,
                    'total_price' => $invoiceData['total_price'] ?? 0,
                    'paid' => $invoiceData['paid'] ?? 0,
                    'remaining' => $invoiceData['remaining'] ?? 0,
                    'safe_id' => $invoiceData['safe_id'] ?? null,
                    'category_id' => $invoiceData['category_id'],
                    'admin_id' => Auth::id(),
                ]);

                // إدارة المعاملات المالية

                // 1. إضافة المديونية على العميل (المبلغ الكامل)
                ClientTransaction::create([
                    'client_id' => $this->client_id,
                    'sale_invoice_id' => $sellingInvoice->id,
                    'amount' => $sellingInvoice->total_price,
                    'type' => 'debit', // المبلغ المستحق من العميل مدين
                    'description' => 'مبلغ مستحق من فاتورة بيع #' . $sellingInvoice->id,
                ]);

                // 2. إذا كان هناك مبلغ مدفوع
                if ($sellingInvoice->paid > 0 && $sellingInvoice->safe_id) {
                    // أ. إضافة معاملة العميل (دائن - سداد)
                    ClientTransaction::create([
                        'client_id' => $this->client_id,
                        'sale_invoice_id' => $sellingInvoice->id,
                        'safe_id' => $sellingInvoice->safe_id,
                        'amount' => $sellingInvoice->paid,
                        'type' => 'credit', // المبلغ المدفوع من العميل دائن
                        'description' => 'دفعة لفاتورة بيع #' . $sellingInvoice->id,
                    ]);

                    // ب. إضافة معاملة الخزنة
                    $clientName = Client::find($this->client_id)->name ?? '';

                    $safeTransaction = new SafeTransaction();
                    $safeTransaction->safe_id = $sellingInvoice->safe_id;
                    $safeTransaction->sale_invoice_id = $sellingInvoice->id;
                    $safeTransaction->amount = $sellingInvoice->paid;
                    $safeTransaction->type = 'deposit';
                    $safeTransaction->description = ' اضافة دفعة من العميل [ ' . $clientName . ' ]' . ' من فاتورة بيع الرقم المرجعي :  ' . $sellingInvoice->referance_number;
                    $safeTransaction->save();

                    // ج. تحديث رصيد الخزينة
                    $safe = Safe::find($sellingInvoice->safe_id);
                    if ($safe) {
                        $safe->increment('balance', $sellingInvoice->paid);
                    }
                }

                $savedInvoices[] = $sellingInvoice;
            }

            DB::commit();

            $count = count($savedInvoices);
            session()->flash('message', "تم حفظ {$count} فاتورة بيع بنجاح!");

            // return redirect()->route('dashboard.sale_invoices.index');
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'حدث خطأ أثناء الحفظ: ' . $e->getMessage());
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

    // Client Modal Methods
    public function openAddClientModal()
    {
        $this->showAddClientModal = true;
        $this->resetClientFormFields();
    }

    public function closeAddClientModal()
    {
        $this->showAddClientModal = false;
        $this->resetClientFormFields();
    }

    public function resetClientFormFields()
    {
        $this->newClientName = '';
        $this->newClientMobile = '';
        $this->newClientEmail = '';
        $this->newClientWhatsapp = '';
        $this->newClientAddress = '';
        $this->modalClientErrorMessage = '';
        $this->resetValidation([
            'newClientName',
            'newClientMobile',
            'newClientEmail',
            'newClientWhatsapp',
            'newClientAddress'
        ]);
    }

    public function saveClient()
    {
        // Clear previous error message
        $this->modalClientErrorMessage = '';

        // Validate client data
        $this->validate([
            'newClientName' => 'required|string|max:255',
            'newClientMobile' => 'required|string|max:20',
            'newClientEmail' => 'nullable|email|max:255',
            'newClientWhatsapp' => 'nullable|string|max:20',
            'newClientAddress' => 'nullable|string|max:500',
        ], [
            'newClientName.required' => 'اسم العميل مطلوب',
            'newClientName.max' => 'اسم العميل يجب ألا يتجاوز 255 حرف',
            'newClientMobile.required' => 'رقم الهاتف مطلوب',
            'newClientMobile.max' => 'رقم الهاتف يجب ألا يتجاوز 20 رقم',
            'newClientEmail.email' => 'البريد الإلكتروني غير صحيح',
            'newClientEmail.max' => 'البريد الإلكتروني يجب ألا يتجاوز 255 حرف',
            'newClientWhatsapp.max' => 'رقم الواتساب يجب ألا يتجاوز 20 رقم',
            'newClientAddress.max' => 'العنوان يجب ألا يتجاوز 500 حرف',
        ]);

        try {
            // Create new client
            $client = Client::create([
                'name' => $this->newClientName,
                'mobile' => $this->newClientMobile,
                'email' => $this->newClientEmail ?: null,
                'whatsapp' => $this->newClientWhatsapp ?: null,
                'address' => $this->newClientAddress ?: null,
                'status' => 1,
            ]);

            // Refresh clients list
            $this->refreshClients();

            // Set the newly created client as selected
            $this->client_id = $client->id;
            $this->getClientInfo();

            // Close modal and reset form
            $this->closeAddClientModal();

            // Show success message
            session()->flash('message', '✅ تم إضافة العميل بنجاح!');

            // Dispatch event to update Select2 with client details
            $this->dispatch('client-added', [
                'clientId' => $client->id,
                'clientName' => $client->name
            ]);

        } catch (\Exception $e) {
            // Display error in modal instead of session flash
            $this->modalClientErrorMessage = 'حدث خطأ أثناء إضافة العميل: ' . $e->getMessage();
        }
    }

    // Supplier Modal Methods
    public function openAddSupplierModal()
    {
        $this->showAddSupplierModal = true;
        $this->resetSupplierFormFields();
    }

    public function closeAddSupplierModal()
    {
        $this->showAddSupplierModal = false;
        $this->resetSupplierFormFields();
    }

    public function resetSupplierFormFields()
    {
        $this->newSupplierName = '';
        $this->newSupplierMobile = '';
        $this->newSupplierEmail = '';
        $this->newSupplierWhatsapp = '';
        $this->newSupplierAddress = '';
        $this->modalSupplierErrorMessage = '';
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
        $this->modalSupplierErrorMessage = '';

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
            $this->modalSupplierErrorMessage = 'حدث خطأ أثناء إضافة المورد: ' . $e->getMessage();
        }
    }


    public function refreshClients()
    {
        $this->clients = Client::active()->get();
    }

    public function refreshSuppliers()
    {
        $this->suppliers = Supplier::active()->get();
    }

    public function render()
    {
        $this->clients = Client::active()->get();
        $this->categories = Category::active()->get();
        $this->purchesInvoices = PurcheInvoice::all();
        return view('livewire.dashboard.selling-invoice-create');
    }
}
