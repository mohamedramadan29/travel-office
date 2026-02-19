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

    <!-- Invoice Edit Form -->
    <div class="card" style="border: 2px solid #007BFF;">
        <div class="text-white card-header bg-primary">
            <h5 class="mb-0">
                <i class="la la-edit"></i>
                {{-- تعديل فاتورة شراء رقم {{ $invoice->id }} --}}
                تعديل فاتورة شراء
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- نوع الفاتورة -->
                <div class="col-md-6">
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

                <!-- المورد -->
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
                                     data-target="#pe_addSupplierModal">
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

            <!-- معلومات المورد -->
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

            <div class="mt-3 row">
                <!-- التصنيف -->
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

                <!-- البيان -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="bayan_txt">البيان/الوصف</label>
                        <input wire:model.live="bayan_txt" type="text" id="bayan_txt" class="form-control"
                            placeholder="أدخل وصف الفاتورة">
                        @error('bayan_txt')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- الرقم المرجعي -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="referance_number">الرقم المرجعي</label>
                        <input wire:model.live="referance_number" type="text" id="referance_number" class="form-control"
                            placeholder="أدخل الرقم المرجعي">
                        @error('referance_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- الكمية -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="qyt">الكمية</label>
                        <input wire:model.live="qyt" type="number" id="qyt" class="form-control" min="1">
                        @error('qyt')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- سعر الشراء -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="purches_price">سعر الشراء</label>
                        <input wire:model.live.debounce.700ms="purches_price" type="number" step="0.00001" min="0" id="purches_price"
                            class="form-control">
                        @error('purches_price')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- السعر الكلي -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="total_price">السعر الكلي (د.ل)</label>
                        <input wire:model.live.debounce.700ms="total_price" readonly type="number" id="total_price" class="form-control">
                        @error('total_price')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <h6><strong>بيانات الدفع</strong></h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="paid">المدفوع (د.ل)</label>
                        <input wire:model.live.debounce.700ms="paid" type="number" step="0.00001" min="0" max="{{ $total_price }}"
                            id="paid" class="form-control">
                        <span>اتركه صفرًا للدفع لاحقًا</span>
                        @error('paid')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="remaining">الباقي (د.ل)</label>
                        <input wire:model.live.debounce.700ms="remaining" readonly type="number" id="remaining" class="form-control">
                        @error('remaining')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Safe Selection (only if payment > 0) -->
            @if($paid > 0)
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="safe_id">الخزينة</label>
                        <select wire:model.live="safe_id" id="safe_id" class="form-control">
                            <option value="">اختر الخزينة</option>
                            @foreach ($safes as $safe)
                            <option value="{{ $safe->id }}">{{ $safe->name }}</option>
                            @endforeach
                        </select>
                        @error('safe_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="mt-4 text-center">
                <button type="button" wire:click="updateInvoice" class="btn btn-success btn-lg">
                    <i class="la la-edit"></i> تحديث الفاتورة
                </button>
                <a href="{{ route('dashboard.purches_invoices.index') }}" class="mr-2 btn btn-warning btn-lg">
                    <i class="la la-times"></i> إلغاء
                </a>
            </div>
        </div>
    </div>

    <!-- Modal for adding new supplier -->
    <div class="modal fade" id="pe_addSupplierModal" tabindex="-1" role="dialog" aria-labelledby="addSupplierLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="text-white modal-header bg-success">
                    <h5 class="modal-title" id="addSupplierLabel"><i class="la la-plus"></i> إضافة مورد جديد</h5>
                    <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="pe_supplierFormErrors"></div>
                    <form id="pe_addSupplierForm">
                        @csrf
                        <div class="form-group">
                            <label><strong>الاسم <span class="text-danger">*</span></strong></label>
                            <input type="text" name="name" class="form-control" placeholder="أدخل اسم المورد" required>
                        </div>
                        <div class="form-group">
                            <label><strong>رقم الهاتف <span class="text-danger">*</span></strong></label>
                            <input type="text" name="mobile" class="form-control" placeholder="أدخل رقم الهاتف"
                                required>
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
                            <textarea name="address" class="form-control" rows="2"
                                placeholder="أدخل عنوان المورد"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="button" id="pe_submitSupplierForm" class="btn btn-success">
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
            console.log('Document ready - Edit page');
            initSelect2();

            // Listen for supplier data updates
            window.addEventListener('supplier-updated', function() {
                console.log('Supplier updated');
                setTimeout(updateSelect2Values, 100);
            });
        });

        function initSelect2() {
            console.log('Init Select2 - Edit page');

            // Initialize Select2 for supplier dropdown
            $('#supplier_id').select2({
                placeholder: "اختر المورد",
                allowClear: true,
                width: '100%'
            });

            // Set the current supplier value
            @if($supplier_id)
                $('#supplier_id').val(@json($supplier_id)).trigger('change.select2');
            @endif

            $('#supplier_id').off('change.supplier').on('change.supplier', function(e) {
                var data = $(this).val();
                @this.set('supplier_id', data);
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
        }

        // Handle submit button click for supplier
        $(document).on('click', '#pe_submitSupplierForm', function(e) {
            console.log('Purchase Edit: Submit supplier button clicked');
            e.preventDefault();

            let formElement = document.getElementById('pe_addSupplierForm');
            if (!formElement) {
                console.error('pe_addSupplierForm not found');
                return;
            }

            let formData = new FormData(formElement);
            let $button = $(this);

            $.ajax({
                url: "{{ route('dashboard.suppliers.storeQuick') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $button.prop('disabled', true).html('<i class="la la-spinner fa-spin"></i> جاري الحفظ...');
                },
                success: function(response) {
                    if (response.success) {
                        $('#pe_addSupplierModal').modal('hide');
                        formElement.reset();
                        $('#pe_supplierFormErrors').html('');

                        var newOption = new Option(response.supplier.name, response.supplier.id, true, true);
                        $('#supplier_id').append(newOption).trigger('change');

                        alert(response.message);

                        @this.refreshSuppliers();
                        @this.set('supplier_id', response.supplier.id);
                    }
                },
                error: function(xhr) {
                    $button.prop('disabled', false).html('<i class="la la-save"></i> حفظ المورد');

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        let errorHtml = '<div class="alert alert-danger"><strong>حدث خطأ:</strong><ul>';
                        for (let field in errors) {
                            errorHtml += '<li>' + errors[field][0] + '</li>';
                        }
                        errorHtml += '</ul></div>';
                        $('#pe_supplierFormErrors').html(errorHtml);
                    } else {
                        $('#pe_supplierFormErrors').html('<div class="alert alert-danger">حدث خطأ، يرجى المحاولة لاحقاً</div>');
                    }
                },
                complete: function() {
                    $button.prop('disabled', false).html('<i class="la la-save"></i> حفظ المورد');
                }
            });
        });

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
