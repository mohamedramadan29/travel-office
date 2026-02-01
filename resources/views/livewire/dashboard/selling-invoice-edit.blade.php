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
    <div class="card">
        <div class="text-white card-header">
            <h5 class="mb-0">
                <i class="la la-edit"></i>
                {{-- تعديل فاتورة بيع رقم {{ $invoice->id }} --}}
                تعديل فاتورة بيع
            </h5>
        </div>
        <div class="card-body">
            <!-- Basic Invoice Info -->
            <div class="row">
                <!-- الرقم المرجعي -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="referance_number">الرقم المرجعي</label>
                        <div wire:ignore>
                            <select wire:model.live="referance_number" id="referance_number"
                                class="form-control select2" name="referance_number">
                                <option value="">اختر الرقم المرجعي</option>
                                @foreach ($purchesInvoices as $purchesInvoice)
                                <option value="{{ $purchesInvoice->referance_number }}"
                                    data-category="{{ $purchesInvoice->category_id }}"
                                    data-bayan="{{ $purchesInvoice->bayan_txt }}"
                                    data-price="{{ $purchesInvoice->purches_price }}"
                                    @if($referance_number==$purchesInvoice->referance_number) selected @endif>
                                    {{ $purchesInvoice->referance_number }} - {{ $purchesInvoice->bayan_txt }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @error('referance_number')
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
                <!-- التصنيف -->
                <div class="col-md-12">
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
                                    <option value="{{ $client->id }}" @if($client_id==$client->id) selected @endif>{{
                                        $client->name }}</option>
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

            <!-- Client Details -->
            @if ($client_id)
            <div class="client-details"
                style="background: #e8f5e8; border-radius: 10px; padding: 15px; margin-top: 10px; border: 1px solid rgba(40, 167, 69, 0.2);">
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
            <h6 class="mt-3"><strong>بيانات المورد</strong></h6>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="supplier_id">المورد</label>
                        <div class="input-group">
                            <div style="flex-grow: 1;" wire:ignore>
                                <select name="supplier_id" id="supplier_id" class="form-control select2">
                                    <option value="">اختر المورد</option>
                                    @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @if($supplier_id==$supplier->id) selected
                                        @endif>{{ $supplier->name }}</option>
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

            <!-- Supplier Details -->
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

            <!-- Product Details -->
            <h6 class="mt-3"><strong>تفاصيل المنتج</strong></h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="qyt">الكمية</label>
                        <input wire:model.live="qyt" type="number" id="qyt" class="form-control" min="1">
                        @error('qyt')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="selling_price">سعر البيع</label>
                        <input wire:model.live="selling_price" type="number" step="0.00001" min="0" id="selling_price"
                            class="form-control">
                        @error('selling_price')
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
                        <label for="total_price">السعر الكلي (د.ل)</label>
                        <input wire:model.live="total_price" readonly type="number" id="total_price"
                            class="form-control">
                        @error('total_price')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="paid">المدفوع (د.ل)</label>
                        <input wire:model.live="paid" type="number" step="0.00001" min="0" max="{{ $total_price }}"
                            id="paid" class="form-control">
                        <span>اتركه صفرًا للدفع لاحقًا</span>
                        @error('paid')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="remaining">الباقي (د.ل)</label>
                        <input wire:model.live="remaining" readonly type="number" id="remaining" class="form-control">
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
                <a href="{{ route('dashboard.selling_invoices.index') }}" class="mr-2 btn btn-warning btn-lg">
                    <i class="la la-times"></i> إلغاء
                </a>
            </div>
        </div>
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
                                placeholder="أدخل عنوان العميل"></textarea>
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
    @section('js')
    <script>
        $(document).ready(function() {
            console.log('Document ready - Selling Edit page');
            initSelect2();

            // Listen for updates
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
            console.log('Init Select2 - Selling Edit page');

            // Initialize Select2 for client dropdown
            $('#client_id').select2({
                placeholder: "اختر العميل",
                allowClear: true,
                width: '100%'
            });

            // Set the current client value
            @if($client_id)
                $('#client_id').val(@json($client_id)).trigger('change.select2');
            @endif

            $('#client_id').off('change.client').on('change.client', function(e) {
                var data = $(this).val();
                @this.set('client_id', data);
            });

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

            // Initialize Select2 for reference number dropdown
            $('#referance_number').select2({
                placeholder: "اختر الرقم المرجعي",
                allowClear: true,
                width: '100%',
                dir: 'rtl',
                language: {
                    noResults: function() {
                        return "لا توجد نتائج";
                    },
                    searching: function() {
                        return "جاري البحث...";
                    }
                }
            });

            // Set the current reference number value
            @if($referance_number)
                $('#referance_number').val(@json($referance_number)).trigger('change.select2');
            @endif

            $('#referance_number').off('change').on('change', function(e) {
                let selectedOption = $(this).find(':selected');
                let referenceNumber = $(this).val();

                console.log('Reference number changed:', referenceNumber);

                if (referenceNumber && selectedOption.length) {
                    let categoryId = selectedOption.data('category');
                    let bayanTxt = selectedOption.data('bayan');
                    let purchesPrice = selectedOption.data('price');

                    console.log('Reference data:', {
                        referenceNumber: referenceNumber,
                        categoryId: categoryId,
                        bayanTxt: bayanTxt,
                        purchesPrice: purchesPrice
                    });

                    // Update Livewire component with selected data
                    @this.set('referance_number', referenceNumber);

                    // Update other fields if data exists
                    if (bayanTxt) {
                        @this.set('bayan_txt', bayanTxt);
                        // Also update DOM directly
                        setTimeout(function() {
                            $('#bayan_txt').val(bayanTxt);
                        }, 100);
                    }
                    if (purchesPrice) {
                        @this.set('selling_price', purchesPrice);
                        // Also update DOM directly
                        setTimeout(function() {
                            $('#selling_price').val(purchesPrice);
                        }, 100);
                    }
                    if (categoryId) {
                        @this.set('category_id', categoryId);
                        // Also update DOM directly
                        setTimeout(function() {
                            $('[wire\\:model\\.live="category_id"]').val(categoryId);
                        }, 100);
                    }
                } else {
                    @this.set('referance_number', '');
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

            // Update reference number select
            @if($referance_number)
                if ($('#referance_number').length && $('#referance_number').val() != @json($referance_number)) {
                    $('#referance_number').val(@json($referance_number)).trigger('change.select2');
                }
            @endif
        }

        // Handle Livewire events
        document.addEventListener('livewire:load', function() {
            console.log('Livewire loaded');
            initSelect2();
            setTimeout(updateSelect2Values, 200);
        });

        document.addEventListener('livewire:update', function() {
            console.log('Livewire updated');
            initSelect2();
            setTimeout(updateSelect2Values, 200);
        });

        // Initialize on page load
        $(document).ready(function() {
            console.log('Document ready - Edit page');
            initSelect2();
            setTimeout(updateSelect2Values, 500);
        });
    </script>
    @endsection
</div>
