<?php

namespace App\Http\Requests\COEmployees;

use Illuminate\Foundation\Http\FormRequest;

class StoreCOEmployeesRequest extends FormRequest
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
        return [
            'name' => 'required',
            'role' => 'required',
            'phone' => 'required|unique:c_o_employees,phone',
            'address' => 'string',
            'identity' => 'string',
            'picture' => 'string',
            'status' => 'integer',
        ];
    }
}
