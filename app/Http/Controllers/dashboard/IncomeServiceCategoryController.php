<?php

namespace App\Http\Controllers\dashboard;

use Mpdf\Mpdf;
use Illuminate\Http\Request;
use App\Http\Traits\Message_Trait;
use App\Http\Controllers\Controller;
use App\Models\admin\IncomServiceCategory;
use App\Models\admin\IncomeServiceCategory;
use App\Exports\IncomeServiceCategoriesExport;

class IncomeServiceCategoryController extends Controller
{
    use Message_Trait;

    public function index(){
        $categories = IncomServiceCategory::orderBy('id','DESC')->paginate(10);
        return view('admin.income-service-categories.index',compact('categories'));
    }
    public function create(){
        return view('admin.income-service-categories.create');
    }
    public function store(Request $request){
        $data = $request->all();
        IncomServiceCategory::create($data);
        return $this->success_message(' تم اضافة التصنيف بنجاح  ');
    }
    public function edit($id){
        $category = IncomServiceCategory::findOrFail($id);
        return view('admin.income-service-categories.update',compact('category'));
    }
    public function update(Request $request,$id){
        $category = IncomServiceCategory::findOrFail($id);
        $data = $request->all();
        $category->update($data);
        return $this->success_message(' تم تعديل التصنيف بنجاح ');
    }
    public function destroy($id){
        $category = IncomServiceCategory::findOrFail($id);
        $category->delete();
        return $this->success_message(' تم حذف التصنيف بنجاح ');
    }

        ########################################### Generate Clients Pdf ##########################################
        public function IncomeServiceCategoriesPdf(){
            $categories = IncomServiceCategory::orderBy('id','DESC')->paginate(10);
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
                <h4>   تقرير عن  تصنيفات الايرادات الخارجية    </h4>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th> الاسم </th>
                            <th> الحالة  </th>
                            <th> تاريخ الانشاء </th>
                        </tr>
                    </thead>
                    <tbody>';

            // تعبئة البيانات داخل الجدول
            foreach ($categories as $category) {
                $html .= '
                        <tr>
                            <td>' . $category->name . '</td>
                            <td>' . $category->status . '</td>
                            <td>' . $category->created_at . '</td>
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
            return $mpdf->Output('تقرير عن التصنيفات.pdf', 'I'); // 'I' لعرض الملف في المتصفح

        }

        ######################################### Generate Clients Excel ############################

        public function IncomeServiceCategoriesExcel(){
            return (new IncomeServiceCategoriesExport())->download('IncomeServiceCategories.xlsx');
        }


}
