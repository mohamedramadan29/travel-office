<div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="userinput1"> الرقم المرجعي </label>

                @if($this->selling_invoice)
                <input readonly required wire:model.live='referance_number' type="text" id="userinput1" class="form-control" name="referance_number"
                    value="{{ old('referance_number') }}">

                @else
                <div class="form-group">
                    <select wire:model.live='referance_number' name="referance_number" id=""
                        class="form-control">
                        <option value="">اختر الرقم المرجعي</option>
                        @foreach ($purchesInvoices as $purcheinvoice)
                            <option @if (old('referance_number') == $purcheinvoice->referance_number) selected @endif value="{{ $purcheinvoice->referance_number }}">{{ $purcheinvoice->referance_number }}</option>
                        @endforeach
                    </select>
                    @if ($referance_error)
                        <span class="text-danger">{{ $referance_error }}</span>
                    @endif
                </div>
                @endif
                @error('referance_number')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                @if ($referance_error)
                    <span class="text-danger">{{ $referance_error }}</span>
                @endif
            </div>

        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="userinput1"> البيان / الوصف </label>
                <input required wire:model.live='bayan_txt' type="text" id="userinput1" class="form-control" name="bayan_txt"
                    value="{{ old('bayan_txt') }}">
                @error('bayan_txt')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="category_id"> التصنيف </label>
                <select name="category_id" id="category_id" class="form-control" wire:model.live="category_id">
                    <option value="">اختر التصنيف </option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="qyt"> الكمية </label>
                <input wire:model.live="qyt" type="number" id="qyt" class="form-control" name="qyt"
                    min="1">
                @error('qyt')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="selling_price"> سعر البيع </label>
                <input wire:model.live="selling_price" type="number" step="0.01" min="0" id="selling_price"
                    class="form-control" name="selling_price">
                @error('selling_price')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    <hr>
    <h4 class="card-title" id="basic-layout-colored-form-control"><strong>بيانات العميل</strong></h4>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="client_id"> العميل </label>
                <select name="client_id" id="client_id" class="form-control" wire:model.live="client_id" wire:change="getClientInfo">
                    <option value="">اختر العميل</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
                @error('client_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        @if ($client_id)
            <div class="supplier-details"
                style="background: #f8f9fa; border-radius: 10px; padding: 15px; margin-top: 10px; border: 1px solid rgba(44, 62, 80, 0.2); width:100%">
                <div class="supplier-info" style="display: flex; justify-content: space-between; margin-top: 10px;">
                    <div class="supplier-info-item"
                        style="font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-phone"></i>
                        <div>
                            <strong>رقم الهاتف:</strong> <span
                                    id="purchase_supplier_phone">{{ $client_mobile }}</span>
                        </div>
                    </div>
                    <div class="supplier-info-item"
                        style="font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                        <i class="fab fa-whatsapp"></i>
                        <div><strong>رقم الواتساب:</strong> <span
                                id="purchase_supplier_whatsapp">{{ $client_whatsapp }}</span></div>
                    </div>
                    <div class="supplier-info-item"
                        style="font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-envelope"></i>
                        <div><strong>البريد الإلكتروني:</strong> <span
                                id="purchase_supplier_email">{{ $client_email }}</span></div>
                    </div>
                    <div class="supplier-info-item"
                        style="font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-map-marker-alt"></i>
                        <div><strong>العنوان:</strong> <span
                                id="purchase_supplier_address">{{ $client_address }}</span></div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <br>
    <br>
    <hr>
    <h4 class="card-title" id="basic-layout-colored-form-control"><strong>بيانات المورد</strong></h4>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="supplier_id"> المورد </label>
                <select name="supplier_id" id="supplier_id" class="form-control" wire:model.live="supplier_id">
                    <option value="">اختر المورد</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        @if ($supplier_id)
            <div class="supplier-details"
                style="background: #f8f9fa; border-radius: 10px; padding: 15px; margin-top: 10px; border: 1px solid rgba(44, 62, 80, 0.2); width:100%">
                <div class="supplier-info" style="display: flex; justify-content: space-between; margin-top: 10px;">
                    <div class="supplier-info-item"
                        style="font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-phone"></i>
                        <div>
                            <strong>رقم الهاتف:</strong> <span
                                    id="purchase_supplier_phone">{{ $supplier_mobile }}</span>
                        </div>
                    </div>
                    <div class="supplier-info-item"
                        style="font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                        <i class="fab fa-whatsapp"></i>
                        <div><strong>رقم الواتساب:</strong> <span
                                id="purchase_supplier_whatsapp">{{ $supplier_whatsapp }}</span></div>
                    </div>
                    <div class="supplier-info-item"
                        style="font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-envelope"></i>
                        <div><strong>البريد الإلكتروني:</strong> <span
                                id="purchase_supplier_email">{{ $supplier_email }}</span></div>
                    </div>
                    <div class="supplier-info-item"
                        style="font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-map-marker-alt"></i>
                        <div><strong>العنوان:</strong> <span
                                id="purchase_supplier_address">{{ $supplier_address }}</span></div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <br>
    <br>
    <h4 class="card-title" id="basic-layout-colored-form-control"><strong>بيانات الدفع</strong></h4>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="paid"> المدفوع (د.ل) </label>
                <input min="0" max="{{ $total_price }}" wire:model.live.debounce.700ms="paid" type="number"
                    id="paid" class="form-control" name="paid">
                <span>اتركه صفرًا للدفع لاحقًا</span>
                @error('paid')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        @if($paid > 0)
        {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="payment_method"> طريقة الدفع </label>
                <select name="payment_method" id="payment_method" class="form-control" wire:model.live="payment_method">
                    <option value="">اختر طريقة الدفع</option>
                    <option value="نقدا">نقدا</option>
                    <option value="شيك">شيك</option>
                    <option value="تحويل بنكي">تحويل بنكي</option>
                </select>
                @error('payment_method')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div> --}}
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

</div>
