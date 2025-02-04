<?php
namespace App\Http\Services\Dashboard;

use App\Http\Repositories\Dashboard\AdminRepository;
use App\Models\admin\Admin;

class AdminService
{

    protected $adminsRepository;
    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminsRepository = $adminRepository;
    }
    //////////// Get Admins /////////////

    public function getAdmins()
    {
        $admins = $this->adminsRepository->getAdmins();
        return $admins;
    }
    /////////////// Get Admin ////////////

    public function getAdmin($id)
    {
        $admin = $this->adminsRepository->getAdmin($id);
        if (!$admin) {
            return abort(404);
        }
        return $admin;
    }

    ////////////// Store admin //////////

    public function storeAdmin($request)
    {
        $admin = $this->adminsRepository->storeAdmin($request);
        if (!$admin) {
            return false;
        }
        return $admin;
    }

    //////////////// Update Admin ////////////
    public function updateAdmin($request, $id)
    {
        $admin = $this->adminsRepository->getAdmin($id);
        if (!$admin) {
            return abort(404);
        }
        if($request->password == null){
           unset($request->password);
        }
        $admin = $this->adminsRepository->updateAdmin($request, $admin);
        if (!$admin) {
            return false;
        }
        return $admin;
    }

    ////////// Delete Admin //////////////
    public function destroyAdmin($id)
    {
        $admin = $this->adminsRepository->getAdmin($id);
        if (!$admin) {
            return abort(404);
        }
        $admin = $this->adminsRepository->destroyAdmin($admin);
        if (!$admin) {
            return false;
        }
        return $admin;
    }

    ///////////// Change Status /////////////////
    public function changeStatus($id)
    {
        $admin = $this->adminsRepository->getAdmin($id);
        if (!$admin) {
            return abort(404);
        }
        if ($admin->status == 'active') {
            $status = 0;
        } else {
            $status = 1;
        }
        $status = $this->adminsRepository->changeStatus($admin, $status);
        if (!$status) {
            return false;
        }
        return $status;
    }

}
