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
            'user_address' =>[
                'required',
                'string'
            ],
            'phone_no' => [
                'required',
                'string',
              'regex:/^(\+?\d{1,4}[\s-]?)?(\(?\d{1,5}\)?[\s-]?)?\d{1,5}[\s-]?\d{1,5}[\s-]?\d{1,5}$/',

            ],
            'identity_card' => [
                'file',
                'mimes:jpeg,png,pdf',
                'max:2048', // Maximum file size in kilobytes
            ],
            'police_clearance' => [
                'file',
                'mimes:jpeg,png,pdf',
                'max:2048',
            ],
            'gramasevaka_certificate' => [
                'file',
                'mimes:jpeg,png,pdf',
                'max:2048',
            ],
            'driver_license' => [
                'file',
                'mimes:jpeg,png,pdf',
                'max:2048',
            ],
            'vehicle_insurance' => [
                'file',
                'mimes:jpeg,png,pdf',
                'max:2048',
            ],
            'passport' => [
                'file',
                'mimes:jpeg,png,pdf',
                'max:2048',
            ],
        ];
        return $rules;
    }
}
