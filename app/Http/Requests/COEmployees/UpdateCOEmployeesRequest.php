<?php

namespace App\Http\Requests\COEmployees;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCOEmployeesRequest extends FormRequest
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
            'phone' => 'required|digits_between:9,10|unique:c_o_employees,phone,' . $this->id,
            'address' => 'string',
            'identity' => 'string',
            'picture' => 'string',
            'status' => 'integer',
        ];
    }
}
