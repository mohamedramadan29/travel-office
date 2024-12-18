<?php
namespace App\Http\Services\Auth;

use App\Http\Repositories\Auth\AuthRepository;

class AuthService
{

    protected $authrepository;
    public function __construct(AuthRepository $authRepository)
    {
        $this->authrepository = $authRepository;
    }
    public function login($credentials,$guard, $remember)
    {
        return $this->authrepository->login( $credentials, $guard, $remember);
    }

    public function logout($guard){
        return $this->authrepository->logout($guard);
    }
}
