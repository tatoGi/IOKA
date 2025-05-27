<?php

namespace App\Models;

use App\Traits\HasMetaData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offplan extends Model
{
    use HasFactory, HasMetaData;

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
        'agent_email',
        'agent_languages',
        'location',
        'developer_id',
        'alt_texts',
    ];

    protected $casts = [
        'features' => 'array',
        'amenities' => 'array',
        'near_by' => 'array',
        'exterior_gallery' => 'array',
        'interior_gallery' => 'array',
        'agent_languages' => 'array',
        'alt_texts' => 'array',
        'amount' => 'decimal:2',
        'amount_dirhams' => 'decimal:2',
    ];

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'offplan_location');
    }

    public function developer()
    {
        return $this->belongsTo(Developer::class);
    }
    public function metadata()
    {
        return $this->morphOne(MetaData::class, 'metadatable');
    }

}
