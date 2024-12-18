<?php
namespace App\Http\Repositories\Auth;

use Illuminate\Support\Facades\Auth;

class AuthRepository
{
    public function login($credentials, $guard, $remeber)
    {
        return Auth::guard($guard)->attempt($credentials, $remeber);
    }

    public function logout($guard){
      return Auth::guard($guard)->logout();
    }
}
