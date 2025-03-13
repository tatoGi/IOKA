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
    ];

    protected $casts = [
        'details' => 'array',
        'amenities' => 'array',
        'addresses' => 'array',
        'gallery_images' => 'array',
        'tags' => 'array',
    ];

    public function amount()
    {
        return $this->hasOne(Amount::class);
    }
    public function developer()
{
    return $this->belongsTo(Developer::class, 'developer_id');
}
}
