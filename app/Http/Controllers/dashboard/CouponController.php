<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Traits\Message_Trait;
use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Http\Services\Dashboard\CouponService;

class CouponController extends Controller
{
    use Message_Trait;
    protected $couponService;
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }
    public function index()
    {
        return view('admin.coupons.index');
    }
    public function CouponsAll()
    {
        return $this->couponService->getCoupons();
    }
    public function create()
    {
        return view('admin.coupons.create');
    }
    public function store(CouponRequest $request)
    {
        $data = $request->only(['code', 'discount_percentage', 'start_date', 'end_date', 'is_active', 'limit', 'is_used']);
        $coupon = $this->couponService->createCoupon($data);
        if (!$coupon) {
            return $this->error_message('حدث خطأ ما أثناء إنشاء الكوبون');
        }
        return $this->success_message('تم إنشاء الكوبون بنجاح');
    }

    public function edit(string $id)
    {
        $coupon = $this->couponService->getCoupon($id);
        return view('admin.coupons.edit', compact('coupon'));
    }
    public function update(CouponRequest $request, string $id)
    {
        $data = $request->only(['code', 'discount_percentage', 'start_date', 'end_date', 'is_active', 'limit', 'is_used', 'id']);
        $coupon = $this->couponService->updateCoupon($id, $data);
        if (!$coupon) {
            return $this->error_message('حدث خطأ ما أثناء تعديل الكوبون');
        }
        return $this->success_message('تم تعديل الكوبون بنجاح');
    }
    public function destroy(string $id)
    {
        if (!$this->couponService->deleteCoupon($id)) {
            return $this->error_message('حدث خطأ ما أثناء حذف الكوبون');
        }
        return $this->success_message('تم حذف الكوبون بنجاح');
    }
}
