<?php
namespace App\Http\Services\Auth\Dashboard;

use App\Http\Repositories\Dashboard\RoleRepository;

class RoleService
{

    protected $roleRepository;
    public function __construct(RoleRepository $roleRepository)
    {

        $this->roleRepository = $roleRepository;
    }
    public function createRole($request)
    {
        $role = $this->roleRepository->createRole($request);
        return $role;
    }

    ############# get Roles ############

    public function getRoles()
    {
        $roles = $this->roleRepository->getRoles();
        return $roles;
    }

    ############# get Role ############
    public function getRole($id)
    {
        $role = $this->roleRepository->getRole($id);
        return $role;
    }

    public function updaterole($request, $id)
    {
        $role = $this->roleRepository->getRole($id);
        if (!$role) {
            return false;
        }
        $role = $this->roleRepository->updaterole($request, $role);
        return $role;
    }

    public function destroyRole($id)
    {

        //  $role = $this->roleRepository->destroyRole($id);
        $role = $this->getRole($id);
        if (!$role || $role->admins()->count() > 0) {
            return false;
        }
        return $this->roleRepository->destroyRole($id);
    }
}
