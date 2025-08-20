<?php

namespace App\Http\Controllers\dashboard;

use Mpdf\Mpdf;
use App\Models\admin\Admin;
use Illuminate\Http\Request;
use App\Http\Traits\Message_Trait;
use App\Exports\EmploeSalaryExport;
use App\Http\Controllers\Controller;
use App\Models\admin\EmployeeSalary;
use Illuminate\Support\Facades\Validator;

class EmployeeSalaryController extends Controller
{
    use Message_Trait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salaries = EmployeeSalary::with('employee')->orderBy('id','Desc')->paginate(10);
        return view('admin.admins.salary.index',compact('salaries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Admin::where('status',1)->get();
        return view('admin.admins.salary.create',compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $rules = [
            'admin_id' => 'required|exists:admins,id',
            'salary' => 'required|numeric',
            'status' => 'required|in:0,1',
        ];
        $messages = [
            'admin_id.required' => 'الموظف مطلوب',
            'admin_id.exists' => 'الموظف غير موجود',
            'salary.required' => 'الراتب مطلوب',
            'salary.numeric' => 'الراتب يجب ان يكون رقم',
            'status.required' => 'الحالة مطلوبة',
            'status.in' => 'الحالة يجب ان تكون 0 أو 1',
        ];
        $validator = Validator::make($data,$rules,$messages);
        if($validator->fails()){
            return $this->error_message($validator->errors()->first());
        }
        $salary = new EmployeeSalary();
        $salary->admin_id = $data['admin_id'];
        $salary->salary = $data['salary'];
        $salary->status = $data['status'];
        $salary->save();
        return $this->success_message(' تم اضافة الراتب بنجاح  ');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $salary = EmployeeSalary::findOrFail($id);
        $employees = Admin::where('status',1)->get();
        return view('admin.admins.salary.edit',compact('salary','employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $rules = [
            'admin_id' => 'required|exists:admins,id',
            'salary' => 'required|numeric',
            'status' => 'required|in:0,1',
        ];
        $messages = [
            'admin_id.required' => 'الموظف مطلوب',
            'admin_id.exists' => 'الموظف غير موجود',
            'salary.required' => 'الراتب مطلوب',
            'salary.numeric' => 'الراتب يجب ان يكون رقم',
            'status.required' => 'الحالة مطلوبة',
            'status.in' => 'الحالة يجب ان تكون 0 أو 1',
        ];
        $validator = Validator::make($data,$rules,$messages);
        if($validator->fails()){
            return $this->error_message($validator->errors()->first());
        }
        $salary = EmployeeSalary::findOrFail($id);
        $salary->admin_id = $data['admin_id'];
        $salary->salary = $data['salary'];
        $salary->status = $data['status'];
        $salary->save();
        return $this->success_message(' تم تحديث الراتب بنجاح  ');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $salary = EmployeeSalary::findOrFail($id);
        $salary->delete();
        return $this->success_message(' تم حذف الراتب بنجاح  ');
    }

    public function EmployeeSalaryPdf(){
        $salaries = EmployeeSalary::latest()->get();
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
            <h4>تقرير عن رواتب الموظفين  </h4>
        </div>
            <table>
                <thead>
                    <tr>
                        <th> الاسم </th>
                        <th> الراتب </th>
                        <th> الحالة </th>
                        <th> تاريخ الانشاء </th>
                    </tr>
                </thead>
                <tbody>';

        // تعبئة البيانات داخل الجدول
        foreach ($salaries as $salary) {
            $status = $salary->status == 1 ? 'مفعل' : 'غير مفعل';
            $html .= '
                    <tr>
                        <td>' . $salary->employee->name . '</td>
                        <td>' . number_format($salary->salary, 2) .  ' دينار  </td>
                        <td>' . $status . '</td>
                        <td>' . $salary->created_at->format('Y-m-d') . '</td>
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
        return $mpdf->Output('تقرير عن رواتب الموظفين.pdf', 'I'); // 'I' لعرض الملف في المتصفح

    }
    public function EmployeeSalaryExcel(){
        return (new EmploeSalaryExport())->download('EmployeeSalary.xlsx');
    }
}
