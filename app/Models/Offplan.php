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
        'banner_title',
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
        'mobile_main_photo',
        'mobile_main_photo_alt',
        'mobile_banner_photo',
        'mobile_banner_photo_alt',
    ];

    protected $casts = [
        'features' => 'array',
        'amenities' => 'array',
        'near_by' => 'array',
        'exterior_gallery' => 'array',
        'interior_gallery' => 'array',
        'agent_languages' => 'array',
        'alt_texts' => 'array',
        'meta_data' => 'array',
        'meta_data->schema' => 'array',
        'amount' => 'string',
        'amount_dirhams' => 'string',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $jsonable = [
        'amenities_icons'
    ];
    
    /**
     * Set the amenities_icons attribute.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setAmenitiesIconsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['amenities_icons'] = json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } else {
            $this->attributes['amenities_icons'] = $value;
        }
    }
    
    /**
     * Get the amenities_icons attribute.
     *
     * @param  mixed  $value
     * @return array
     */
    public function getAmenitiesAttribute($value)
    {
        $amenities = is_string($value) ? json_decode($value, true) : $value;
        
        if (!is_array($amenities)) {
            return [];
        }
        
        // Ensure each amenity has both name and icon
        return array_map(function($amenity) {
            if (is_array($amenity)) {
                return [
                    'name' => $amenity['name'] ?? '',
                    'icon' => $amenity['icon'] ?? ''
                ];
            }
            
            // Handle legacy format (string only)
            return [
                'name' => $amenity,
                'icon' => ''
            ];
        }, $amenities);
    }

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
