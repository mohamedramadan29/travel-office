<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:255|min:3|unique:coupons,code,' . $this->id,
            'discount_percentage' => 'required|numeric|between:1,100',
            'start_date' => 'required|date|after_or_equal:now',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'required|boolean',
            'limit' => 'required|integer|min:1',
        ];
    }
    public function messages()
    {
        return [
            'code.required' => 'الكود مطلوب',
            'code.string' => 'الكود يجب أن يكون سلسلة',
            'code.max' => 'الكود يجب أن يكون أقل من 255 حرف',
            'code.min' => 'الكود يجب أن يكون أطول من 3 حروف',
            'code.unique' => 'الكود مستخدم من قبل',
            'discount_percentage.required' => 'النسبة المئوية مطلوبة',
            'discount_percentage.numeric' => 'النسبة المئوية يجب أن يكون رقم',
            'discount_percentage.between' => 'النسبة المئوية يجب أن تكون بين 1 و 100',
            'start_date.required' => 'التاريخ البداية مطلوب',
            'start_date.date' => 'التاريخ البداية يجب أن يكون تاريخ',
            'start_date.after_or_equal' => 'التاريخ البداية يجب أن يكون بعد اليوم',
            'end_date.required' => 'التاريخ النهاية مطلوب',
            'end_date.date' => 'التاريخ النهاية يجب أن يكون تاريخ',
            'end_date.after' => 'التاريخ النهاية يجب أن يكون بعد التاريخ البداية',
            'is_active.required' => 'الحالة مطلوبة',
            'is_active.boolean' => 'الحالة يجب أن يكون إما 1 أو 0',
            'limit.required' => 'الحد الأقصى للاستخدام مطلوب',
            'limit.integer' => 'الحد الأقصى للاستخدام يجب أن يكون رقم',
            'limit.min' => 'الحد الأقصى للاستخدام يجب أن يكون أكبر من 0',

        ];
    }
}
