<?php

namespace App\Http\Controllers\dashboard\auth;

use App\Http\Services\Auth\AuthService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateAuthAdminRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class AuthController extends Controller implements HasMiddleware
{

    protected $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        #### If Use Laravel UI ####
        $this->middleware('guest:admin')->except('logout');
    }



    // public static function middleware()
    // {
    //     return [
    //         new Middleware(middleware: 'guest:admin', except: ['logout']),
    //     ];
    // }

    public function show_login()
    {
        return view("admin.auth.login");
    }
    public function register_login(CreateAuthAdminRequest $request)
    {
        if ($request->isMethod("POST")) {
            $data = $request->all();
            $credentials = $request->only("email", "password");
            if ($this->authService->login($credentials, 'admin', $request->remeber)) {
                // return redirect()->route('dashboard.welcome')->with('success','تم تسجيل الدخول بنجاح ');
                ////// intebded = > بيرجعك علي اخر حاجة كنت شغال عليها بعد التسجيل مرة اخري
                return redirect()->intended(route('dashboard.welcome'));
            }
            // return redirect()->route('dashboard.login.show')->withErrors([]'error','لا يوجد حساب بهذة البيانات ');
            return redirect()->back()->withErrors(['email' => 'لا يوجد حساب بهذة البيانات ']);
        }
    }

    public function logout()
    {
        //Auth::guard('admin')->logout();
        $this->authService->logout('admin');
        return redirect()->route('dashboard.login.show');
    }
}
