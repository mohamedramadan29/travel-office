<?php
namespace App\Http\Repositories\Dashboard;

use App\Models\admin\Attribute;

class AttributeRepository
{


    public function getAttribute($id)
    {
        $attribute = Attribute::with('Attributevalues')->find($id);
        return $attribute;
    }

    public function getAttributes()
    {
        $attributes = Attribute::with('Attributevalues')->get();
        return $attributes;
    }

    public function createAttribute($data)
    {
        $attribute = Attribute::create([
            'name' => $data['name']
        ]);
        return $attribute;
    }

    public function updateAttribute($data, $attribute)
    {
        $attribute->name = $data['name'];
        $attribute->save();
        return $attribute;
    }

    public function deleteAttribute($attribute)
    {
        //  $attributeValues = $attribute->attributevalues->delete();
        $attribute->delete();
        return $attribute;
    }
}
