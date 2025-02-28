<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UpdateSectionRequest extends FormRequest
{
    public function authorize()
    {
        // Add debugging
        Log::info('UpdateSectionRequest being validated', [
            'method' => $this->method(),
            'all' => $this->all(),
            'route' => $this->route()->getName(),
        ]);

        return true;
    }

    public function rules()
    {
        return [
            'fields' => 'required|array',
            'fields.title' => 'required|string|max:255',
            'fields.subtitle' => 'nullable|string|max:255',
            'fields.slider_images' => 'nullable|array',
            'fields.slider_images.*' => 'nullable|string',
            'description' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'fields.required' => 'The fields are required.',
            'fields.title.required' => 'The title field is required.',
        ];
    }
}
