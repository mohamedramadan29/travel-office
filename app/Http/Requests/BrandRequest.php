<?php

namespace App\Http\Requests;

use CodeZero\UniqueTranslation\UniqueTranslationRule;
use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
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
    public function rules()
    {
        $rules = [

            "name.*" => ['required', 'string', 'max:100', UniqueTranslationRule::for('brands')->ignore($this->id)],

            'status' => ['required', 'integer', 'in:0,1'],
        ];
        if ($this->method() == 'PUT') {
            $rules['logo'] = ['nullable', 'image', 'max:2048'];
        } else {
            $rules['logo'] = ['required', 'image', 'max:2048'];
        }
        return $rules;
    }
}
