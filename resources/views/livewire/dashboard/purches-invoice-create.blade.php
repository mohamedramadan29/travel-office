<div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="qyt"> الكمية </label>
                <input wire:model.live="qyt" type="number" id="qyt" class="form-control" name="qyt" min="1">
                @error('qyt') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="purches_price"> سعر الشراء </label>
                <input wire:model.live="purches_price" type="number" step="0.01" min="0" id="purches_price" class="form-control" name="purches_price">
                @error('purches_price') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

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
                @error('supplier_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        @if ($supplier_id)
        <div class="supplier-details"
            style="background: #f8f9fa; border-radius: 10px; padding: 15px; margin-top: 10px; border: 1px solid rgba(44, 62, 80, 0.2); width:100%">
            <div class="supplier-info"
                style="display: flex; justify-content: space-between; margin-top: 10px;">
                <div class="supplier-info-item"
                    style="font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-phone"></i>
                    <div><还不 ><strong>رقم الهاتف:</strong> <span id="purchase_supplier_phone">{{ $supplier_mobile }}</span></div>
                </div>
                <div class="supplier-info-item"
                    style="font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fab fa-whatsapp"></i>
                    <div><strong>رقم الواتساب:</strong> <span id="purchase_supplier_whatsapp">{{ $supplier_whatsapp }}</span></div>
                </div>
                <div class="supplier-info-item"
                    style="font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-envelope"></i>
                    <div><strong>البريد الإلكتروني:</strong> <span id="purchase_supplier_email">{{ $supplier_email }}</span></div>
                </div>
                <div class="supplier-info-item"
                    style="font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-map-marker-alt"></i>
                    <div><strong>العنوان:</strong> <span id="purchase_supplier_address">{{ $supplier_address }}</span></div>
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
                <label for="payment_method"> طريقة الدفع </label>
                <select name="payment_method" id="payment_method" class="form-control" wire:model.live="payment_method">
                    <option value="">اختر طريقة الدفع</option>
                    <option value="نقدا">نقدا</option>
                    <option value="شيك">شيك</option>
                    <option value="تحويل بنكي">تحويل بنكي</option>
                </select>
                @error('payment_method') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="safe_id"> الخزينة </label>
                <select name="safe_id" id="safe_id" class="form-control" wire:model.live="safe_id">
                    <option value="">اختر الخزينة</option>
                    @foreach ($safes as $safe)
                        <option value="{{ $safe->id }}">{{ $safe->name }}</option>
                    @endforeach
                </select>
                @error('safe_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="paid"> المدفوع (د.ل) </label>
                <input min="0" max="{{ $total_price }}" wire:model.live="paid" type="number" id="paid" class="form-control" name="paid">
                <span>اتركه صفرًا للدفع لاحقًا</span>
                @error('paid') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="remaining"> الباقي (د.ل) </label>
                <input wire:model.live="remaining" readonly type="number" id="remaining" class="form-control" name="remaining">
                @error('remaining') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="total_price"> السعر الكلي (د.ل) </label>
                <input wire:model.live="total_price" readonly type="number" id="total_price" class="form-control" name="total_price">
                @error('total_price') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

</div>
