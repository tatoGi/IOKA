<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreOffplanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'amount' => ['required', 'regex:/^[0-9\s.,\-+\/\$]*$/'],
            'amount_dirhams' => ['nullable', 'regex:/^[0-9\s.,\-+\/\$]*$/'],
            'description' => 'required',
            // Array inputs
            'features.*' => 'nullable|string',
            'near_by.*.title' => 'nullable|string',
            'near_by.*.distance' => 'nullable|numeric',
            'amenities' => 'nullable',
            'map_location' => 'nullable|string',
            // File uploads
            'main_photo' => 'nullable|file|image|max:5120', // 5MB max
            'banner_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner_title' => 'nullable|string|max:255',
            'exterior_gallery.*' => 'nullable|file|image|max:5120',
            'interior_gallery.*' => 'nullable|file|image|max:5120',
            'property_type' => 'nullable|string|max:50',
            'bathroom' => 'nullable|integer',
            'bedroom' => 'nullable|integer',
            'garage' => 'nullable|integer',
            'sq_ft' => 'nullable|integer',
            'qr_title' => 'nullable|string|max:255',
            'qr_photo' => 'nullable|file|image|max:5120',
            'qr_text' => 'nullable',
            'download_brochure' => 'nullable|string',
            'agent_title' => 'nullable|string|max:255',
            'agent_status' => 'nullable|string|max:50',
            'agent_image' => 'nullable|file|image|max:5120',
            'agent_telephone' => 'nullable|string|max:20',
            'agent_whatsapp' => 'nullable|string|max:20',
            'agent_linkedin' => 'nullable|string|max:255',
            'agent_email' => 'nullable|email|max:255',
            'location' => 'nullable|string|max:255',
            'location_id' => 'nullable|exists:locations,id',
            'agent_languages' => 'nullable|array',
            'agent_languages.*' => 'string',
            // Alt text validation rules
            'main_photo_alt' => 'nullable|string|max:255',
            'banner_photo_alt' => 'nullable|string|max:255',
            'exterior_gallery_alt.*' => 'nullable|string|max:255',
            'interior_gallery_alt.*' => 'nullable|string|max:255',
            'qr_photo_alt' => 'nullable|string|max:255',
            'agent_image_alt' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'features.*' => 'feature',
            'near_by.*.title' => 'nearby location title',
            'near_by.*.distance' => 'nearby location distance',
            'exterior_gallery.*' => 'exterior gallery image',
            'interior_gallery.*' => 'interior gallery image',
        ];
    }
}
