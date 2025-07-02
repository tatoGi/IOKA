<?php

namespace App\Models;

use App\Traits\HasMetaData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalResale extends Model
{
    use HasFactory, HasMetaData;

    protected $table = 'rental_resale';

    protected $fillable = [
        'property_type',
        'title',
        'slug',
        'bathroom',
        'bedroom',
        'sq_ft',
        'garage',
        'description',
        'details',
        'amenities',
        'agent_title',
        'agent_status',
        'agent_call',
        'agent_whatsapp',
        'agent_email',
        'agent_photo',
        'location_link',
        'qr_photo',
        'reference',
        'dld_permit_number',
        'addresses',
        'amount',
        'gallery_images',
        'tags',
        'amount_id',
        'location_id',
        'top', // Add 'top' to the fillable attributes
        'languages',
        'subtitle',
        'alt_texts',
        'mobile_agent_photo',
        'mobile_agent_photo_alt',
        'mobile_qr_photo',
        'mobile_qr_photo_alt',
        'mobile_gallery_images',
    ];

    protected $casts = [
        'details' => 'array',
        'amenities' => 'array',
        'addresses' => 'array',
        'gallery_images' => 'array',
        'tags' => 'array',
        'top' => 'boolean',
        'languages' => 'array', // Ensuring JSON is handled properly
        'alt_texts' => 'array',
        'mobile_upload_photos' => 'array',
        'mobile_gallery_images' => 'array',
    ];

    public function amount()
    {
        return $this->hasOne(Amount::class);
    }

    public function developer()
    {
        return $this->belongsTo(Developer::class, 'developer_id');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'rental_resale_location');
    }
    public function metadata()
    {
        return $this->morphOne(MetaData::class, 'metadatable');
    }

}
