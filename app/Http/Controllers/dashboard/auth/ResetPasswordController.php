<?php

namespace App\Http\Controllers\dashboard\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Services\Auth\PasswordService;
use App\Models\admin\Admin;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    protected $passwordServices;
    public function __construct(PasswordService $passwordService)
    {
        $this->passwordServices = $passwordService;
    }
    public function ShowResetForm($email)
    {
        return view('admin.auth.password.reset')->with("email", $email);
    }
    public function resetpassword(ResetPasswordRequest $request)
    {
        $admin = $this->passwordServices->resetpassword($request->email, $request->password);
        if (!$admin) {
            return redirect()->back()->with('error', 'لا يوجد تسجيل بهذة البيانات');
        }
        return redirect()->route('dashboard.login.show')->with('success', 'تم تعديل كلمة المرور بنجاح ');
    }
}
