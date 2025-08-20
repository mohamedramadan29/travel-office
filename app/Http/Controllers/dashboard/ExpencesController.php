<?php

namespace App\Http\Controllers\dashboard;

use Mpdf\Mpdf;
use App\Models\admin\Safe;
use Illuminate\Http\Request;
use App\Models\admin\Expense;
use App\Exports\ExpencesExport;
use App\Http\Traits\Message_Trait;
use App\Http\Controllers\Controller;
use App\Models\admin\ExpenceCategory;
use App\Models\admin\SafeTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ExpencesController extends Controller
{
    use Message_Trait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = Expense::orderBy('id','DESC')->paginate(10);
        return view('admin.expenses.index',compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $safes = Safe::active()->get();
        $categories = ExpenceCategory::where('status',1)->get();
        return view('admin.expenses.create',compact('safes','categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $rules = [
            'category_id'=>'required|exists:expence_categories,id',
            'price'=>'required|min:1',
            'safe_id'=>'required|exists:safes,id',
            'description'=>'nullable|string',
        ];
        $messages = [
            'category_id.required'=>'التصنيف مطلوب',
            'category_id.exists'=>'التصنيف غير موجود',
            'price.required'=>'السعر مطلوب',
            'price.min'=>'السعر يجب ان يكون اكبر من 1',
            'safe_id.required'=>'الخزينة مطلوبة',
            'safe_id.exists'=>'الخزينة غير موجودة',
        ];
        $validator = Validator::make($data,$rules,$messages);
        if($validator->fails()){
            return Redirect()->back()->withErrors($validator)->withInput();
        }
        $safe = Safe::findOrFail($data['safe_id']);
        $oldSafeBalance =  $safe->balance;
        $newSafeBalance = $oldSafeBalance - $data['price'];
        if($newSafeBalance < 0){
            return Redirect()->back()->withErrors(['الرصيد غير كافٍ لاتمام العملية'])->withInput();
        }
        DB::beginTransaction();
        $expense = new Expense();
        $expense->category_id = $data['category_id'];
        $expense->price = $data['price'];
        $expense->safe_id = $data['safe_id'];
        $expense->description = $data['description'];
        $expense->save();
        ############################################# Start Add Transaction To Safe ############################
        $safeTransaction = new SafeTransaction();
        $safeTransaction->safe_id = $data['safe_id'];
        $safeTransaction->expense_category_id = $data['category_id'];
        $safeTransaction->amount = $data['price'];
        $safeTransaction->type = 'withdraw';
        $safeTransaction->description = ' دفعة من المصروف [ ' . $expense->category->name . ' ]' . ' من المصروف رقم :  ' . $expense->id;
        $safeTransaction->save();
        ############################################ End Add Transaction To Safe ###############################
        ################## Update Safe Balance #########
        $safe = Safe::findOrFail($data['safe_id']);
        $oldSafeBalance =  $safe->balance;
        $newSafeBalance = $oldSafeBalance - $data['price'];
        $safe->update([
            'balance' => $newSafeBalance,
        ]);
        DB::commit();
        ################ End Update Safe Balance ########
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
        $categories = ExpenceCategory::where('status',1)->get();
        return view('admin.expenses.edit',compact('expense','safes','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $rules = [
            'category_id'=>'required|exists:expence_categories,id',
            'price'=>'required|min:1',
            'safe_id'=>'required|exists:safes,id',
            'description'=>'nullable|string',
        ];
        $messages = [
            'category_id.required'=>'التصنيف مطلوب',
            'category_id.exists'=>'التصنيف غير موجود',
            'price.required'=>'السعر مطلوب',
            'price.min'=>'السعر يجب ان يكون اكبر من 1',
            'safe_id.required'=>'الخزينة مطلوبة',
            'safe_id.exists'=>'الخزينة غير موجودة',
        ];
        $validator = Validator::make($data,$rules,$messages);
        if($validator->fails()){
            return Redirect()->back()->withErrors($validator);
        }

        $expense = Expense::findOrFail($id);
        $expense->category_id = $data['category_id'];
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

           ########################################### Generate Expenses Pdf ##########################################
           public function ExpensesPdf(){
            $expenses = Expense::orderBy('id','DESC')->get();
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
                <h1>تقرير عن  التصنيفات  </h1>

                <table>
                    <thead>
                        <tr>
                            <th> التصنيف   </th>
                            <th> المبلغ </th>
                            <th> الخزينة  </th>
                            <th> تاريخ الانشاء </th>
                        </tr>
                    </thead>
                    <tbody>';

            // تعبئة البيانات داخل الجدول
            foreach ($expenses as $expense) {
                $html .= '
                        <tr>
                            <td>' . $expense->category->name . '</td>
                            <td>' . number_format($expense->price, 2) . ' دينار</td>
                            <td>' . $expense->safe->name . '</td>
                            <td>' . $expense->created_at . '</td>
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
            return $mpdf->Output('تقرير عن المصروفات.pdf', 'I'); // 'I' لعرض الملف في المتصفح

        }

        ######################################### Generate Clients Excel ############################

        public function ExpencesExcel(){
            return (new ExpencesExport())->download('Expences.xlsx');
        }
}
