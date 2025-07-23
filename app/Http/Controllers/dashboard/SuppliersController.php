<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use Illuminate\Http\Request;
use App\Models\admin\Supplier;
use Illuminate\Support\Facades\Validator;

class SuppliersController extends Controller
{
  use Message_Trait;
    public function index()
    {
        $suppliers = Supplier::paginate(10);
        return view('admin.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:suppliers,email|max:255',
            'mobile' => 'nullable|string|max:255|unique:suppliers,mobile',
            'telegram' => 'nullable|string|max:255|unique:suppliers,telegram',
            'whatsapp' => 'nullable|string|max:255|unique:suppliers,whatsapp',
            'status' => 'required|numeric',
            'address' => 'nullable|string|max:255',
        ];
        $messages = [
            'name.required' => 'الاسم مطلوب',
            'email.unique' => 'البريد الالكتروني موجود بالفعل',
            'mobile.unique' => 'رقم الهاتف موجود بالفعل',
            'telegram.unique' => 'رقم التيلغرام موجود بالفعل',
            'whatsapp.unique' => 'رقم الواتساب موجود بالفعل',
            'status.required' => 'الحالة مطلوبة',
           // 'address.required' => 'العنوان مطلوب',
        ];
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $supplier = new Supplier();
        $supplier->name = $data['name'];
        $supplier->email = $data['email'];
        $supplier->mobile = $data['mobile'];
        $supplier->telegram = $data['telegram'];
        $supplier->whatsapp = $data['whatsapp'];
        $supplier->status = $data['status'];
        $supplier->address = $data['address'];
        $supplier->save();
      return $this->success_message('تم اضافة المورد بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supplier = Supplier::findOrFail($id);

        $data = $request->all();
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:suppliers,email,' . $supplier->id,
            'mobile' => 'nullable|string|max:255|unique:suppliers,mobile,' . $supplier->id,
            'telegram' => 'nullable|string|max:255|unique:suppliers,telegram,' . $supplier->id,
            'whatsapp' => 'nullable|string|max:255|unique:suppliers,whatsapp,' . $supplier->id,
            'status' => 'required|numeric',
            'address' => 'nullable|string|max:255',
        ];
        $messages = [
            'name.required' => 'الاسم مطلوب',
            'email.unique' => 'البريد الالكتروني موجود بالفعل',
            'mobile.unique' => 'رقم الهاتف موجود بالفعل',
            'telegram.unique' => 'رقم التيلغرام موجود بالفعل',
            'whatsapp.unique' => 'رقم الواتساب موجود بالفعل',
            'status.required' => 'الحالة مطلوبة',
           // 'address.required' => 'العنوان مطلوب',
        ];
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $supplier->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'telegram' => $data['telegram'],
            'whatsapp' => $data['whatsapp'],
            'status' => $data['status'],
            'address' => $data['address'],
        ]);
        return $this->success_message('تم تحديث المورد بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return $this->success_message('تم حذف المورد بنجاح');
    }

    public function ChangeStatus($id){

        $supplier = Supplier::findOrFail($id);
        $supplier->update([
            'status' => $supplier->status == 'نشط' ? '0' : '1',
        ]);
        return $this->success_message('تم تغير الحالة بنجاح');
    }

}
