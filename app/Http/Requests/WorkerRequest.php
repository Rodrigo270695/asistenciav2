<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class WorkerRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:30', 'min:3'],
            'lastname' => ['required', 'string', 'max:30', 'min:3'],
            'document_type' => ['required', Rule::in(['DNI','CARNET DE EXTRANJERIA','OTROS'])],
            'num_document' => ['required', Rule::unique('workers')->ignore($this->worker), function ($attribute, $value, $fail) {
                if ($this->document_type == 'DNI' && (strlen($value) != 8 || !is_numeric($value))) {
                    $fail('El número de documento debe tener 8 dígitos y ser numérico.');
                }
            }],
            'entry_date' => ['required', 'date'],
            'exit_date' => ['nullable', 'date', 'after_or_equal:entry_date'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:18 years ago'],
            'phone' => ['nullable', 'string', 'max:9', 'min:9', 'regex:/^9[0-9]{8}$/'],
            'address' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('workers')->ignore($this->worker)],
            'charge_id' => ['required', 'exists:charges,id'],
            'pdv_id' => ['required', 'exists:pdvs,id'],
        ];
    }
}
