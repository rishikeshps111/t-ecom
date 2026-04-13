<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
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
            // 'type'          => 'required|in:general,planner',
            'company_id'    => 'nullable|required_if:type,general|exists:companies,id',
            // 'project_id'    => 'nullable|required_if:type,planner|exists:projects,id',
            'financial_year_id'    => 'required|exists:financial_years,id',
            'company_type_id' => 'nullable|exists:company_types,id',
            'business_user_id' => 'nullable|exists:users,id',
            'total_group_id' => 'nullable|exists:customers,id',
            'title'         => 'required|string|max:255',
            'document_type' => 'required|in:pdf,word,image,power_point,excel',
            'document' => 'required|file|max:10240',
            // 'valid_from'    => 'nullable|date',
            // 'valid_to'      => 'nullable|date|after_or_equal:valid_from',
            'status'        => 'required|in:active,inactive,expired',
        ];
    }
}
