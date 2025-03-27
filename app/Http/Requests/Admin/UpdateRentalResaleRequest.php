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
            'amenities' => 'sometimes|array',
            'agent_title' => 'sometimes|string|max:255',
            'agent_status' => 'sometimes|string|max:255',
            'agent_languages' => 'sometimes|string|max:255',
            'agent_call' => 'sometimes|string|max:255',
            'agent_whatsapp' => 'sometimes|string|max:255',
            'agent_photo' => 'sometimes|image',
            'location_link' => 'sometimes|string|max:255',
            'qr_photo' => 'sometimes|image',
            'reference' => 'sometimes|string|max:255',
            'dld_permit_number' => 'sometimes|string|max:255',
            'addresses' => 'sometimes|array',
            'amount' => 'sometimes|numeric',
            'amount_dirhams' => 'sometimes|numeric',
            'gallery_images' => 'sometimes|array',
            'tags' => 'sometimes|array',
            'languages' => 'sometimes|array',
        ];
    }
}
