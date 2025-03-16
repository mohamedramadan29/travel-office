<?php
namespace App\Http\Repositories\Dashboard;

use App\Models\admin\Coupon;


class CouponRepository
{

    public function getCoupon($id)
    {
        $coupon = Coupon::find($id);
        return $coupon;
    }

    public function getCoupons()
    {
        $coupons = Coupon::latest()->get();
        return $coupons;
    }

    public function createCoupon($data)
    {
        $coupon = Coupon::create($data);
        return $coupon;
    }

    public function updateCoupon($coupon, $data)
    {
        $coupon->update($data);
        return $coupon;
    }

    public function deleteCoupon($coupon)
    {
        $coupon->delete();
        return $coupon;
    }

}
