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
            'slug' => 'required|string|max:255|unique:rental_resale,slug',
            'top' => 'nullable|boolean',
            'bathroom' => 'required|integer',
            'bedroom' => 'required|integer',
            'sq_ft' => 'required|numeric',
            'garage' => 'required|integer',
            'description' => 'required|string',
            'details' => 'required|array',
            'amenities' => 'required|array',
            'agent_title' => 'required|string|max:255',
            'agent_status' => 'required|string|max:255',
            'agent_languages' => 'required|string|max:255',
            'agent_call' => 'required|string|max:255',
            'agent_whatsapp' => 'required|string|max:255',
            'location_link' => 'required|string|max:255',
            'qr_photo' => 'required|image',
            'reference' => 'required|string|max:255',
            'dld_permit_number' => 'required|string|max:255',
            'addresses' => 'required|array',
            'amount' => 'required|numeric',
            'amount_dirhams' => 'required|numeric',
            'gallery_images' => 'required|array',
            'tags' => 'required|array',
            'languages' => 'required|array',
            'agent_photo' => 'required|image',

        ];
    }
}
