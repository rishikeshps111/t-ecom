<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkPlanRequest extends FormRequest
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
            'workplan_number' => 'required|string|max:50',
            'date' => 'required|date|after_or_equal:today',
            'company_id' => 'required|exists:companies,id',
            'company_type_id' => 'required|exists:company_types,id',
            'total_group_id' => 'required|exists:customers,id',
            'planner_id' => 'nullable|exists:users,id',
            'production_staff_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
            // 'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif|max:5120', // 5MB max
            // 'file_type' => 'nullable|in:image,word,pdf,excel,power_point',
        ];
    }
}
