<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offplan extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'slug',
        'amount',
        'amount_dirhams',
        'description',
        'features',
        'amenities',
        'map_location',
        'near_by',
        'main_photo',
        'banner_photo',
        'exterior_gallery',
        'interior_gallery',
        'property_type',
        'bathroom',
        'bedroom',
        'garage',
        'sq_ft',
        'qr_title',
        'qr_photo',
        'qr_text',
        'download_brochure',
        'agent_title',
        'agent_status',
        'agent_image',
        'agent_telephone',
        'agent_whatsapp',
        'agent_linkedin',
        'location',
        'developer_id',
    ];

    protected $casts = [
        'features' => 'array',
        'near_by' => 'array',
        'exterior_gallery' => 'array',
        'interior_gallery' => 'array',
        'amount' => 'decimal:2',
        'amount_dirhams' => 'decimal:2',
    ];
}
