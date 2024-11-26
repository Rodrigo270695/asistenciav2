<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class HoraryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:25', 'min:3', Rule::unique('horaries', 'name')->ignore($this->horary)],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

}
