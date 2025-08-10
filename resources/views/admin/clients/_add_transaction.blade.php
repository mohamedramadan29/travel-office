<div class="text-left modal fade" id="addtransaction{{ $client->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel2">
                    <i class="la la-road2"></i> اضافة دفعة من العميل
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('dashboard.client.add_transaction', $client->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input class="form-control" type="text" disabled readonly value="{{ $client->name }}">
                    </div>
                    <div class="form-group">
                        <input required type="number" step="0.01" name="amount" min="1" class="form-control"
                            placeholder="ادخل المبلغ">
                    </div>
                    <div class="form-group">
                        <select name="invoice_id" class="form-control">
                            <option value="" selected> -- حدد فاتورة البيع  -- </option>
                            @foreach ($invoices as $invoice)
                                <option value="{{ $invoice->id }}">{{ $invoice->referance_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="safe_id" class="form-control" required>
                            <option value="" selected disabled> -- حدد الخزينة -- </option>
                            @foreach ($safes as $safe)
                                <option value="{{ $safe->id }}">{{ $safe->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">
                        اضافة
                    </button>
                    <button type="button" class="btn grey btn-outline-secondary btn-sm" data-dismiss="modal">
                        رجوع
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
