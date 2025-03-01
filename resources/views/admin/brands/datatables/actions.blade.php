<a href="{{ route('dashboard.brands.edit', $brand->id) }}" class="btn btn-primary btn-sm"> تعديل </a>

<form action="{{ route('dashboard.brands.destroy', $brand->id) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="delete_confirm btn btn-danger btn-sm"> حذف </button>
</form>
