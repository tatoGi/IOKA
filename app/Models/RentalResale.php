<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalResale extends Model
{
    use HasFactory;

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
        'agent_languages',
        'agent_call',
        'agent_whatsapp',
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
    ];

    protected $casts = [
        'details' => 'array',
        'amenities' => 'array',
        'addresses' => 'array',
        'gallery_images' => 'array',
        'tags' => 'array',
        'languages' => 'array', // Ensuring JSON is handled properly
    ];

    public function amount()
    {
        return $this->hasOne(Amount::class);
    }

    public function developer()
    {
        return $this->belongsTo(Developer::class, 'developer_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
