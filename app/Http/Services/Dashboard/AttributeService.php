<?php
namespace App\Http\Services\Dashboard;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Repositories\Dashboard\AttributeRepository;
use App\Http\Repositories\Dashboard\AttributeValuesRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class AttributeService
{


    protected $attributeRepository;
    protected $attributeValuesRepository;
    public function __construct(AttributeRepository $attributeRepository, AttributeValuesRepository $attributeValuesRepository)
    {
        $this->attributeRepository = $attributeRepository;
        $this->attributeValuesRepository = $attributeValuesRepository;
    }

    public function getAttribute($id)
    {
        $attribute = $this->attributeRepository->getAttribute($id);
        if (!$attribute) {
            abort(404);
        }
        return $attribute;
    }

    public function getAll(){
        return $this->attributeRepository->getAttributes();
    }

    public function getAttributes()
    {
        $attributes = $this->attributeRepository->getAttributes();

        //  dd($attributes);
        return DataTables::of($attributes)
            ->addIndexColumn()
            ->addColumn('name', function ($attribute) {
                return $attribute->getTranslation('name', app()->getLocale());
            })
            ->addColumn('attributevalues', content: function ($attribute) {
                return view('admin.attributes.datatables.attribute-values', compact('attribute'));
            })
            ->addColumn('action', function ($attribute) {
                return view('admin.attributes.datatables.actions', compact('attribute'));
            })
            ->make(true);
    }

    public function createAttribute($data)
    {
        try {
            DB::beginTransaction();
            $attribute = $this->attributeRepository->createAttribute($data);
            foreach ($data['value'] as $item) {
                $this->attributeValuesRepository->createAttributeValue($attribute, $item);
            }
            DB::commit();
            return $attribute;
        } catch (\Exception $e) {
            DB::rollBack();
            // throw $e;
            //  Log::error('Attribute Error Create',$e->getMessage());
            return Redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    ######## Update Attribute Values
    public function updateAttribute($data, $id)
    {
        try {
            DB::beginTransaction();
            $attribute = $this->getAttribute($id);
          //  dd($attribute);
            $attribute = $this->attributeRepository->updateAttribute($data, $attribute);
            $this->attributeValuesRepository->deleteAttributeValues($attribute);
            foreach ($data['value'] as $key => $item) {
                $this->attributeValuesRepository->UpdateAttributeValues($attribute, $key, $item);
                // $attribute->Attributevalues()->updateOrCreate([
                //     'id' => $key
                // ], [
                //     'value' => $item,
                // ]);
            }
            DB::commit();
            return true;
            // return $attribute;

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
            // throw $e;
            //  Log::error('Attribute Error Create',$e->getMessage());
            // return Redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }
    public function deleteAttribute($id)
    {
        $attribute = $this->getAttribute($id);

        return $this->attributeRepository->deleteAttribute($attribute);

    }
}
