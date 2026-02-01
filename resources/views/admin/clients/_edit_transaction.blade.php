<div class="text-left modal fade" id="edittransaction{{ $transaction->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel2">
                    <i class="la la-edit"></i> تعديل الدفعة
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('dashboard.clients.transactions.update', $transaction->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>المبلغ</label>
                        <input required type="number" step="0.01" name="amount" min="0.01" class="form-control"
                            value="{{ $transaction->amount }}" placeholder="ادخل المبلغ">
                    </div>
                    <div class="form-group">
                        <label>الخزينة</label>
                        <select name="safe_id" class="form-control" required>
                            <option value="" disabled> -- حدد الخزينة -- </option>
                            @foreach ($safes as $safe)
                                <option value="{{ $safe->id }}" {{ $transaction->safe_id == $safe->id ? 'selected' : '' }}>
                                    {{ $safe->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>ملاحظات</label>
                        <textarea name="description" class="form-control" placeholder="ادخل ملاحظات">{{ $transaction->description }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">
                        حفظ التعديلات
                    </button>
                    <button type="button" class="btn grey btn-outline-secondary btn-sm" data-dismiss="modal">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
