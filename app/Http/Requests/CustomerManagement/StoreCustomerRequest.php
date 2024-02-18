<?php

namespace App\Http\Requests\CustomerManagement;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            // 'customer_type_id' => 'required|integer',
            // 'customer_name' => 'required|string',
            // 'customer_email' => ['nullable', 'regex:/(.+)@(.+)\.(.+)/i','unique:customers,customer_email'],
            'customer_email' => ['nullable','unique:customers,customer_email'],
            // 'customer_gender' => 'required|string',
            // 'customer_picture' => 'nullable|string',
            // 'customer_dob' => 'required|date_format:Y-m-d',
            // 'customer_address' => 'required|string',
            'customer_phone' => 'unique:customers,customer_phone',
            // 'customer_identity' => 'required|string',
            // 'isdiscount' => 'numeric|gte:0',
            // 'customer_created_by' => 'integer',
            // 'customer_updated_by' => 'integer',
        ];
    }
}
