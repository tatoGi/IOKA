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
            'desc' => 'nullable|string',
            'parent_id' => 'nullable|exists:pages,id',
            'type_id' => 'required', // Assuming you have a PageType model
            'sort' => 'nullable|integer',
            'active' => 'nullable|boolean',

            // Metadata validation rules (similar to BlogPostController)
            'metadata.meta_title' => 'nullable|string|max:255',
            'metadata.meta_description' => 'nullable|string',
            'metadata.meta_keywords' => 'nullable|string',
            'metadata.og_title' => 'nullable|string|max:255',
            'metadata.og_description' => 'nullable|string',
            'metadata.og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'metadata.twitter_card' => 'nullable|string|in:summary,summary_large_image',
            'metadata.twitter_title' => 'nullable|string|max:255',
            'metadata.twitter_description' => 'nullable|string',
            'metadata.twitter_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'type_id.required' => 'The page type is required.',
            'metadata.og_image.image' => 'The OG image must be an image file.',
            'metadata.og_image.mimes' => 'The OG image must be a file of type: jpeg, png, jpg, gif.',
            'metadata.og_image.max' => 'The OG image may not be greater than 2MB.',
            'metadata.twitter_image.image' => 'The Twitter image must be an image file.',
            'metadata.twitter_image.mimes' => 'The Twitter image must be a file of type: jpeg, png, jpg, gif.',
            'metadata.twitter_image.max' => 'The Twitter image may not be greater than 2MB.',
        ];
    }
}
