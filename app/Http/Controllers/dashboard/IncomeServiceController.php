<?php

namespace App\Http\Controllers\dashboard;

use Mpdf\Mpdf;
use App\Models\admin\Safe;
use Illuminate\Http\Request;
use App\Http\Traits\Message_Trait;
use App\Models\admin\IncomService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Exports\IncomeServicesExport;
use App\Models\admin\SafeTransaction;
use Illuminate\Support\Facades\Validator;
use App\Models\admin\IncomServiceCategory;

class IncomeServiceController extends Controller
{
    use Message_Trait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incomes = IncomService::orderBy('id','DESC')->paginate(10);
        return view('admin.income-services.index',compact('incomes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $safes = Safe::active()->get();
        $categories = IncomServiceCategory::where('status',1)->get();
        return view('admin.income-services.create',compact('safes','categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $rules = [
            'category_id'=>'required',
            'price'=>'required|min:1',
            'safe_id'=>'required|exists:safes,id',
            'description'=>'nullable|string',
        ];
        $messages = [
            'category_id.required'=>'التصنيف مطلوب',
            'price.required'=>'السعر مطلوب',
            'price.min'=>'السعر يجب ان يكون اكبر من 1',
            'safe_id.required'=>'الخزينة مطلوبة',
            'safe_id.exists'=>'الخزينة غير موجودة',
        ];
        $validator = Validator::make($data,$rules,$messages);
        if($validator->fails()){
            return Redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        $income = new IncomService();
        $income->category_id = $data['category_id'];
        $income->price = $data['price'];
        $income->safe_id = $data['safe_id'];
        $income->description = $data['description'];
        $income->save();
        ############################################# Start Add Transaction To Safe ############################
        $safeTransaction = new SafeTransaction();
        $safeTransaction->safe_id = $data['safe_id'];
        $safeTransaction->income_service_category_id = $data['category_id'];
        $safeTransaction->amount = $data['price'];
        $safeTransaction->type = 'deposit';
        $safeTransaction->description = '  اضاف رصيد من ايراد [ ' . $income->category->name . ' ]' . ' من ايراد رقم :  ' . $income->id;
        $safeTransaction->save();
        ############################################ End Add Transaction To Safe ###############################
        ################## Update Safe Balance #########
        $safe = Safe::findOrFail($data['safe_id']);
        $oldSafeBalance =  $safe->balance;
        $newSafeBalance = $oldSafeBalance + $data['price'];
        $safe->update([
            'balance' => $newSafeBalance,
        ]);
        DB::commit();
        ################ End Update Safe Balance ########
        return $this->success_message('تم اضافة ايراد بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $income = IncomService::findOrFail($id);
        return view('admin.income-services.show',compact('income'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $income = IncomService::findOrFail($id);
        $safes = Safe::active()->get();
        $categories = IncomServiceCategory::where('status',1)->get();
        return view('admin.income-services.edit',compact('income','safes','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $rules = [
            'category_id'=>'required',
            'price'=>'required|min:1',
            'safe_id'=>'required|exists:safes,id',
            'description'=>'nullable|string',
        ];
        $messages = [
            'category_id.required'=>'التصنيف مطلوب',
            'price.required'=>'السعر مطلوب',
            'price.min'=>'السعر يجب ان يكون اكبر من 1',
            'safe_id.required'=>'الخزينة مطلوبة',
            'safe_id.exists'=>'الخزينة غير موجودة',
        ];
        $validator = Validator::make($data,$rules,$messages);
        if($validator->fails()){
            return Redirect()->back()->withErrors($validator);
        }

        $income = IncomService::findOrFail($id);
        $income->category_id = $data['category_id'];
        $income->price = $data['price'];
        $income->safe_id = $data['safe_id'];
        $income->description = $data['description'];
        $income->save();
        return $this->success_message('تم تحديث الايراد بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $income = IncomService::findOrFail($id);
        $income->delete();
        return $this->success_message('تم حذف الايراد بنجاح');
    }

           ########################################### Generate Income Services Pdf ##########################################
           public function IncomeServicesPdf(){
            $incomes = IncomService::orderBy('id','DESC')->get();
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
                <h1>تقرير عن  الايرادات  </h1>

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
            foreach ($incomes as $income) {
                $html .= '
                        <tr>
                            <td>' . $income->category->name . '</td>
                            <td>' . number_format($income->price, 2) . ' دينار</td>
                            <td>' . $income->safe->name . '</td>
                            <td>' . $income->created_at . '</td>
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
            return $mpdf->Output('تقرير عن الايرادات.pdf', 'I'); // 'I' لعرض الملف في المتصفح

        }

        ######################################### Generate Income Services Excel ############################

        public function IncomeServicesExcel(){
            return (new IncomeServicesExport())->download('IncomeServices.xlsx');
        }


        public function PrintIncomeServices($id){
            $income = IncomService::findOrFail($id);

            return view('admin.income-services.print',compact('income'));

        }
}
