<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRentalResaleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'property_type' => 'sometimes|string|max:255',
            'title' => 'sometimes|string|max:255',
            'subtitle' => 'sometimes|string|max:255',
            'top' => 'nullable|boolean',
            'bathroom' => 'sometimes|integer',
            'bedroom' => 'sometimes|integer',
            'sq_ft' => 'sometimes|numeric',
            'garage' => 'sometimes|integer',
            'description' => 'sometimes|string',
            'details' => 'sometimes|array',
            'details.*.title' => 'sometimes|string',
            'details.*.info' => 'sometimes|string',
            'amenities' => 'sometimes|array',
            'amenities.*.amenity' => 'sometimes|string',
            'agent_title' => 'sometimes|string|max:255',
            'agent_status' => 'sometimes|string|max:255',
            'agent_languages' => 'sometimes|string|max:255',
            'agent_call' => 'sometimes|string|max:255',
            'agent_whatsapp' => 'sometimes|string|max:255',
            'agent_photo' => 'sometimes|array',
            'agent_photo.*' => 'sometimes|image',
            'location_link' => 'sometimes|string|max:255',
            'qr_photo' => 'sometimes|image',
            'reference' => 'sometimes|string|max:255',
            'dld_permit_number' => 'sometimes|string|max:255',
            'addresses' => 'sometimes|array',
            'addresses.*.address' => 'sometimes|string',
            'amount' => 'sometimes|numeric',
            'amount_dirhams' => 'sometimes|numeric',
            'gallery_images' => 'sometimes|array',
            'gallery_images.*' => 'sometimes|image',
            'tags' => 'sometimes|array',
            'languages' => 'sometimes|array',
            'languages.*.languages' => 'sometimes|string',
            'alt_texts' => 'sometimes|array',
            'alt_texts.*' => 'sometimes|string|max:255',
             // Metadata validation
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
}
