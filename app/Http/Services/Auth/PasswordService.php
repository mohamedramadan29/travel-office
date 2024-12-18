<?php
namespace App\Http\Services\Auth;

use App\Notifications\SendOtpNotify;
use App\Http\Repositories\Auth\PasswordRepository;

class PasswordService
{
    protected $passwordRepository;
    public function __construct(PasswordRepository $passwordRepository)
    {
        $this->passwordRepository = $passwordRepository;
    }

    public function sendotp($email)
    {
        $admin = $this->passwordRepository->getuseremail($email);
        if (!$admin) {
            return false;
        }
        $admin->notify(new SendOtpNotify());
        return $admin;
    }
    public function otpverify($email, $otp){
        $otp = $this->passwordRepository->otpverify($email,$otp);
        return $otp->status;
    }

    public function resetpassword($email,$password){
        return $this->passwordRepository->resetpassword($email,$password);
    }
}
