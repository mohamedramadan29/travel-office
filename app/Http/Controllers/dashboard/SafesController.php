<?php

namespace App\Http\Controllers\dashboard;

use App\Models\admin\Safe;
use Illuminate\Http\Request;
use App\Http\Traits\Message_Trait;
use App\Models\admin\SafeMovement;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class SafesController extends Controller
{

    use Message_Trait;
    public function index()
    {
        $safes = Safe::paginate(10);
        return view('admin.safes.index',compact('safes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.safes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
      //  dd($data);
        $rules = [
            'name'=>'required|unique:safes,name|string|max:255',
            'status'=>'required|numeric',
        ];
        $messages = [
            'name.required'=>'الاسم مطلوب',
            'name.unique'=>'الاسم موجود بالفعل',
            'status.required'=>'الحالة مطلوبة',
        ];
        $validator = Validator::make($data,$rules,$messages);
        if($validator->fails()){
            return Redirect()->back()->withErrors($validator);
        }

        $safe = new Safe();
        $safe->name = $data['name'];
        $safe->status = $data['status'];
        $safe->save();
        return $this->success_message('تم اضافة الخزنة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $safe = Safe::findOrFail($id);
        return view('admin.safes.show',compact('safe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $safe = Safe::findOrFail($id);
        return view('admin.safes.edit',compact('safe'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $rules = [
            'name'=>'required|string|max:255|unique:safes,name,'.$id,
            'status'=>'required|numeric',
        ];
        $messages = [
            'name.required'=>'الاسم مطلوب',
            'name.unique'=>'الاسم موجود بالفعل',
            'status.required'=>'الحالة مطلوبة',
        ];
        $validator = Validator::make($data,$rules,$messages);
        if($validator->fails()){
            return Redirect()->back()->withErrors($validator);
        }

        $safe = Safe::findOrFail($id);
        $safe->name = $data['name'];
        $safe->status = $data['status'];
        $safe->save();
        return $this->success_message('تم تحديث الخزنة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $safe = Safe::findOrFail($id);
        $safe->delete();
        return $this->success_message('تم حذف الخزنة بنجاح');
    }

    public function ChangeStatus($id){

        $safe = Safe::findOrFail($id);
        $safe->update([
            'status' => $safe->status == 1 ? 0 : 1,
        ]);
        return $this->success_message('تم تغير الحالة بنجاح');
    }

    public function AddBalance(Request $request,$id){
        $safe = Safe::findOrFail($id);
        $current_balance = $safe['balance'];
        $data = $request->all();
        $rules = [
            'amount'=>'required|numeric',
        ];
        $messages = [
            'amount.required'=>'الكمية مطلوبة',
            'amount.numeric'=>'الكمية يجب ان تكون رقم',
        ];
        $validator = Validator::make($data,$rules,$messages);
        if($validator->fails()){
            return Redirect()->back()->withErrors($validator);
        }

        DB::beginTransaction();
        ########## Add Balance

        $safe->update([
            'balance' => $current_balance + $data['amount'],
        ]);

        ######### Add Safe Movement
        $safe_movement = new SafeMovement();
        $safe_movement->safe_id = $safe->id;
        $safe_movement->amount = $data['amount'];
        $safe_movement->admin_id = Auth::user()->id;
        $safe_movement->movment_type = 'deposit';
        $safe_movement->save();
        DB::commit();

        return $this->success_message('تم اضافة الرصيد بنجاح');
    }

    public function RemoveBalance(Request $request,$id){
        $safe = Safe::findOrFail($id);
        $current_balance = $safe['balance'];
        $data = $request->all();
        $rules = [
            'amount'=>'required|numeric',
        ];
        $messages = [
            'amount.required'=>'الكمية مطلوبة',
            'amount.numeric'=>'الكمية يجب ان تكون رقم',
        ];
        $validator = Validator::make($data,$rules,$messages);
        if($validator->fails()){
            return Redirect()->back()->withErrors($validator);
        }
        DB::beginTransaction();
        $safe->update([
            'balance' => $current_balance - $data['amount'],
        ]);

        ######### Add Safe Movement
        $safe_movement = new SafeMovement();
        $safe_movement->safe_id = $safe->id;
        $safe_movement->amount = $data['amount'];
        $safe_movement->admin_id = Auth::user()->id;
        $safe_movement->movment_type = 'withdraw';
        $safe_movement->save();
        DB::commit();
        return $this->success_message('تم ازالة الرصيد بنجاح');
    }

    public function SafeMovement($id){
        $safe = Safe::with('movements')->findOrFail($id);
        return view('admin.safes.movements',compact('safe'));
    }

}
