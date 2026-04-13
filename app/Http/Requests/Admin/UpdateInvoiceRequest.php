<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
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
            'invoice_number'  => 'required|unique:invoices,invoice_number,' . $this->invoice->id,
            // 'customer_id'       => 'required|exists:users,id',
            // 'company_id'        => 'required|exists:companies,id',
            'quotation_id'        => 'required|exists:quotations,id',
            // 'company_type_id'   => 'required|exists:company_types,id',
            // 'business_user_id'  => 'required|exists:users,id',
            // 'total_group_id'   => 'required|exists:customers,id',
            // 'currency_id'      => 'required|exists:currencies,id',
            'invoice_date'    => 'required|date',
            'due_date'     => 'required|date|after_or_equal:invoice_date',
            'terms'            => 'nullable|string',
            'remarks'          => 'nullable|string',
            // 'payment_terms'     => 'required|in:Net 7,Net 15,Net 30',
            // 'currency'          => 'required|in:INR,MYR,USD',

            'items'             => 'required|array|min:1',
            'items.*.item_id'   => 'required|exists:items,id',
            'items.*.description'  => 'nullable|string',
            'items.*.umo'  => 'nullable|string',
            'items.*.quantity'  => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_percentage' => 'required|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'customer_id.required'       => 'Please select a customer.',
            'customer_id.exists'         => 'The selected customer does not exist.',

            'company_id.required'        => 'Please select a company.',
            'company_id.exists'          => 'The selected company does not exist.',

            'invoice_date.required'    => 'Please select the invoice date.',
            'invoice_date.date'        => 'The invoice date must be a valid date.',

            'due_date.required'     => 'Please select the expiry date.',
            'due_date.date'         => 'The expiry date must be a valid date.',
            'due_date.after_or_equal' => 'The expiry date must be a date after or equal to the invoice date.',

            'payment_terms.required'    => 'Please select a payment term.',
            'payment_terms.in'     => 'Please select a valid payment term.',

            'currency.required'    => 'Please select a currency.',
            'currency.in'          => 'Please select a valid currency.',

            'items.required'             => 'Please add at least one item.',
            'items.array'                => 'Items must be a valid array.',
            'items.min'                  => 'Please add at least one item.',

            'items.*.item_id.required'   => 'Please select an item.',
            'items.*.item_id.exists'     => 'The selected item does not exist.',

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
        ];
    }
}
