<?php
namespace App\Http\Repositories\Dashboard;


class AttributeValuesRepository
{

    public function createAttributeValue($attribute, $item)
    {
        $attributevalue = $attribute->Attributevalues()->create([
            'value' => $item
        ]);
        return $attributevalue;
    }

    public function UpdateAttributeValues($attribute, $key, $item)
    {
        return $attribute->Attributevalues()->updateOrCreate([
            'id' => $key
        ], [
            'value' => $item,
        ]);
    }
    public function deleteAttributeValues($attribute){
        return $attribute->Attributevalues()->delete();
    }
}
