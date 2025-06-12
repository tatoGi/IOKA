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
        $rules = [
            'fields' => 'required|array',
            'fields.title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];

        // Add flexible rules for common fields
        $optionalFields = [
            'subtitle', 'description', 'title_2', 'number', 'number_suffix', 'url', 'alt_text'
        ];

        foreach ($optionalFields as $field) {
            $rules["fields.{$field}"] = 'nullable|string|max:255';
        }

        // Add flexible rules for repeater fields
        $repeaterFields = [
            'phone_numbers', 'email_addresses', 'locations', 'slider_images',
            'rolling_numbers', 'properties', 'features', 'amenities'
        ];

        foreach ($repeaterFields as $field) {
            $rules["fields.{$field}"] = 'nullable|array';
            $rules["fields.{$field}.*"] = 'nullable|array';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'fields.required' => 'The fields are required.',
            'fields.title.required' => 'The title field is required.',
        ];
    }
}
