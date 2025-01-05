<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Services\Auth\Dashboard\RoleService;
use App\Http\Traits\Message_Trait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RolesController extends Controller
{
    use Message_Trait;
    protected $roleService;
    public function __construct(RoleService $roleService)
    {

        $this->roleService = $roleService;
    }
    public function index()
    {
        $roles = $this->roleService->getRoles();
        return view('admin.roles.index', compact('roles'));
    }
    public function create()
    {
        return view('admin.roles.create');
    }
    public function store(RoleRequest $request)
    {
        $role = $this->roleService->createRole($request);
        if (!$role) {
            return back()->with('error', ' لم يتم انشاء الصلاحية بنجاح  ');
        }
        return $this->success_message('تمت الاضافة بنجاح ');
        // return redirect()->back()->with('success', ' تم انشاء الصلاحية بنجاح ');
    }
    public function edit($id)
    {
        $role = $this->roleService->getRole($id);
        if (!$role) {
            return Redirect::back()->with('error', ' لم يتم العثور على الصلاحية ');
        }
        return view('admin.roles.edit', compact('role'));
    }
    public function update(RoleRequest $request, $id)
    {
        $role = $this->roleService->updaterole($request, $id);
        if (!$role) {
            return back()->with('error', ' لم يتم تعديل الصلاحية بنجاح  ');
        }
        return $this->success_message('تم التعديل بنجاح ');
    }

    public function destroy($id){

        $role = $this->roleService->destroyRole($id);
        if (!$role) {
            return back()->with('error', ' لم يتم حذف الصلاحية بنجاح  ');
        }
        return $this->success_message('تم الحذف بنجاح ');
    }
}
