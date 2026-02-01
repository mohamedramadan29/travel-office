<div>
    @if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="la la-check-circle"></i> {{ session('message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="la la-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Common Invoice Data Section -->
    <div class="mb-4 card" style="border: 2px solid #28a745;">
        <div class="text-white card-header bg-success">
            <h5 class="mb-0">
                <i class="la la-cogs"></i>
                بيانات مشتركة لجميع فواتير البيع
            </h5>
        </div>
        <div class="card-body">
            <!-- Client Info -->
            <h6><strong>بيانات العميل</strong></h6>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="client_id">العميل</label>
                        <div class="input-group">
                            <div style="flex-grow: 1;" wire:ignore>
                                <select name="client_id" id="client_id" class="form-control select2">
                                    <option value="">اختر العميل</option>
                                    @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" @if($client_id == $client->id) selected @endif>{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#addClientModal">
                                    <i class="la la-plus"></i>
                                </button>
                            </div>
                        </div>
                        @error('client_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            @if ($client_id)
            <div class="client-details"
                style="background: #e8f5e8; border-radius: 10px; padding: 15px; margin-bottom: 10px; border: 1px solid rgba(40, 167, 69, 0.2);">
                <div class="client-info" style="display: flex; justify-content: space-between;">
                    <div class="client-info-item"
                        style="font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-phone"></i>
                        <div><strong>رقم الهاتف:</strong> <span>{{ $client_mobile }}</span></div>
                    </div>
                    <div class="client-info-item"
                        style="font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                        <i class="fab fa-whatsapp"></i>
                        <div><strong>رقم الواتساب:</strong> <span>{{ $client_whatsapp }}</span></div>
                    </div>
                    <div class="client-info-item"
                        style="font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-envelope"></i>
                        <div><strong>البريد الإلكتروني:</strong> <span>{{ $client_email }}</span></div>
                    </div>
                    <div class="client-info-item"
                        style="font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-map-marker-alt"></i>
                        <div><strong>العنوان:</strong> <span>{{ $client_address }}</span></div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Supplier Info -->
            <h6><strong>بيانات المورد</strong></h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="supplier_id">المورد</label>
                        <div class="input-group">
                            <div style="flex-grow: 1;" wire:ignore>
                                <select name="supplier_id" id="supplier_id" class="form-control select2">
                                    <option value="">اختر المورد</option>
                                    @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @if($supplier_id == $supplier->id) selected @endif>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#addSupplierModal">
                                    <i class="la la-plus"></i>
                                </button>
                            </div>
                        </div>
                        @error('supplier_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            @if ($supplier_id)
            <div class="supplier-details"
                style="background: #f8f9fa; border-radius: 10px; padding: 15px; margin-top: 10px; border: 1px solid rgba(44, 62, 80, 0.2);">
                <div class="supplier-info" style="display: flex; justify-content: space-between;">
                    <div class="supplier-info-item"
                        style="font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-phone"></i>
                        <div><strong>رقم الهاتف:</strong> <span>{{ $supplier_mobile }}</span></div>
                    </div>
                    <div class="supplier-info-item"
                        style="font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                        <i class="fab fa-whatsapp"></i>
                        <div><strong>رقم الواتساب:</strong> <span>{{ $supplier_whatsapp }}</span></div>
                    </div>
                    <div class="supplier-info-item"
                        style="font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-envelope"></i>
                        <div><strong>البريد الإلكتروني:</strong> <span>{{ $supplier_email }}</span></div>
                    </div>
                    <div class="supplier-info-item"
                        style="font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-map-marker-alt"></i>
                        <div><strong>العنوان:</strong> <span>{{ $supplier_address }}</span></div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Multi-Invoice Section -->
    <div id="invoicesContainer">
        @foreach($invoices as $index => $invoice)
        <div class="invoice-section"
            style="border: 2px solid #e3e6f0; border-radius: 10px; padding: 20px; margin-bottom: 20px; background: #f8f9fa;">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title" style="margin-bottom: 0;">
                    <i class="la la-file-invoice"></i>
                    فاتورة بيع {{ $index + 1 }}
                </h5>
                <div>
                    @if($index == 0)
                    <button type="button" wire:click="addNewInvoice" class="btn btn-success btn-sm">
                        <i class="la la-plus"></i> إضافة فاتورة جديدة
                    </button>
                    @else
                    <button type="button" wire:click="removeInvoice({{ $index }})" class="btn btn-danger btn-sm">
                        <i class="la la-trash"></i> حذف
                    </button>
                    @endif
                </div>
            </div>

            <!-- Invoice Basic Info -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="invoices_{{ $index }}_referance_number">الرقم المرجعي</label>
                        <div wire:ignore>
                            <select wire:model.live="invoices.{{ $index }}.referance_number"
                                id="invoices_{{ $index }}_referance_number"
                                class="form-control select2-reference"
                                name="invoices[{{ $index }}][referance_number]" data-index="{{ $index }}">
                                <option value="">اختر الرقم المرجعي</option>
                                @foreach ($purchesInvoices as $purchesInvoice)
                                <option value="{{ $purchesInvoice->referance_number }}"
                                    data-category="{{ $purchesInvoice->category_id }}"
                                    data-bayan="{{ $purchesInvoice->bayan_txt }}"
                                    data-price="{{ $purchesInvoice->purches_price }}"
                                    @if($invoice['referance_number'] == $purchesInvoice->referance_number) selected @endif>
                                    {{ $purchesInvoice->referance_number }} - {{ $purchesInvoice->bayan_txt }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @error('invoices.' . $index . '.referance_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="invoices_{{ $index }}_bayan_txt">البيان/الوصف</label>
                        <input wire:model.live="invoices.{{ $index }}.bayan_txt" type="text"
                            id="invoices_{{ $index }}_bayan_txt" class="form-control"
                            name="invoices[{{ $index }}][bayan_txt]" placeholder="أدخل وصف الفاتورة">
                        @error('invoices.' . $index . '.bayan_txt')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Category Selection for this Invoice -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="invoices_{{ $index }}_category_id">التصنيف</label>
                        <select wire:model.live="invoices.{{ $index }}.category_id"
                            id="invoices_{{ $index }}_category_id" class="form-control"
                            name="invoices[{{ $index }}][category_id]">
                            <option value="">اختر التصنيف</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @if($invoice['category_id'] == $category->id) selected @endif>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('invoices.' . $index . '.category_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="invoices_{{ $index }}_qyt">الكمية</label>
                        <input wire:model.live="invoices.{{ $index }}.qyt" type="number" id="invoices_{{ $index }}_qyt"
                            class="form-control" name="invoices[{{ $index }}][qyt]" min="1"
                            value="{{ $invoice['qyt'] }}">
                        @error('invoices.' . $index . '.qyt')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="invoices_{{ $index }}_selling_price">سعر البيع</label>
                        <input wire:model.live="invoices.{{ $index }}.selling_price" type="number" step="0.00001"
                            min="0" id="invoices_{{ $index }}_selling_price" class="form-control"
                            name="invoices[{{ $index }}][selling_price]" value="{{ $invoice['selling_price'] }}">
                        @error('invoices.' . $index . '.selling_price')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <h6><strong>بيانات الدفع</strong></h6>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="invoices_{{ $index }}_total_price">السعر الكلي (د.ل)</label>
                        <input wire:model.live="invoices.{{ $index }}.total_price" readonly type="number"
                            id="invoices_{{ $index }}_total_price" class="form-control"
                            name="invoices[{{ $index }}][total_price]" value="{{ $invoice['total_price'] }}">
                        @error('invoices.' . $index . '.total_price')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="invoices_{{ $index }}_paid">المدفوع (د.ل)</label>
                        <input wire:model.live="invoices.{{ $index }}.paid" type="number" step="0.00001" min="0"
                            max="{{ $invoice['total_price'] }}" id="invoices_{{ $index }}_paid" class="form-control"
                            name="invoices[{{ $index }}][paid]" value="{{ $invoice['paid'] }}">
                        <span>اتركه صفرًا للدفع لاحقًا</span>
                        @error('invoices.' . $index . '.paid')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="invoices_{{ $index }}_remaining">الباقي (د.ل)</label>
                        <input wire:model.live="invoices.{{ $index }}.remaining" readonly type="number"
                            id="invoices_{{ $index }}_remaining" class="form-control"
                            name="invoices[{{ $index }}][remaining]" value="{{ $invoice['remaining'] }}">
                        @error('invoices.' . $index . '.remaining')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Safe Selection (only if payment > 0) -->
            @if($invoice['paid'] > 0)
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="invoices_{{ $index }}_safe_id">الخزينة</label>
                        <select wire:model.live="invoices.{{ $index }}.safe_id" id="invoices_{{ $index }}_safe_id"
                            class="form-control" name="invoices[{{ $index }}][safe_id]">
                            <option value="">اختر الخزينة</option>
                            @foreach ($safes as $safe)
                            <option value="{{ $safe->id }}">{{ $safe->name }}</option>
                            @endforeach
                        </select>
                        @error('invoices.' . $index . '.safe_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Save Button Section -->
    <div class="mt-4 text-center">
        <button type="button" wire:click="saveInvoices" class="btn btn-primary btn-lg">
            <i class="la la-check-square-o"></i> حفظ جميع فواتير البيع
        </button>
    </div>
    <!-- Modal for adding new client -->
    <div class="modal fade" id="addClientModal" tabindex="-1" role="dialog" aria-labelledby="addClientLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="text-white modal-header bg-success">
                    <h5 class="modal-title" id="addClientLabel"><i class="la la-plus"></i> إضافة عميل جديد</h5>
                    <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="clientFormErrors"></div>
                    <form id="addClientForm">
                        @csrf
                        <div class="form-group">
                            <label><strong>الاسم <span class="text-danger">*</span></strong></label>
                            <input type="text" name="name" class="form-control" placeholder="أدخل اسم العميل" required>
                        </div>
                        <div class="form-group">
                            <label><strong>رقم الهاتف <span class="text-danger">*</span></strong></label>
                            <input type="text" name="mobile" class="form-control" placeholder="أدخل رقم الهاتف" required>
                        </div>
                        <div class="form-group">
                            <label>البريد الإلكتروني (اختياري)</label>
                            <input type="email" name="email" class="form-control" placeholder="example@example.com">
                        </div>
                        <div class="form-group">
                            <label>واتساب (اختياري)</label>
                            <input type="text" name="whatsapp" class="form-control" placeholder="أدخل رقم الواتساب">
                        </div>
                        <div class="form-group">
                            <label>العنوان (اختياري)</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="أدخل عنوان العميل"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="button" id="submitClientForm" class="btn btn-success">
                        <i class="la la-save"></i> حفظ العميل
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for adding new supplier -->
    <div class="modal fade" id="addSupplierModal" tabindex="-1" role="dialog" aria-labelledby="addSupplierLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="text-white modal-header bg-success">
                    <h5 class="modal-title" id="addSupplierLabel"><i class="la la-plus"></i> إضافة مورد جديد</h5>
                    <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="supplierFormErrors"></div>
                    <form id="addSupplierForm">
                        @csrf
                        <div class="form-group">
                            <label><strong>الاسم <span class="text-danger">*</span></strong></label>
                            <input type="text" name="name" class="form-control" placeholder="أدخل اسم المورد" required>
                        </div>
                        <div class="form-group">
                            <label><strong>رقم الهاتف <span class="text-danger">*</span></strong></label>
                            <input type="text" name="mobile" class="form-control" placeholder="أدخل رقم الهاتف" required>
                        </div>
                        <div class="form-group">
                            <label>البريد الإلكتروني (اختياري)</label>
                            <input type="email" name="email" class="form-control" placeholder="example@example.com">
                        </div>
                        <div class="form-group">
                            <label>واتساب (اختياري)</label>
                            <input type="text" name="whatsapp" class="form-control" placeholder="أدخل رقم الواتساب">
                        </div>
                        <div class="form-group">
                            <label>العنوان (اختياري)</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="أدخل عنوان المورد"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="button" id="submitSupplierForm" class="btn btn-success">
                        <i class="la la-save"></i> حفظ المورد
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    @section('js')
    <script>
        $(document).ready(function() {
            console.log('Document ready - Selling Create');
            initSelect2();
            // Update values after page load
            setTimeout(updateSelect2Values, 500);

            // Listen for Livewire updates
            window.addEventListener('invoice-added', function() {
                console.log('New invoice added');
                setTimeout(function() {
                    initSelect2();
                }, 100);
            });

            window.addEventListener('invoice-removed', function() {
                console.log('Invoice removed');
                setTimeout(function() {
                    initSelect2();
                    updateSelect2Values();
                }, 100);
            });

            // Listen for client/supplier data updates
            window.addEventListener('client-updated', function() {
                console.log('Client updated');
                setTimeout(updateSelect2Values, 100);
            });

            window.addEventListener('supplier-updated', function() {
                console.log('Supplier updated');
                setTimeout(updateSelect2Values, 100);
            });
        });

        function initSelect2() {
            console.log('Init Select2 - Selling Create');

            // Initialize Select2 for main client dropdown
            $('#client_id').select2({
                placeholder: "اختر العميل",
                allowClear: true,
                width: '100%'
            });

            // Set the current client value if exists
            @if($client_id)
                $('#client_id').val(@json($client_id)).trigger('change.select2');
            @endif

            $('#client_id').off('change.client').on('change.client', function(e) {
                var data = $(this).val();
                @this.set('client_id', data);
            });

            // Initialize Select2 for main supplier dropdown
            $('#supplier_id').select2({
                placeholder: "اختر المورد",
                allowClear: true,
                width: '100%'
            });

            // Set the current supplier value if exists
            @if($supplier_id)
                $('#supplier_id').val(@json($supplier_id)).trigger('change.select2');
            @endif

            $('#supplier_id').off('change.supplier').on('change.supplier', function(e) {
                var data = $(this).val();
                @this.set('supplier_id', data);
            });

            // Initialize Select2 for all safe dropdowns in invoices
            $('[id^="invoices_"][id$="_safe_id"]').each(function() {
                if (!$(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2({
                        placeholder: "اختر الخزينة",
                        allowClear: true,
                        width: '100%'
                    });

                    // Set the current safe value if exists
                    let currentValue = $(this).val();
                    if (currentValue) {
                        $(this).trigger('change.select2');
                    }
                }
            });

            // Initialize Select2 for reference number dropdowns
            $('.select2-reference').each(function() {
                if (!$(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2({
                        placeholder: "اختر الرقم المرجعي",
                        allowClear: true,
                        width: '100%'
                    });

                    // Set the current value if exists
                    let currentValue = $(this).val();
                    if (currentValue) {
                        $(this).trigger('change.select2');
                    }
                }
            });

            // Add change listeners for safe dropdowns
            $('[id^="invoices_"][id$="_safe_id"]').off('change.safe').on('change.safe', function(e) {
                let index = $(this).attr('id').match(/invoices_(\d+)_safe_id/)[1];
                let data = $(this).val();
                @this.set('invoices.' + index + '.safe_id', data);
            });

            // Add change listeners for reference number dropdowns
            $('.select2-reference').off('change.reference').on('change.reference', function(e) {
                let index = $(this).data('index');
                let selectedOption = $(this).find(':selected');
                let referenceNumber = $(this).val();

                if (referenceNumber && selectedOption.length) {
                    let categoryId = selectedOption.data('category');
                    let bayanTxt = selectedOption.data('bayan');
                    let purchesPrice = selectedOption.data('price');

                    // Update Livewire component with selected data
                    @this.set('invoices.' + index + '.referance_number', referenceNumber);
                    @this.set('invoices.' + index + '.bayan_txt', bayanTxt);
                    @this.set('invoices.' + index + '.selling_price', purchesPrice);
                    @this.set('invoices.' + index + '.category_id', categoryId);

                    // Update the UI fields
                    $('#invoices_' + index + '_bayan_txt').val(bayanTxt);
                    $('#invoices_' + index + '_selling_price').val(purchesPrice);
                    $('#invoices_' + index + '_category_id').val(categoryId);

                    console.log('Reference selected:', {
                        index: index,
                        referenceNumber: referenceNumber,
                        categoryId: categoryId,
                        bayanTxt: bayanTxt,
                        purchesPrice: purchesPrice
                    });
                } else {
                    @this.set('invoices.' + index + '.referance_number', '');
                }
            });
        }

        // Function to update select2 values after Livewire updates
        function updateSelect2Values() {
            // Update client select
            @if($client_id)
                if ($('#client_id').length && $('#client_id').val() != @json($client_id)) {
                    $('#client_id').val(@json($client_id)).trigger('change.select2');
                }
            @endif

            // Update supplier select
            @if($supplier_id)
                if ($('#supplier_id').length && $('#supplier_id').val() != @json($supplier_id)) {
                    $('#supplier_id').val(@json($supplier_id)).trigger('change.select2');
                }
            @endif

            // Update safe selects
            $('[id^="invoices_"][id$="_safe_id"]').each(function() {
                let currentVal = $(this).val();
                let expectedVal = $(this).find('option:selected').val();
                if (currentVal != expectedVal && expectedVal) {
                    $(this).val(expectedVal).trigger('change.select2');
                }
            });

            // Update reference number selects
            $('.select2-reference').each(function() {
                let currentVal = $(this).val();
                let expectedVal = $(this).find('option:selected').val();
                if (currentVal != expectedVal && expectedVal) {
                    $(this).val(expectedVal).trigger('change.select2');
                }
            });
        }

        // Handle submit button click for client
        $(document).on('click', '#submitClientForm', function(e) {
            console.log('Submit client button clicked');
            e.preventDefault();
            submitClientForm();
        });

        // Handle submit button click for supplier
        $(document).on('click', '#submitSupplierForm', function(e) {
            console.log('Submit supplier button clicked');
            e.preventDefault();
            submitSupplierForm();
        });

        function submitClientForm() {
            console.log('submitClientForm called');
            let formData = new FormData(document.getElementById('addClientForm'));

            $.ajax({
                url: "{{ route('dashboard.clients.storeQuick') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    console.log('Sending client request...');
                    $('#submitClientForm').prop('disabled', true).html('<i class="la la-spinner fa-spin"></i> جاري الحفظ...');
                },
                success: function(response) {
                    console.log('Client Success:', response);
                    if (response.success) {
                        // Close modal
                        $('#addClientModal').modal('hide');
                        $('#addClientForm')[0].reset();
                        $('#clientFormErrors').html('');

                        // Add new option to Select2
                        var newOption = new Option(response.client.name, response.client.id, true, true);
                        $('#client_id').append(newOption).trigger('change');

                        // Show success message
                        alert(response.message);

                        // Update Livewire component
                        @this.refreshClients();
                        @this.set('client_id', response.client.id);
                    }
                },
                error: function(xhr) {
                    console.log('Client Error:', xhr);
                    $('#submitClientForm').prop('disabled', false).html('<i class="la la-save"></i> حفظ العميل');

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        let errorHtml = '<div class="alert alert-danger">';
                        errorHtml += '<strong>حدث خطأ:</strong><ul>';
                        for (let field in errors) {
                            errorHtml += '<li>' + errors[field][0] + '</li>';
                        }
                        errorHtml += '</ul></div>';
                        $('#clientFormErrors').html(errorHtml);
                    } else {
                        $('#clientFormErrors').html('<div class="alert alert-danger">حدث خطأ، يرجى المحاولة لاحقاً</div>');
                    }
                },
                complete: function() {
                    console.log('Client request complete');
                    $('#submitClientForm').prop('disabled', false).html('<i class="la la-save"></i> حفظ العميل');
                }
            });
        }

        function submitSupplierForm() {
            console.log('submitSupplierForm called');
            let formData = new FormData(document.getElementById('addSupplierForm'));

            $.ajax({
                url: "{{ route('dashboard.suppliers.storeQuick') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    console.log('Sending request...');
                    $('#submitSupplierForm').prop('disabled', true).html('<i class="la la-spinner fa-spin"></i> جاري الحفظ...');
                },
                success: function(response) {
                    console.log('Success:', response);
                    if (response.success) {
                        // Close modal
                        $('#addSupplierModal').modal('hide');
                        $('#addSupplierForm')[0].reset();
                        $('#supplierFormErrors').html('');

                        // Add new option to Select2
                        var newOption = new Option(response.supplier.name, response.supplier.id, true, true);
                        $('#supplier_id').append(newOption).trigger('change');

                        // Show success message
                        alert(response.message);

                        // Update Livewire component
                        @this.refreshSuppliers();
                        @this.set('supplier_id', response.supplier.id);
                    }
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                    $('#submitSupplierForm').prop('disabled', false).html('<i class="la la-save"></i> حفظ المورد');

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        let errorHtml = '<div class="alert alert-danger">';
                        errorHtml += '<strong>حدث خطأ:</strong><ul>';
                        for (let field in errors) {
                            errorHtml += '<li>' + errors[field][0] + '</li>';
                        }
                        errorHtml += '</ul></div>';
                        $('#supplierFormErrors').html(errorHtml);
                    } else {
                        $('#supplierFormErrors').html('<div class="alert alert-danger">حدث خطأ، يرجى المحاولة لاحقاً</div>');
                    }
                },
                complete: function() {
                    console.log('Request complete');
                    $('#submitSupplierForm').prop('disabled', false).html('<i class="la la-save"></i> حفظ المورد');
                }
            });
        }

        // Re-initialize Select2 after Livewire updates
        document.addEventListener('livewire:navigated', function() {
            initSelect2();
        });

        // Handle Livewire load/update events
        document.addEventListener('livewire:load', function() {
            initSelect2();
            setTimeout(updateSelect2Values, 200);
        });

        document.addEventListener('livewire:update', function() {
            initSelect2();
            setTimeout(updateSelect2Values, 200);
        });
    </script>
    @endsection
</div>
