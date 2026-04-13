<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePlannerDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Add policy later if needed
    }

    public function rules(): array
    {
        return [
            // 'company_id' => ['required', 'exists:companies,id'],
            'financial_year_id'    => 'required|exists:financial_years,id',
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
            'company_type_id' => ['required', 'exists:company_types,id'],
            'planner_id' => ['required', 'exists:users,id'],
            'total_group_id' => ['required', 'exists:customers,id'],
            // 'start_date' => ['required', 'date'],
            // 'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            // 'description' => ['nullable', 'string'],

            // Multiple files
            'documents' => ['required', 'array'],
            'documents.*' => [
                'file',
                'mimes:jpg,jpeg,png,pdf,doc,docx',
                'max:5120', // 5MB per file
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required' => 'Company is required.',
            'company_id.exists' => 'Selected company is invalid.',

            'title.required' => 'Title is required.',

            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status selected.',

            'start_date.required' => 'Start date is required.',
            'end_date.required' => 'End date is required.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',

            'documents.*.mimes' => 'Allowed file types: JPG, PNG, PDF, DOC, DOCX.',
            'documents.*.max' => 'Each file must not exceed 5MB.',
        ];
    }
}
