<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
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
            'company_id' => ['nullable', 'exists:companies,id'],
            'user_type' => ['required', 'in:customer,planner,production_staff'],
            'user_id' => ['required', 'array', 'min:1'],
            'user_id.*' => [
                'required',
                function ($attribute, $value, $fail) {
                    $allowedGroups = ['group_all', 'group_planners', 'group_production'];

                    if (in_array($value, $allowedGroups, true)) {
                        return;
                    }

                    if (!User::whereKey($value)->exists()) {
                        $fail('The selected Cus User is invalid.');
                    }
                }
            ],
            // 'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            // 'priority' => ['required', 'in:low,high,medium'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_type.required' => 'Please select a User Type.',
            'user_type.in' => 'The selected User Type is invalid.',
            'user_id.required' => 'Please select a Cus User.',
            'user_id.array' => 'Please select at least one Cus User.',
            'user_id.min' => 'Please select at least one Cus User.',
            // 'company_id.required' => 'Please select a recipient.',


            // 'subject.required' => 'The subject field is required.',
            // 'subject.max' => 'Subject cannot exceed 255 characters.',

            // 'message.required' => 'The message field is required.',
        ];
    }
}
