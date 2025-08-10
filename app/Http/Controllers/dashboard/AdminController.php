<?php

namespace App\Http\Controllers\dashboard;

use Mpdf\Mpdf;
use App\Models\admin\Role;
use App\Models\admin\Admin;
use Illuminate\Http\Request;
use App\Exports\AdminsExport;
use App\Http\Traits\Message_Trait;
use App\Http\Requests\AdminRequest;
use App\Http\Controllers\Controller;
use App\Http\Services\Dashboard\AdminService;
use App\Http\Services\Auth\Dashboard\RoleService;

class AdminController extends Controller
{
    use Message_Trait;
    protected $adminService, $roleService;
    public function __construct(AdminService $adminService, RoleService $roleService)
    {
        $this->adminService = $adminService;
        $this->roleService = $roleService;
    }
    public function index()
    {
        $admins = $this->adminService->getAdmins();
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        $roles = $this->roleService->getRoles();

        return view('admin.admins.create', compact('roles'));
    }

    public function store(AdminRequest $request)
    {
        $data = $request->all();

        $admin = $this->adminService->storeAdmin($data);
        if (!$admin) {
            return $this->Error_message(' لم يتم التخزين بنجاح ، الرجاء المحاولة مرة اخرى ');
        }

        return $this->Success_message('تم التخزين بنجاح');
    }

    public function show(string $id)
    {
        if (!$admin = $this->adminService->getAdmin($id)) {
            return abort(404);
        }
        return view('admin.admins.show', compact('admin'));
    }

    public function edit(string $id)
    {
        $roles = $this->roleService->getRoles();
        if (!$admin = $this->adminService->getAdmin($id)) {
            return abort(404);
        }
        return view('admin.admins.edit', data: compact('admin','roles'));
    }

    public function update(AdminRequest $request, string $id)
    {
        $data = $request->all();
        $admin = $this->adminService->updateAdmin($request, $id);
        if (!$admin) {
            return $this->Error_message(' لم يتم التخزين بنجاح ، الرجاء المحاولة مرة اخرى ');
        }
        return $this->Success_message('تم التخزين بنجاح');
    }

    public function ChangeStatus($id){

        $admin = $this->adminService->changeStatus($id);
        if (!$admin) {
            return $this->Error_message(' لم يتم تغير الحالة  ');
        }
        return $this->Success_message('تم تغير الحالة  بنجاح');
    }
    public function destroy(string $id)
    {
        $admin = $this->adminService->destroyAdmin($id);
        if (!$admin) {
            return $this->Error_message(' لم يتم الحذف بنجاح ، الرجاء المحاولة مرة اخرى ');
        }
        return $this->Success_message('تم الحذف بنجاح');
    }

    ########################################### Generate Admins Pdf ##########################################
    public function AdminsPdf(){
        $admins = Admin::latest()->get();
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
            <h1>تقرير عن العملاء </h1>

            <table>
                <thead>
                    <tr>
                        <th> الاسم </th>
                        <th> البريد الالكتروني </th>
                        <th> الصلاحيات </th>
                        <th> الحالة </th>
                        <th> تاريخ الانشاء </th>
                    </tr>
                </thead>
                <tbody>';

        // تعبئة البيانات داخل الجدول
        foreach ($admins as $admin) {
            $html .= '
                    <tr>
                        <td>' . $admin->name . '</td>
                        <td>' . $admin->email . '</td>
                        <td>' . $admin->role->role . '</td>
                        <td>' . $admin->status . '</td>
                        <td>' . $admin->created_at->format('Y-m-d') . '</td>
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

    ######################################### Generate Clients Excel ############################

    public function AdminsExcel(){
        return (new AdminsExport())->download('Admins.xlsx');
    }
}
