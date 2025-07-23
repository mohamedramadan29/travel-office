<?php
namespace App\Http\Repositories\Dashboard;

use App\Models\admin\Admin;

use function Symfony\Component\String\b;

class AdminRepository
{


    /////////////// Get All  Admins /////////////
    public function getAdmins()
    {
        $admins = Admin::paginate(10);
        return $admins;
    }

    /////////////// Get Admin ////////////
    public function getAdmin($id)
    {
        $admin = Admin::find($id);
        return $admin;
    }

    ////////////// Store Admin ////////////
    public function storeAdmin($request)
    {
        $admin = new Admin();
        $admin->name = $request['name'];
        $admin->email = $request['email'];
        $admin->password = bcrypt($request['password']); // تشفير كلمة المرور
        $admin->role_id = $request['role_id'];
        $admin->status = $request['status'];
        $admin->save();
        return $admin;
    }
    /////////////////// Update Admin ////////////
    public function updateAdmin($request, $admin)
    {
        $admin->update([
            'name' => $request->input('name'),
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
            'status' => $request->status,
        ]);
        return $admin;
    }
    //////////////// Destroy Admin ////////////
    public function destroyAdmin($admin)
    {
        return $admin->delete();
    }

    ////////////// Change Status ##################
    public function changeStatus($admin, $status)
    {
        $admin->update([
            'status' => $status,
        ]);
        return $admin;
    }
}
