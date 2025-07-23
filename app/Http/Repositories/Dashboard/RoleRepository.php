<?php
namespace App\Http\Repositories\Dashboard;

use App\Models\admin\Role;


class RoleRepository
{
    public function createRole($request)
    {
        $data = $request->all();
        $role = new Role();
        $role->create([
            'role' => $data['role'],
            'permission' => json_encode($data['permissions'])
        ]);
        return $role;
    }
    public function getRoles(){
        $roles = Role:: paginate(10);
        return $roles;
    }

    public function getRole($id){
        $role = Role::find($id);
        return $role;
    }

    public function updaterole($request,$role){
        $data = $request->all();
        $role->update([
            'role' => $data['role'],
            'permission' => json_encode($data['permissions'])
        ]);
        return $role;
    }
    public function destroyRole($id){
        $role = $this->getRole($id);
        $role->delete();
        return $role;
    }
}
