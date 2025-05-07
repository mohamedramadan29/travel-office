<button class="btn btn-primary btn-sm edit_coupon" data-coupon-id="{{ $coupon->id }}"
    data-coupon-code="{{ $coupon->code }}" data-coupon-discount-percentage="{{ $coupon->discount_percentage }}"
    data-coupon-start-date="{{ $coupon->start_date }}" data-coupon-end-date="{{ $coupon->end_date }}"
    data-coupon-limit="{{ $coupon->limit }}" data-coupon-time-used="{{ $coupon->time_used }}"
    data-coupon-is-active="{{ $coupon->is_active }}"> تعديل </button>

<form action="{{ route('dashboard.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="delete_confirm btn btn-danger btn-sm"> حذف </button>
</form>
