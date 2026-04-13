<?php

namespace App\Http\Requests\Admin;

use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
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
            'invoice_id'      => 'required|exists:invoices,id',
            // 'status'          => 'required|in:closed,pending',
            'amount'          => 'required|numeric|min:1',
            'payment_method'  => 'required|string',
            'remark'          => 'nullable|string|max:500',
            'notes'          => 'nullable|string|max:500',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $invoiceId = $this->input('invoice_id');
            $amount    = $this->input('amount');

            $invoice = Invoice::find($invoiceId);

            if (!$invoice) {
                return;
            }

            $balanceAmount = $invoice->balance_amount;

            if ($amount > $balanceAmount) {
                $validator->errors()->add(
                    'amount',
                    'Payment amount cannot exceed the invoice balance amount.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'invoice_id.required' => 'Invoice is required.',
            'invoice_id.exists'   => 'Selected invoice does not exist.',

            'status.required'     => 'Payment status is required.',
            'status.in'           => 'Payment status must be either Pending or Closed.',

            'amount.required'     => 'Payment amount is required.',
            'amount.numeric'      => 'Payment amount must be a valid number.',
            'amount.min'          => 'Payment amount must be at least 1.',

            'payment_method.required' => 'Payment method is required.',

            'remark.string'       => 'Remarks must be valid text.',
            'remark.max'          => 'Remarks cannot exceed 500 characters.',
        ];
    }
}
