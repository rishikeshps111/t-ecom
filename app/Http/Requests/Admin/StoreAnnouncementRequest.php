<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnnouncementRequest extends FormRequest
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
            'priority'   => 'required|in:low,medium,high',
            'type'       => 'required|in:public,private',
            'subject'    => 'required|string|max:255',
            'message'    => 'required|string',
            'schedule_date'    => 'required|date',

            // only required if type = private
            'user_type' => 'nullable|required_if:type,private|in:planner,customer',
            'user_id'    => 'required_if:type,private|array',
            'user_id.*'  => 'exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'priority.required' => 'Priority is required.',
            'priority.in'       => 'Invalid priority selected.',

            'type.required'     => 'Type is required.',
            'type.in'           => 'Invalid type selected.',

            'subject.required'  => 'Subject is required.',
            'subject.max'       => 'Subject cannot exceed 255 characters.',

            'message.required'  => 'Message is required.',

            'user_id.required_if' => 'Please select at least one customer for private announcement.',
            'user_id.*.exists'    => 'Selected customer is invalid.',
        ];
    }
}
