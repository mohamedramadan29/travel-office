<div class="text-left modal fade" id="removebalance{{ $safe->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel2">
                    <i class="la la-road2"></i>  ازالة رصيد من الخزينة
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('dashboard.safes.remove_balance',$safe->id) }}" method="POST">
                @csrf
            <div class="modal-body">
                    <div class="form-group">
                        <input class="form-control" type="text" disabled readonly value="{{ $safe->name }}">
                    </div>
                    <div class="form-group">
                        <input type="number" step="0.01" name="amount" min="1" class="form-control">
                    </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm">
                    ازالة
                </button>
                <button type="button" class="btn grey btn-outline-secondary btn-sm" data-dismiss="modal">
                    رجوع
                </button>
            </div>
        </form>
        </div>
    </div>
</div>
