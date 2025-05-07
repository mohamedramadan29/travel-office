<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttributeRequest;
use App\Http\Services\Dashboard\AttributeService;
use App\Http\Traits\Message_Trait;
use App\Models\admin\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AttributeController extends Controller
{
    use Message_Trait;

    protected $attributeService;
    public function __construct(AttributeService $attributeService)
    {
        $this->attributeService = $attributeService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.attributes.index');
    }

    public function AttributesAll()
    {
        return $this->attributeService->getAttributes();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.attributes._create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttributeRequest $request)
    {
        $data = $request->except('_token');
        $attribute = $this->attributeService->createAttribute($data);
        // $attribute = new Attribute();
        // $attribute->name = $data['name'];
        // $attribute->save();

        // ######### Create Attribute Values

        // foreach ($data['value'] as $item) {
        //     $attribute->Attributevalues()->create([
        //         'attribute_id'=>$attribute->id,
        //         'value' => $item
        //     ]);
        // }
        if (!$attribute) {
            return $this->Error_message(' لم تتم  ');
        }
        return $this->success_message(' تم اضافة السمة  بنجاح  ');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttributeRequest $request, string $id)
    {
      //  dd($request);
        $data = $request->except('_token');
        $attribute = $this->attributeService->updateAttribute($data, $id);
        if (!$attribute) {
            return Response()->json([
                'status' => 'error',
                'message' => ' لم يتم تعديل السمة  بنجاح ',
            ], 500);
        }
        return Response()->json([
            'status' => 'success',
            'message' => ' تم تعديل السمة  بنجاح ',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        if (!$this->attributeService->deleteAttribute($id)) {
            return $this->error_message('حدث خطأ ما أثناء حذف السمة ');
        }
        return $this->success_message(' تم حذف السمة بنجاح  ');
    }
}
