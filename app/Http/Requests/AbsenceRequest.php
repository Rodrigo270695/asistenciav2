<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AbsenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'reason_id' => ['required', 'integer', 'exists:reasons,id'],
            'file' => ['nullable', 'mimes:jpg,jpeg,png,doc,docx,pdf', 'max:3072'],
            'status' => ['required'],
            'status_detail' => ['nullable', 'string'],
            'worker_id' => ['required', 'integer', 'exists:workers,id'],
        ];
    }
}
