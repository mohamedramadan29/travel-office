<a href="{{ route('dashboard.products.edit', $product->id) }}" class="btn btn-primary btn-sm edit_product"> تعديل </a>

<button class="btn btn-success btn-sm edit_product change_status" data-product-id = {{ $product->id }} > تعديل الحالة </button>

<a href="{{ route('dashboard.products.show', $product->id) }}" class="btn btn-info btn-sm"> <i class="la la-eye"></i> </a>

<form action="{{ route('dashboard.products.destroy', $product->id) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="delete_confirm btn btn-danger btn-sm"> حذف </button>
</form>
