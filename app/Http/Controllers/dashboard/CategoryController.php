<?php

namespace App\Http\Controllers\dashboard;

use Mpdf\Mpdf;
use Illuminate\Http\Request;
use App\Models\admin\Category;
use App\Http\Traits\Message_Trait;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Services\Dashboard\CategoriesService;
use App\Exports\CategoriesExport;

class CategoryController extends Controller
{
    use Message_Trait;

    protected $categoryService;
    public function __construct(CategoriesService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->getAll();

        return view('admin.categories.index', compact('categories'));
    }

    public function CategoryAll()
    {

        return $this->categoryService->getAll();
    }

    public function create()
    {
        return view('admin.categories.create');
    }


    public function store(CategoryRequest $request)
    {
        $data = $request->only(['name', 'status', 'parent']);
        if (!$this->categoryService->store($data)) {
            return $this->Error_message(' لم تتم العملية بنجاح  ');
        } else {
            return $this->success_message(' تمت الاضافة بنجاح  ');
        }

    }

    public function edit(string $id)
    {
        $category = $this->categoryService->findById($id);
        return view('admin.categories.update', compact('category'));
    }

    public function update(CategoryRequest $request, string $id)
    {
        $data = $request->only(['name', 'parent', 'status', 'id']);
        if (!$this->categoryService->update($data)) {
            return $this->Error_message(' لم تتم العملية بنجاح  ');
        } else {
            return $this->success_message(' تمت عملية التعديل بنجاح  ');
        }
    }


    public function destroy(string $id)
    {
        if (!$this->categoryService->delete($id)) {
            return $this->Error_message(' لم تتم عملية الحذف بنجاح  ');
        }
        return $this->success_message(' تمت عملية الحذف بنجاح  ');
    }


        ########################################### Generate Clients Pdf ##########################################
        public function CategoriesPdf(){
            $categories = $this->categoryService->getAll();
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

        public function CategoriesExcel(){
            return (new CategoriesExport())->download('Categories.xlsx');
        }


}
