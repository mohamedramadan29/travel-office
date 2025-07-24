<?php

namespace App\Http\Controllers\dashboard;

use App\Models\admin\Safe;
use Illuminate\Http\Request;
use App\Models\admin\Category;
use App\Models\admin\Supplier;
use App\Http\Traits\Message_Trait;
use Illuminate\Support\Facades\DB;
use App\Models\admin\PurcheInvoice;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class PurchesInvoicesController extends Controller
{

    use Message_Trait;
    public function index()
    {
        $invoices = PurcheInvoice::paginate(10);
        return view('admin.invoices.purches.index', compact('invoices'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::active()->get();
        $safes = Safe::active()->get();
        $categories = Category::active()->get();
        return view('admin.invoices.purches.create', compact('suppliers', 'safes','categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['admin_id'] = Auth::user()->id;
        $rules = [
            'type'=>'required'
        ];
        if($data['type'] == 'فاتورة رسمية'){
            $rules = [
                'bayan_txt'=>'required',
                'referance_number'=>'required|unique:purche_invoices,referance_number',
                'supplier_id' => 'required',
                'qyt' => 'required',
                'purches_price' => 'required',
                'total_price' => 'required',
                'paid' => 'required',
                'remaining' => 'required',
                'payment_method' => 'required',
                'safe_id' => 'required',
            ];
        }else{
            $rules = [
                'bayan_txt'=>'required',
                'referance_number'=>'required|unique:purche_invoices,referance_number',
                'supplier_id' => 'required',
            ];
        }
       $messages = [
        'bayan_txt.required'=>'البيان / الوصف مطلوب',
        'referance_number.required'=>'الرقم المرجعي مطلوب',
        'referance_number.unique'=>'الرقم المرجعي موجود',
        'supplier_id.required'=>'المورد مطلوب',
        'qyt.required'=>'الكمية مطلوبة',
        'purches_price.required'=>'سعر الشراء مطلوب',
        'total_price.required'=>'السعر الكلي مطلوب',
        'paid.required'=>'المدفوع مطلوب',
        'remaining.required'=>'الباقي مطلوب',
        'payment_method.required'=>'طريقة الدفع مطلوبة',
        'safe_id.required'=>'الخزنة مطلوبة',
       ];
       $validator = Validator::make($data,$rules,$messages);
       if($validator->fails()){
           return redirect()->back()->withErrors($validator)->withInput();
       }
       try{
        DB::beginTransaction();
        $invoice = new PurcheInvoice;
        $invoice->type = $data['type'];
        $invoice->bayan_txt = $data['bayan_txt'];
        $invoice->referance_number = $data['referance_number'];
        $invoice->supplier_id = $data['supplier_id'];
        $invoice->qyt = $data['qyt'];
        $invoice->purches_price = $data['purches_price'];
        $invoice->total_price = $data['total_price'];
        $invoice->paid = $data['paid'];
        $invoice->remaining = $data['remaining'];
        $invoice->payment_method = $data['payment_method'];
        $invoice->safe_id = $data['safe_id'];
        $invoice->category_id = $data['category_id'];
        $invoice->admin_id = Auth::user()->id;
        $invoice->save();
        DB::commit();
        return $this->success_message(' تم اضافة الفاتورة بنجاح  ');
       }catch(\Exception $e){
        DB::rollBack();
        return $this->exception_message($e);
       }
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
        $invoice = PurcheInvoice::findOrFail($id);
        $suppliers = Supplier::active()->get();
        $safes = Safe::active()->get();
        $categories = Category::active()->get();
        return view('admin.invoices.purches.edit',compact('invoice','suppliers','safes','categories'));
    }
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $invoice = PurcheInvoice::findOrFail($id);
        $data['admin_id'] = Auth::user()->id;
        $rules = [
            'type'=>'required'
        ];
        if($data['type'] == 'فاتورة رسمية'){
            $rules = [
                'bayan_txt'=>'required',
                'referance_number'=>'required|unique:purche_invoices,referance_number,'.$id,
                'supplier_id' => 'required',
                'qyt' => 'required|numeric|min:1',
                'purches_price' => 'required|numeric|min:1',
                'total_price' => 'required|numeric|min:1',
                'paid' => 'required',
                'remaining' => 'required',
                'payment_method' => 'required',
                'safe_id' => 'required',
            ];
        }else{
            $rules = [
                'bayan_txt'=>'required',
                'referance_number'=>'required|unique:purche_invoices,referance_number,'.$id,
                'supplier_id' => 'required',
            ];
        }
       $messages = [
        'bayan_txt.required'=>'البيان / الوصف مطلوب',
        'referance_number.required'=>'الرقم المرجعي مطلوب',
        'referance_number.unique'=>'الرقم المرجعي موجود',
        'supplier_id.required'=>'المورد مطلوب',
        'qyt.required'=>'الكمية مطلوبة',
       'purches_price.required' => 'سعر الشراء مطلوب',
        'purches_price.numeric' => 'سعر الشراء يجب أن يكون رقمًا',
        'purches_price.min' => 'سعر الشراء يجب أن يكون 1 أو أكثر',
        'total_price.required' => 'السعر الكلي مطلوب',
        'total_price.numeric' => 'السعر الكلي يجب أن يكون رقمًا',
        'total_price.min' => 'السعر الكلي يجب أن يكون 1 أو أكثر',
        'paid.required'=>'المدفوع مطلوب',
        'remaining.required'=>'الباقي مطلوب',
        'payment_method.required'=>'طريقة الدفع مطلوبة',
        'safe_id.required'=>'الخزنة مطلوبة',
       ];
       $validator = Validator::make($data,$rules,$messages);
       if($validator->fails()){
           return redirect()->back()->withErrors($validator)->withInput();
       }
       try{
        DB::beginTransaction();
        $invoice->update([
            "type" => $data['type'],
            "bayan_txt" => $data['bayan_txt'],
            "referance_number" => $data['referance_number'],
            "supplier_id" => $data['supplier_id'],
            "qyt" => $data['qyt'],
            "purches_price" => $data['purches_price'],
            "total_price" => $data['total_price'],
            "paid" => $data['paid'],
            "remaining" => $data['remaining'],
            "payment_method" => $data['payment_method'],
            "safe_id" => $data['safe_id'],
            "category_id" => $data['category_id'],
        ]);
        DB::commit();
        return $this->success_message(' تم تعديل الفاتورة بنجاح  ');
       }catch(\Exception $e){
        DB::rollBack();
        return $this->exception_message($e);
       }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $invoice = PurcheInvoice::findOrFail($id);
        try{
            $invoice->delete();
            return $this->success_message(' تم حذف الفاتورة بنجاح  ');
        }catch(\Exception $e){
            return $this->exception_message($e);
        }
    }

    public function PurchesInvoice($type){
        if($type == 'official'){
            $type = 'فواتير شراء رسمية';
            $invoices = PurcheInvoice::where('type','فاتورة رسمية')->paginate(10);
        }elseif($type == 'interim'){
            $type = 'فواتير شراء  مؤقتة';
            $invoices = PurcheInvoice::where('type','فاتورة مؤقتة')->paginate(10);
        }else{
            $type = 'فواتير شراء  مؤقتة';
            $invoices = PurcheInvoice::paginate(10);
        }
        return view('admin.invoices.purches.invoices-by-type', compact('invoices','type'));
    }

    public function ConvertToOfficial($id){
        $invoice = PurcheInvoice::findOrFail($id);
        if(!$invoice){
            abort(404);
        }
        if($invoice['bayan_txt'] !='' && $invoice['referance_number'] !='' && $invoice['supplier_id'] !='' && $invoice['qyt'] !=''
        && $invoice['purches_price'] !='' && $invoice['purches_price'] > 0 && $invoice['total_price'] !='' && $invoice['total_price'] > 0
        && $invoice['payment_method'] !='' && $invoice['safe_id'] !='' && $invoice['admin_id'] !=''
        ){
            $invoice->update([
                "type" => "فاتورة رسمية",
            ]);
            return $this->success_message(' تم تحويل الفاتورة إلى فاتورة رسمية بنجاح  ');
        }
       // return Redirect()->back()->withInput()->withErrors(' يجب اكمال جميع بيانات الفاتورة ');
       return to_route('dashboard.purches_invoices.edit',$invoice->id)->withErrors(' يجب اكمال جميع بيانات الفاتورة ');
    }
}
