<?php

namespace App\Http\Controllers\dashboard;

use App\Models\admin\Safe;
use Illuminate\Http\Request;
use App\Models\admin\Expense;
use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use Illuminate\Support\Facades\Validator;

class ExpencesController extends Controller
{
    use Message_Trait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = Expense::paginate(10);

        return view('admin.expenses.index',compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $safes = Safe::active()->get();
        return view('admin.expenses.create',compact('safes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $rules = [
            'title'=>'required|string',
            'price'=>'required|min:1',
            'safe_id'=>'required|exists:safes,id',
            'description'=>'nullable|string',
        ];
        $messages = [
            'title.required'=>'العنوان مطلوب',
            'price.required'=>'السعر مطلوب',
            'safe_id.required'=>'الخزينة مطلوبة',
            'safe_id.exists'=>'الخزينة غير موجودة',
        ];
        $validator = Validator::make($data,$rules,$messages);
        if($validator->fails()){
            return Redirect()->back()->withErrors($validator);
        }

        $expense = new Expense();
        $expense->title = $data['title'];
        $expense->price = $data['price'];
        $expense->safe_id = $data['safe_id'];
        $expense->description = $data['description'];
        $expense->save();
        return $this->success_message('تم اضافة المصروف بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $expense = Expense::findOrFail($id);
        return view('admin.expenses.show',compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $expense = Expense::findOrFail($id);
        $safes = Safe::active()->get();
        return view('admin.expenses.edit',compact('expense','safes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $rules = [
            'title'=>'required|string',
            'price'=>'required|min:1',
            'safe_id'=>'required|exists:safes,id',
            'description'=>'nullable|string',
        ];
        $messages = [
            'title.required'=>'العنوان مطلوب',
            'price.required'=>'السعر مطلوب',
            'safe_id.required'=>'الخزينة مطلوبة',
            'safe_id.exists'=>'الخزينة غير موجودة',
        ];
        $validator = Validator::make($data,$rules,$messages);
        if($validator->fails()){
            return Redirect()->back()->withErrors($validator);
        }

        $expense = Expense::findOrFail($id);
        $expense->title = $data['title'];
        $expense->price = $data['price'];
        $expense->safe_id = $data['safe_id'];
        $expense->description = $data['description'];
        $expense->save();
        return $this->success_message('تم تحديث المصروف بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();
        return $this->success_message('تم حذف المصروف بنجاح');
    }
}
