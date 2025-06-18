<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RentalResaleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'property_type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'top' => 'nullable|boolean',
            'bathroom' => 'required|integer',
            'bedroom' => 'required|integer',
            'sq_ft' => 'required|numeric',
            'garage' => 'required|integer',
            'description' => 'required|string',
            'details' => 'required|array',
            'details.*.title' => 'required|string',
            'details.*.info' => 'required|string',
            'amenities' => 'required|array',
            'amenities.*.amenity' => 'required|string',
            'agent_title' => 'required|string|max:255',
            'agent_status' => 'required|string|max:255',
            'agent_call' => 'required|string|max:255',
            'agent_whatsapp' => 'required|string|max:255',
            'agent_email' => 'nullable|email|max:255',
            'location_link' => 'required|string|max:255',
            'qr_photo' => 'required|image',
            'reference' => 'required|string|max:255',
            'dld_permit_number' => 'required|string|max:255',
            'addresses' => 'required|array',
            'addresses.*.address' => 'required|string',
            'amount' => 'required|numeric',
            'amount_dirhams' => 'required|numeric',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image',
            'tags' => 'required|array',
            'languages' => 'nullable|array',
            'languages.*.languages' => 'required|string',
            'agent_photo' => 'required|array|min:1',
            'agent_photo.*' => 'image',
            'location_id' => 'nullable|array',
            'location_id.*' => 'exists:locations,id',
            'alt_texts' => 'nullable|array',
        ];
    }

    public function messages()
    {
        return [
            'agent_photo.required' => 'Please select at least one agent photo.',
            'agent_photo.*.image' => 'Each agent photo must be a valid image file.',
            'details.required' => 'Please add at least one detail.',
            'amenities.required' => 'Please add at least one amenity.',
            'addresses.required' => 'Please add at least one address.',
            'tags.required' => 'Please select at least one tag.',
        ];
    }
}
