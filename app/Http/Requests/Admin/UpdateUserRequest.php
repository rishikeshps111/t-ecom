<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        return [
            // 'user_name' => [
            //     'nullable',
            //     'string',
            //     'max:255',
            //     Rule::unique('users', 'user_name')->ignore($this->business_user),
            // ],
            'user_code'  => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'user_code')->ignore($this->business_user),
            ],
            'password' => 'nullable|string|min:8',

            'company_id'   => 'nullable|array|min:1',
            'company_id.*' => 'exists:companies,id',
            'name' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->business_user),
            ],
            'alternate_phone' => 'nullable|string|min:8|max:15',
            'whats_app' => 'nullable|string|min:8|max:15',
            'billing_address' => 'nullable|string|max:1000',
            // 'description' => 'nullable|string|max:1000',
            'country' => 'nullable|in:india,malaysia',
            'state_id' => 'nullable|exists:states,id',
            'city_id' => 'nullable|exists:locations,id',
            // 'gst' => 'nullable|string|max:20',
            // 'tax_id' => 'nullable|string|max:20',
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

            'gst.required_if' => 'GST/VAT number is required for customers in India.',

            'user_name.required' => 'User name is required.',
            'user_name.unique' => 'This user name is already taken.',

            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }
}
