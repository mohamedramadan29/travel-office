<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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
            'question.*' => ['required', 'string', 'max:255'],
            'answer.*' => ['required', 'string', 'max:5000'],
        ];
    }
    public function messages()
    {
        return [
            'question.*.required' => 'السؤال مطلوب',
            'question.*.string' => 'السؤال يجب أن يكون نص',
            'question.*.max' => 'السؤال يجب أن يكون أقل من 255 حرف',
            'answer.*.required' => 'الاجابة مطلوبة',
            'answer.*.string' => 'الاجابة يجب أن يكون نص',
            'answer.*.max' => 'الاجابة يجب أن يكون أقل من 5000 حرف',
        ];
    }
}
