<?php
namespace App\Http\Services\Dashboard;
use App\Http\Repositories\Dashboard\AttributeValuesRepository;
class AttributeValuesServices
{
    protected $attributeValuesRepository;
    public function __construct(AttributeValuesRepository $attributeValuesRepository)
    {
        $this->attributeValuesRepository = $attributeValuesRepository;
    }
}

