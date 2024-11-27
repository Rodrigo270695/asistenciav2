<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PdvRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'unit' => ['required', 'in:Distribuidora,Franquicia,DAM'],
            'spot' => [
                'required',
                'string',
                'max:30',
                'min:1',
                Rule::unique('pdvs')->where(function ($query) {
                    return $query->where('unit', $this->unit)
                        ->where('zonal_id', $this->zonal_id);
                })
            ],
            'zonal_id' => ['required', 'exists:zonals,id'],
            'address' => ['nullable', 'string', 'max:255'],
        ];
    }
}
