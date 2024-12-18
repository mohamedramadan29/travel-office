<?php

namespace App\Http\Controllers\dashboard\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Services\Auth\PasswordService;
use App\Models\admin\Admin;
use App\Notifications\SendOtpNotify;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;

class ForgetPasswordController extends Controller
{
    protected $otp;
    protected $passwordService;
    public function __construct(PasswordService $passwordService)
    {
        $this->passwordService = $passwordService;
        $this->otp = new Otp;
    }
    public function showemailform()
    {
        return view("admin.auth.password.email");
    }

    public function sendotp(ForgetPasswordRequest $request)
    {
        try {
            $admin = $this->passwordService->sendotp($request->email);
            if (!$admin) {
                return redirect()->back()->withErrors(['email' => __('passwords.email_is_not_register')]);
            }
            return redirect()->route('dashboard.password.otp.show', ['email' => $admin->email]);
        } catch (\Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function showotpform($email)
    {
        return view('admin.auth.password.verify', ['email' => $email]);
    }

    public function otpverify(ForgetPasswordRequest $request)
    {

        $otp = $this->passwordService->otpverify($request->email,$request->code);
        if (!$otp) {
            return back()->withErrors(['error' => ' Code Not Correct  ']);
        }
        return redirect()->route('dashboard.password.reset', ['email' => $request->email]);
    }
}
