<?php

namespace App\Http\Controllers\dashboard;

use App\Models\admin\Client;
use Illuminate\Http\Request;
use App\Http\Traits\Message_Trait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ClientsController extends Controller
{
    use Message_Trait;
    public function index()
    {
        $clients = Client::paginate(10);
        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.clients.create');
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
        $client = new Client();
        $client->name = $data['name'];
        $client->email = $data['email'];
        $client->mobile = $data['mobile'];
        $client->telegram = $data['telegram'];
        $client->whatsapp = $data['whatsapp'];
        $client->status = $data['status'];
        $client->address = $data['address'];
        $client->save();
      return $this->success_message('تم اضافة المورد بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $client = Client::findOrFail($id);
        return view('admin.clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = Client::findOrFail($id);
        return view('admin.clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $client = Client::findOrFail($id);

        $data = $request->all();
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:clients,email,' . $client->id,
            'mobile' => 'nullable|string|max:255|unique:clients,mobile,' . $client->id,
            'telegram' => 'nullable|string|max:255|unique:clients,telegram,' . $client->id,
            'whatsapp' => 'nullable|string|max:255|unique:clients,whatsapp,' . $client->id,
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
        $client->update([
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
        $client = Client::findOrFail($id);
        $client->delete();
        return $this->success_message('تم حذف المورد بنجاح');
    }

    public function ChangeStatus($id){

        $client = Client::findOrFail($id);
        $client->update([
            'status' => $client->status == 'نشط' ? '0' : '1',
        ]);
        return $this->success_message('تم تغير الحالة بنجاح');
    }

}
