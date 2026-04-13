<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyMessageRequest extends FormRequest
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
            'company_id' => ['required', 'exists:companies,id'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'priority' => ['required', 'in:low,high,medium'],

        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required' => 'Please select a recipient.',

            'subject.required' => 'The subject field is required.',
            'subject.max' => 'Subject cannot exceed 255 characters.',

            'message.required' => 'The message field is required.',
        ];
    }
}
