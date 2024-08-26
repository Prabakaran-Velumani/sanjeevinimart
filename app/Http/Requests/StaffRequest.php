<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Use the request instance to check for conditions
        $passwordRule = $this->has('password') ? 'nullable|min:8' : 'required|min:8';

        return [
            "employee_id" => "required_if:role_type,!=,admin",
            "first_name" => "required",
            "last_name" => "nullable",
            "phone" => "nullable",
            "email" => "nullable",
            "password" => $passwordRule,
            "role_id" => "required",
            "date_of_birth" => "required|date|date_format:m/d/Y",
            "address" => "nullable|max:200",
            "bank_name" => "nullable",
            "bank_branch_name" => "nullable",
            "bank_account_name" => "nullable",
            "bank_account_number" => "nullable",
            "date_of_joining" => "required|date|date_format:m/d/Y",
            "leave_applicable_date" => "required|date|date_format:m/d/Y",
            'photo' => 'nullable|mimes:jpeg,jpg,png',
        ];
    }
}
