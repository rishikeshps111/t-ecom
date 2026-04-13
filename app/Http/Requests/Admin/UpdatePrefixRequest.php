<?php

namespace App\Http\Requests\Admin;

use App\Models\Prefix;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePrefixRequest extends FormRequest
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
        $prefixId = Prefix::where('module', $this->module)->value('id');
        return [
            'module' => [
                'required',
                'string',
                'max:50',
                Rule::unique('prefixes', 'module')->ignore($prefixId), // ignore current record
            ],
            'prefix' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'module.required' => 'The module field is required.',
            'module.string' => 'The module must be a valid string.',
            'module.max' => 'The module cannot exceed 50 characters.',
            'module.unique' => 'This module already exists. Please choose a different one.',

            'prefix.required' => 'The prefix field is required.',
            'prefix.string' => 'The prefix must be a valid string.',
            'prefix.max' => 'The prefix cannot exceed 255 characters.',
        ];
    }
}
