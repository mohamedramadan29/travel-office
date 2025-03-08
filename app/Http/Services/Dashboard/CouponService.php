<?php
namespace App\Http\Services\Dashboard;

use Yajra\DataTables\Facades\DataTables;
use App\Http\Repositories\Dashboard\CouponRepository;


class CouponService
{
    protected $couponRepository;
    public function __construct(CouponRepository $couponRepository)
    {
        $this->couponRepository = $couponRepository;
    }
    public function getCoupon($id)
    {
        $coupon = $this->couponRepository->getCoupon($id);
        if (!$coupon) {
            abort(404, ' هذا الكوبون غير موجود ');
        }
        return $coupon;
    }
    public function getCoupons()
    {
        $coupons = $this->couponRepository->getCoupons();
        return DataTables::of($coupons)
            ->addIndexColumn()
            ->addColumn('action', function ($coupon) {
                return view('admin.coupons.datatables.actions', compact('coupon'));
            })
            ->make(true);
    }
    public function createCoupon($data)
    {
        return $this->couponRepository->createCoupon($data);
    }
    public function updateCoupon($id, $data)
    {
        $coupon = self::getCoupon($id);
        return $this->couponRepository->updateCoupon($coupon, $data);
    }
    public function deleteCoupon($id)
    {
        $coupon = self::getCoupon($id);
        return $this->couponRepository->deleteCoupon($coupon);
    }


}
