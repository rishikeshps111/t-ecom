<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuotationRequest extends FormRequest
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
            'quotation_number'  => 'required|string|max:255|unique:quotations,quotation_number',
            'company_type_id'  => 'nullable|exists:company_types,id',
            'business_user_id'  => 'nullable|exists:users,id',
            'planner_user_id'  => 'nullable|exists:users,id',
            'invoice_address'  => 'nullable|string|max:1000',
            'delivery_address'  => 'nullable|string|max:1000',
            // 'customer_id'       => 'required|exists:users,id',
            'company_id'        => 'nullable|exists:companies,id',
            'currency_id'        => 'required|exists:currencies,id',

            // 'contact_person'    => 'required|string|max:255',
            'quotation_date'    => 'required|date',
            'validity_date'     => 'required|date|after_or_equal:quotation_date',
            // 'validity_in_days'     => 'required|numeric',
            'payment_terms'     => 'nullable|string|max:500',
            'remarks'     => 'nullable|string|max:500',
            'planner_id' => 'required|exists:users,id',
            'production_staff_id' => 'required|exists:users,id',

            // 'notes'             => 'required|string|max:1000',
            // 'terms'             => 'required|string|max:1000',

            'items'             => 'required|array|min:1',
            'items.*.item_id'   => 'required|exists:items,id',
            'items.*.quantity'  => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_percentage' => 'required|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'items.*.description' => 'required|string|max:1000',
            'items.*.umo' => 'nullable|string|max:255',

            'attachments'       => 'nullable|array',
            'attachments.*.file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'attachments.*.alt' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'customer_id.required'       => 'Please select a customer.',
            'customer_id.exists'         => 'The selected customer does not exist.',

            'company_id.required'        => 'Please select a company.',
            'company_id.exists'          => 'The selected company does not exist.',

            'company_type_id.required'        => 'Please select a company type.',
            'company_type_id.exists'          => 'The selected company type does not exist.',

            'business_user_id.required'        => 'Please select a corp user.',
            'business_user_id.exists'          => 'The selected corp user does not exist.',

            'contact_person.required'    => 'Please enter the contact person name.',
            'contact_person.string'      => 'The contact person name must be a valid string.',
            'contact_person.max'         => 'The contact person name may not exceed 255 characters.',

            'quotation_date.required'    => 'Please select the quotation date.',
            'quotation_date.date'        => 'The quotation date must be a valid date.',

            'validity_date.required'     => 'Please select the Expiry date.',
            'validity_date.date'         => 'The Expiry date must be a valid date.',
            'validity_date.after_or_equal' => 'The Expiry date must be a date after or equal to the quotation date.',

            'validity_in_days.required'       => 'Please enter the validity in days.',
            'validity_in_days.numeric'          => 'validity in days must be a number.',

            'payment_terms.required'     => 'Please enter the payment terms.',
            'payment_terms.string'       => 'Payment terms must be a valid string.',
            'payment_terms.max'          => 'Payment terms may not exceed 500 characters.',

            'notes.required'     => 'Please enter the Notes.',
            'notes.string'               => 'Notes must be a valid string.',
            'notes.max'                  => 'Notes may not exceed 1000 characters.',

            'terms.required'     => 'Please enter the terms.',
            'terms.string'               => 'Terms must be a valid string.',
            'terms.max'                  => 'Terms may not exceed 1000 characters.',

            'items.required'             => 'Please add at least one item.',
            'items.array'                => 'Items must be a valid array.',
            'items.min'                  => 'Please add at least one item.',

            'items.*.item_id.required'   => 'Please select an item.',
            'items.*.item_id.exists'     => 'The selected item does not exist.',

            'items.*.description.required'   => 'Please enter description for each item.',
            'items.*.description.string'     => 'Description must be a valid string.',
            'items.*.description.max'        => 'Description may not exceed 1000 characters.',

            'items.*.umo.required'   => 'Please enter UMO for each item.',
            'items.*.umo.string'     => 'UMO must be a valid string.',
            'items.*.umo.max'        => 'UMO may not exceed 255 characters.',

            'items.*.quantity.required'  => 'Please enter quantity for each item.',
            'items.*.quantity.numeric'   => 'Quantity must be a number.',
            'items.*.quantity.min'       => 'Quantity must be at least 1.',

            'items.*.unit_price.required' => 'Please enter unit price for each item.',
            'items.*.unit_price.numeric'  => 'Unit price must be a number.',
            'items.*.unit_price.min'      => 'Unit price cannot be negative.',

            'items.*.tax_percentage.required' => 'Please enter Tax percentage for each item.',
            'items.*.tax_percentage.numeric' => 'Tax percentage must be a number.',
            'items.*.tax_percentage.min'     => 'Tax percentage cannot be negative.',

            'items.*.discount_amount.numeric' => 'Discount amount must be a number.',
            'items.*.discount_amount.min'     => 'Discount amount cannot be negative.',

            'attachments.array'          => 'Attachments must be a valid array.',
            'attachments.*.file.file'    => 'Each attachment must be a valid file.',
            'attachments.*.file.mimes'   => 'Attachments must be a file of type: pdf, jpg, jpeg, png.',
            'attachments.*.file.max'     => 'Each attachment may not exceed 2MB in size.',
            'attachments.*.alt.string'   => 'Attachment alt text must be a valid string.',
            'attachments.*.alt.max'      => 'Attachment alt text may not exceed 255 characters.',
        ];
    }
}
