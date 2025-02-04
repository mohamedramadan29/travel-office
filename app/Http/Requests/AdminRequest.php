<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        $data =  [
            'name' => ['required', 'min:2', 'max:60'],
            'email' => ['required', 'email', 'min:2', 'max:100', Rule::unique('admins', 'email')->ignore($this->id)],
            'role_id' => ['required', 'exists:roles,id'],
            'status' => ['required', 'in:1,0']
        ];
        if(in_array($this->method(), ['PUT', 'PATCH'])) {
            $data['password'] = ['nullable', 'min:8', 'max:150', 'confirmed'];
            $data['password_confirmation'] = ['nullable'];
        }else{
            $data['password'] = ['required', 'min:8', 'max:150', 'confirmed'];
            $data['password_confirmation'] = ['required'];
        }

        return $data;

    }
}
