<?php

namespace App\Http\Controllers\dashboard;

use Mpdf\Mpdf;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;
use App\Http\Traits\Message_Trait;
use App\Http\Controllers\Controller;
use App\Models\admin\ExpenceCategory;
use App\Exports\ExpencesCategoriesExport;

class ExpencesCategoriesController extends Controller
{


    use Message_Trait;

    public function index(){
        $categories = ExpenceCategory::orderBy('id','DESC')->paginate(10);
        return view('admin.expenses-categories.index',compact('categories'));
    }
    public function create(){
        return view('admin.expenses-categories.create');
    }
    public function store(Request $request){
        $data = $request->all();
        ExpenceCategory::create($data);
        return $this->success_message(' تم اضافة التصنيف بنجاح  ');
    }
    public function edit($id){
        $category = ExpenceCategory::findOrFail($id);
        return view('admin.expenses-categories.update',compact('category'));
    }
    public function update(Request $request,$id){
        $category = ExpenceCategory::findOrFail($id);
        $data = $request->all();
        $category->update($data);
        return $this->success_message(' تم تعديل التصنيف بنجاح ');
    }
    public function destroy($id){
        $category = ExpenceCategory::findOrFail($id);
        $category->delete();
        return $this->success_message(' تم حذف التصنيف بنجاح ');
    }

        ########################################### Generate Clients Pdf ##########################################
        public function ExpencesCategoriesPdf(){
            $categories = ExpenceCategory::orderBy('id','DESC')->paginate(10);
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

        public function ExpencesCategoriesExcel(){
            return (new ExpencesCategoriesExport())->download('ExpencesCategories.xlsx');
        }

}
