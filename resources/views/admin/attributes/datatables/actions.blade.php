<button type="button" class="btn btn-primary btn-sm edit_attribute" data-toggle="modal" data-target="#editattribute"
    data-id={{ $attribute->id }} data-name-ar = "{{ $attribute->getTranslation('name', 'ar') }}"
    data-name-en = "{{ $attribute->getTranslation('name', 'en') }}"
    data-values={{ $attribute->attributeValues->map(
            fn($value) => [
                'id' => $value->id,
                'value_ar' => $value->getTranslation('value', 'ar'),
                'value_en' => $value->getTranslation('value', 'en'),
            ],
        )->toJson() }}>
    تعديل
</button>

<form action="{{ route('dashboard.attributes.destroy', $attribute->id) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="delete_confirm btn btn-danger btn-sm"> حذف </button>
</form>
