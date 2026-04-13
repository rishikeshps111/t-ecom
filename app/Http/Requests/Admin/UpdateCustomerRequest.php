<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
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
        $customerId = $this->customer?->id;
        return [
            // 'user_id' => 'required|exists:users,id',
            'company_id' => 'nullable|exists:companies,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'customer_name' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('customers', 'email')->ignore($customerId),
            ],
            'phone' => 'nullable|string|min:8|max:15',
            'alternate_phone' => 'nullable|string|min:8|max:15',
            // 'billing_address' => 'required|string|max:1000',
            // 'shipping_address' => 'nullable|string|max:1000',
            'country' => 'nullable|in:india,malaysia',
            'state_id' => 'nullable|exists:states,id',
            'city_id' => 'nullable|exists:locations,id',
            'gst' => 'nullable|string|max:20',
            'tss' => 'nullable|string|max:200',
            'banner' => 'nullable|string|max:200',
            'tax_id' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Please select a company user.',
            'user_id.exists' => 'Selected user is invalid.',
            'customer_name.required' => 'Customer name is required.',

            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already associated with another customer.',

            'phone.required' => 'Phone number is required.',
            'phone.min' => 'Phone number must be at least 8 digits.',

            'billing_address.required' => 'Billing address is required.',

            'country.required' => 'Please select a country.',
            'country.in' => 'Selected country is invalid.',

            'state_id.required' => 'Please select a state.',
            'state_id.exists' => 'Selected state is invalid.',

            'city_id.required' => 'Please select a city.',
            'city_id.exists' => 'Selected city is invalid.',

            'gst.required_if' => 'TIN is required for customers in India.',
        ];
    }
}
