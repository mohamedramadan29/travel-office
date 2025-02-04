<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Http\Services\Auth\Dashboard\RoleService;
use App\Http\Services\Dashboard\AdminService;
use App\Http\Traits\Message_Trait;
use App\Models\admin\Role;
use Illuminate\Http\Request;

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
}
