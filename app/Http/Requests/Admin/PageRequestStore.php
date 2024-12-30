<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PageRequestStore extends FormRequest
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
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'keywords' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'parent_id' => 'nullable|exists:pages,id',
            'type_id' => 'required', // Assuming you have a PageType model
            'sort' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ];
    }
    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'The title is required.',
            'slug.required' => 'The slug is required.',
            'slug.unique' => 'The slug must be unique.',
            'type_id.required' => 'The page type is required.',
            'type_id.exists' => 'The selected page type is invalid.',
            // Add more custom messages as needed
        ];
    }
}