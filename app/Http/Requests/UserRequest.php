<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::check();
    }


    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50', 'min:5'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user)],
            'role' => ['required', 'exists:roles,name'],
            'pdv_id' => ['required', 'exists:pdvs,id'],
        ];
    }
}
