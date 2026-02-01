<div>
    <!-- Invoice Form Content -->
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="qyt"> الكمية </label>
                <input wire:model.live="qyt" type="number" id="qyt" class="form-control" name="qyt" min="1">
                @error('qyt')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="purches_price"> سعر الشراء </label>
                <input wire:model.live="purches_price" type="number" step="0.00001" min="0" id="purches_price"
                    class="form-control" name="purches_price">
                @error('purches_price')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <hr>
    <hr>

    <div class="card-header" style="padding: 1rem 0;">
        <h4 class="card-title"><strong>بيانات المورد</strong></h4>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="supplier_id"> المورد </label>
                <div class="input-group">
                    <div style="flex-grow: 1;" wire:ignore>
                        <select name="supplier_id" id="supplier_id" class="form-control select2">
                            <option value="">اختر المورد</option>
                            @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
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

        @if ($supplier_id)
        <div class="col-md-12">
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
        </div>
        @endif
    </div>

    <br>
    <h4 class="card-title"><strong>بيانات الدفع</strong></h4>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="paid"> المدفوع (د.ل) </label>
                <input step="0.00001" min="0" max="{{ $total_price }}" wire:model.live.debounce.700ms="paid"
                    type="number" id="paid" class="form-control" name="paid">
                <span>اتركه صفرًا للدفع لاحقًا</span>
                @error('paid')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        @if ($paid > 0)
        <div class="col-md-6">
            <div class="form-group">
                <label for="safe_id"> الخزينة </label>
                <select name="safe_id" id="safe_id" class="form-control" wire:model.live="safe_id">
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
        @endif

        <div class="col-md-6">
            <div class="form-group">
                <label for="remaining"> الباقي (د.ل) </label>
                <input wire:model.live="remaining" readonly type="number" id="remaining" class="form-control"
                    name="remaining">
                @error('remaining')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="total_price"> السعر الكلي (د.ل) </label>
                <input wire:model.live="total_price" readonly type="number" id="total_price" class="form-control"
                    name="total_price">
                @error('total_price')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Modal for adding new supplier -->
    <div class="modal fade" id="addSupplierModal" tabindex="-1" role="dialog" aria-labelledby="addSupplierLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addSupplierLabel"><i class="la la-plus"></i> إضافة مورد جديد</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
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
                    <button type="button" id="submitSupplierForm" class="btn btn-success">
                        <i class="la la-save"></i> حفظ المورد
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        $(document).ready(function() {
            console.log('Document ready');
            initSelect2();
        });

        function initSelect2() {
            console.log('Init Select2');
            $('#supplier_id').select2({
                placeholder: "اختر المورد",
                allowClear: true,
                width: '100%'
            });

            $('#supplier_id').on('change', function(e) {
                var data = $(this).val();
                @this.set('supplier_id', data);
            });
        }

        // Handle submit button click
        $(document).on('click', '#submitSupplierForm', function(e) {
            console.log('Submit button clicked');
            e.preventDefault();
            submitSupplierForm();
        });

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
    </script>
</div>
