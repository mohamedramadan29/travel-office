<?php

namespace App\Http\Repositories\Auth;

use App\Models\admin\Admin;
use App\Notifications\SendOtpNotify;
use Ichtrojan\Otp\Otp;

class PasswordRepository
{

    protected $otp;
    public function __construct()
    {
        $this->otp = new Otp();
    }
    public function getuseremail($email)
    {
        $admin = Admin::where('email', $email)->first();
        return $admin;
    }
    public function otpverify($email, $code)
    {
        $otp = $this->otp->validate($email, $code);
        return $otp;
    }

    public function resetpassword($email,$password){
        $admin = self::getuseremail($email);
        $admin->password = bcrypt($password);
        $admin->save();
        return $admin;
    }


}
