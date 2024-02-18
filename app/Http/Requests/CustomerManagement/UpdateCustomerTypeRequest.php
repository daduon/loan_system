<?php

namespace App\Http\Requests\CustomerManagement;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerTypeRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'customer_type_name' => 'required|not_in:General|not_in:general',
            'customer_type_rate' => 'required|numeric|gte:0',
            'customer_type_des' => 'nullable|string',
            'customer_type_updated_by' => 'integer',
        ];
    }
}
