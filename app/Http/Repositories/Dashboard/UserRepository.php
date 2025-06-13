<?php
namespace App\Http\Repositories\Dashboard;

use App\Models\User;

class UserRepository
{
    public function getAll()
    {
        return User::orderBy('id','desc')->get();
    }

    public function store($data){
        $user =  User::create($data);
        return $user;
    }
}
