<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeFormRequest extends FormRequest
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
        $rules = [
            'first_name' =>[
                'required',
                'string',
                'max:200'
            ],
            'last_name' =>[
                'required',
                'string',
                'max:200'
            ],
            'email' =>[
                'required',
                'string',
                'email'
            ],
            'password' =>[
                'required',
            ],
            'location' =>[
                'required',
                'string'
            ],
        ];
        return $rules;
    }
}
