@foreach ($attribute->attributeValues as $attributevalue)
    <span class="badge badge-info">
        {{ $attributevalue->getTranslation('value', App::getLocale()) }}
    </span>
@endforeach
