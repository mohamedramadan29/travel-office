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
    <div class="mb-4 card" style="border: 2px solid #007BFF;">
        <div class="text-white card-header bg-primary">
            <h5 class="mb-0">
                <i class="la la-cogs"></i>
                بيانات مشتركة لجميع الفواتير
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- نوع الفاتورة -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="type">نوع الفاتورة</label>
                        <select wire:model.live="type" id="type" class="form-control">
                            <option value="فاتورة مؤقتة">فاتورة مؤقتة</option>
                            @can('official_purches_invoices')
                                <option value="فاتورة رسمية">فاتورة رسمية</option>
                            @endcan
                        </select>
                        @if($type == 'فاتورة مؤقتة')
                        <span id="temporaryInvoiceNote" style="color: #888; font-size: 0.9em;">
                            الفاتورة المؤقتة غير نهائية ولا تؤثر على المخزون
                        </span>
                        @endif
                        @error('type')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
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
                                <button type="button" class="btn btn-success" wire:click="openAddSupplierModal">
                                    <i class="la la-plus"></i>
                                </button>
                            </div>
                        </div>
                        @error('supplier_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="category_id">التصنيف</label>
                        <select wire:model.live="category_id" class="form-control">
                            <option value="">اختر التصنيف</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
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
                    فاتورة {{ $index + 1 }}
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
                        <label for="invoices_{{ $index }}_bayan_txt"> البيان/الوصف </label>
                        <input wire:model.live="invoices.{{ $index }}.bayan_txt" type="text"
                            id="invoices_{{ $index }}_bayan_txt" class="form-control"
                            name="invoices[{{ $index }}][bayan_txt]" placeholder="أدخل وصف الفاتورة">
                        @error('invoices.' . $index . '.bayan_txt')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="invoices_{{ $index }}_referance_number"> الرقم المرجعي </label>
                        <input wire:model.live="invoices.{{ $index }}.referance_number" type="text"
                            id="invoices_{{ $index }}_referance_number" class="form-control"
                            name="invoices[{{ $index }}][referance_number]" placeholder="أدخل الرقم المرجعي">
                        @error('invoices.' . $index . '.referance_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="invoices_{{ $index }}_qyt"> الكمية </label>
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
                        <label for="invoices_{{ $index }}_purches_price"> سعر الشراء </label>
                        <input wire:model.live="invoices.{{ $index }}.purches_price" type="number" step="0.00001"
                            min="0" id="invoices_{{ $index }}_purches_price" class="form-control"
                            name="invoices[{{ $index }}][purches_price]" value="{{ $invoice['purches_price'] }}">
                        @error('invoices.' . $index . '.purches_price')
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
                        <label for="invoices_{{ $index }}_total_price"> السعر الكلي (د.ل) </label>
                        <input wire:model.live.debounce.700ms="invoices.{{ $index }}.total_price" readonly type="number"
                            id="invoices_{{ $index }}_total_price" class="form-control"
                            name="invoices[{{ $index }}][total_price]" value="{{ $invoice['total_price'] }}">
                        @error('invoices.' . $index . '.total_price')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="invoices_{{ $index }}_paid"> المدفوع (د.ل) </label>
                        <input wire:model.live.debounce.700ms="invoices.{{ $index }}.paid" type="number" step="0.00001" min="0"
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
                        <label for="invoices_{{ $index }}_remaining"> الباقي (د.ل) </label>
                        <input wire:model.live.debounce.700ms="invoices.{{ $index }}.remaining" readonly type="number"
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
                        <label for="invoices_{{ $index }}_safe_id"> الخزينة </label>
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
            <i class="la la-check-square-o"></i> حفظ جميع الفواتير
        </button>
    </div>

    <!-- Livewire Modal for adding new supplier -->
    @if($showAddSupplierModal)
    <div class="modal fade show" id="addSupplierModal" tabindex="-1" role="dialog"
         style="display: block; background-color: rgba(0,0,0,0.5);" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="text-white modal-header bg-success">
                    <h5 class="modal-title"><i class="la la-plus"></i> إضافة مورد جديد</h5>
                    <button type="button" class="text-white close" wire:click="closeAddSupplierModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if($modalErrorMessage)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="la la-exclamation-circle"></i> {{ $modalErrorMessage }}
                        <button type="button" class="close" wire:click="$set('modalErrorMessage', '')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    <div class="form-group">
                        <label><strong>الاسم <span class="text-danger">*</span></strong></label>
                        <input type="text" wire:model="newSupplierName" class="form-control" placeholder="أدخل اسم المورد">
                        @error('newSupplierName') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label><strong>رقم الهاتف <span class="text-danger">*</span></strong></label>
                        <input type="text" wire:model="newSupplierMobile" class="form-control" placeholder="أدخل رقم الهاتف">
                        @error('newSupplierMobile') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>البريد الإلكتروني (اختياري)</label>
                        <input type="email" wire:model="newSupplierEmail" class="form-control" placeholder="example@example.com">
                        @error('newSupplierEmail') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>واتساب (اختياري)</label>
                        <input type="text" wire:model="newSupplierWhatsapp" class="form-control" placeholder="أدخل رقم الواتساب">
                        @error('newSupplierWhatsapp') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>العنوان (اختياري)</label>
                        <textarea wire:model="newSupplierAddress" class="form-control" rows="2" placeholder="أدخل عنوان المورد"></textarea>
                        @error('newSupplierAddress') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeAddSupplierModal">إلغاء</button>
                    <button type="button" class="btn btn-success" wire:click="saveSupplier">
                        <i class="la la-save"></i> حفظ المورد
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- JavaScript -->
    @section('js')

    <script>
        $(document).ready(function() {
            console.log('Document ready');
            initSelect2();
            // تحديث القيم بعد تحميل الصفحة
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

            // Listen for supplier data updates
            window.addEventListener('supplier-updated', function() {
                console.log('Supplier updated');
                setTimeout(updateSelect2Values, 100);
            });

            // Listen for supplier added event
            window.addEventListener('supplier-added', function(event) {
                console.log('Supplier added event received', event.detail);

                // Wait a bit for Livewire to finish processing
                setTimeout(function() {
                    // Get the supplier details from the event
                    const supplierId = event.detail[0].supplierId;
                    const supplierName = event.detail[0].supplierName;

                    console.log('Adding supplier to Select2:', supplierId, supplierName);

                    // Create a new option element
                    const newOption = new Option(supplierName, supplierId, true, true);

                    // Append it to the select
                    $('#supplier_id').append(newOption);

                    // Trigger change to notify Select2 and Livewire
                    $('#supplier_id').trigger('change');

                    // Also update Livewire's supplier_id
                    @this.set('supplier_id', supplierId);

                    console.log('Supplier added to Select2 and selected');
                }, 300);
            });
        });

        function initSelect2() {
            console.log('Init Select2');

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

            // Add change listeners for safe dropdowns
            $('[id^="invoices_"][id$="_safe_id"]').off('change.safe').on('change.safe', function(e) {
                let index = $(this).attr('id').match(/invoices_(\d+)_safe_id/)[1];
                let data = $(this).val();
                @this.set('invoices.' + index + '.safe_id', data);
            });
        }

        // Function to update select2 values after Livewire updates
        function updateSelect2Values() {
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

        // Handle invoice type change for note visibility
        function toggleInvoiceTypeNote() {
            const typeSelect = document.getElementById('type');
            const note = document.getElementById('temporaryInvoiceNote');

            if (typeSelect && note) {
                if (typeSelect.value === 'فاتورة رسمية') {
                    note.style.display = 'none';
                } else {
                    note.style.display = 'block';
                }
            }
        }

        // Listen for Livewire events to update the note
        window.addEventListener('livewire:update', function() {
            setTimeout(toggleInvoiceTypeNote, 100);
        });

        // Initial call
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(toggleInvoiceTypeNote, 500);
        });
    </script>
    @endsection
</div>
