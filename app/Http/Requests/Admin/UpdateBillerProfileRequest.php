<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBillerProfileRequest extends FormRequest
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
            // 'company_id' => [
            //     'required',
            //     Rule::unique('biller_profiles', 'company_id')
            //         ->ignore($this->route('biller_profile')->id),
            // ],
            'total_group_id'   => ['required', 'exists:customers,id', Rule::unique('biller_profiles', 'total_group_id')
                ->ignore($this->route('biller_profile')->id)],
            'address'   => ['required', 'string'],
            'invoice_header'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'invoice_footer'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'quotation_header' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'quotation_footer' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'receipt_header'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'receipt_footer'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'receipt_tc'   => ['nullable', 'string'],
            'quotation_tc'   => ['nullable', 'string'],
            'invoice_payment_terms'   => ['nullable', 'string'],
        ];
    }
}
