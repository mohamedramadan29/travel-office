<a href="{{ route('dashboard.coupons.edit', $coupon->id) }}" class="btn btn-primary btn-sm"> تعديل </a>

<form action="{{ route('dashboard.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="delete_confirm btn btn-danger btn-sm"> حذف </button>
</form>
