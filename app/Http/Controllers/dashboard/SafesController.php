<?php

namespace App\Http\Controllers\dashboard;

use Mpdf\Mpdf;
use App\Models\admin\Safe;
use App\Exports\SafesExport;
use Illuminate\Http\Request;
use App\Http\Traits\Message_Trait;
use App\Models\admin\SafeMovement;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\admin\SafeTransaction;
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

        ############################################# Start Add Transaction To Safe ############################
        $safeTransaction = new SafeTransaction();
        $safeTransaction->safe_id = $id;
        $safeTransaction->amount = $data['amount'];
        $safeTransaction->type = 'deposit';
        $safeTransaction->description = ' اضافة رصيد عام الي الخزينة  ';
        $safeTransaction->save();
        ############################################ End Add Transaction To Safe ###############################
        ################## Update Safe Balance #########
        $safe->update([
            'balance' => $current_balance + $data['amount'],
        ]);
        DB::commit();
        ################ End Update Safe Balance ########
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
        ########## Check If Safe Have Amount Or Not
        // if($current_balance < $data['amount']){
        //     return Redirect()->back()->withErrors('الرصيد الحالي غير كافٍ');
        // }
        DB::beginTransaction();

        ############################################# Start Add Transaction To Safe ############################
        $safeTransaction = new SafeTransaction();
        $safeTransaction->safe_id = $id;
        $safeTransaction->amount = $data['amount'];
        $safeTransaction->type = 'withdraw';
        $safeTransaction->description = ' ازالة رصيد عام من الخزينة  ';
        $safeTransaction->save();
        ############################################ End Add Transaction To Safe ###############################
        ################## Update Safe Balance #########
        $safe->update([
            'balance' => $current_balance - $data['amount'],
        ]);
        DB::commit();
        ################ End Update Safe Balance ########
        return $this->success_message('تم ازالة الرصيد بنجاح');
    }

    public function SafeMovement($id){
        $safe = Safe::findOrFail($id);
        $safeTransactions = SafeTransaction::where('safe_id',$id)->get();
        return view('admin.safes.movements',compact('safe','safeTransactions'));
    }


    ########################################### Generate Safes Pdf ##########################################
    public function SafesPdf(){
        $safes = Safe::latest()->get();
        // إعداد محتوى HTML
        $html = '
        <html lang="ar" dir="rtl">
        <head>
            <style>
                body {
                    font-family: "Cairo", sans-serif; /* اختر خط يدعم اللغة العربية */
                    text-align: right; /* محاذاة النصوص لليمين */
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #000;
                    padding: 8px;
                    text-align: right; /* لمحاذاة النصوص داخل الجدول */
                }
                th {
                    background-color: #f2f2f2; /* لون خلفية للرأس */
                }
            </style>
        </head>
        <body>
          <div style="text-align:center; margin:auto;display:block">
            <img  src="' . url('assets/admin/images/logo.png') . '" style="width:120px;" alt="Logo">
            <h4>   تقرير عن  الخزائن   </h4>
            </div>

            <table>
                <thead>
                    <tr>
                        <th> الاسم </th>
                        <th> الرصيد الحالي  </th>
                        <th> الحالة </th>
                        <th> تاريخ الانشاء </th>
                    </tr>
                </thead>
                <tbody>';

        // تعبئة البيانات داخل الجدول
        foreach ($safes as $safe) {
            $html .= '
                    <tr>
                        <td>' . $safe->name . '</td>
                        <td>' . number_format($safe->balance,2) . ' دينار </td>
                        <td>' . $safe->SafeStatus($safe->status)  . '</td>
                        <td>' . $safe->created_at . '</td>
                    </tr>';
        }
        $html .= '
                </tbody>
            </table>
        </body>
        </html>';

        // إعداد mPDF
        $mpdf = new Mpdf([
            'default_font' => 'Cairo', // خط يدعم اللغة العربية
        ]);

        // تحميل المحتوى إلى ملف PDF
        $mpdf->WriteHTML($html);
        // توليد ملف PDF وإرساله للتنزيل
        return $mpdf->Output('تقرير عن الموظفين.pdf', 'I'); // 'I' لعرض الملف في المتصفح

    }

    ######################################### Generate Safes Excel ############################

    public function SafesExcel(){
        return (new SafesExport())->download('Safes.xlsx');
    }

}
