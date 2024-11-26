<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DetailhoraryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'week' => [
                'array',
                function ($attribute, $value, $fail) {
                    if ($this->isMethod('post')) {
                        foreach ($value as $day) {
                            $exists = DB::table('detailhoraries')
                                ->where('horary_id', $this->route('horary')->id)
                                ->where('week', $day)
                                ->exists();

                            if ($exists) {
                                $fail("El día $day ya está registrado para este horario.");
                            }
                        }
                    }
                },
            ],
            'week.*' => 'in:Lunes,Martes,Miercoles,Jueves,Viernes,Sabado,Domingo',
            'hi' => 'required',
            'rs' => 'nullable|after:hi|before:ri',
            'ri' => 'nullable|after:rs|before:hs',
            'hs' => 'required|after:hi',
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('week', 'required', function ($input) {
            return $this->isMethod('post');
        });

        $validator->sometimes('rs', 'required_with:ri', function ($input) {
            return !is_null($input->ri);
        });

        $validator->sometimes('ri', 'required_with:rs', function ($input) {
            return !is_null($input->rs);
        });
    }
}
