<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Developer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'paragraph',
        'phone',
        'whatsapp',
        'photo',
        'logo',
        'rental_listings',
        'offplan_listings',
        'tags',
    ];

    protected $casts = [
        'photo' => 'array',
        'rental_listings' => 'array',
        'offplan_listings' => 'array',
        'tags' => 'array',
    ];

    protected $attributes = [
        'photo' => '[]', // Default to an empty JSON array
        'offplan_listings' => '[]', // Default to an empty JSON array
        'rental_listings' => '[]', // Default to an empty JSON array
        'tags' => '[]', // Default to an empty JSON array
    ];

    public function awards()
    {
        return $this->hasMany(DeveloperAward::class);
    }

    public function rentalResaleListings()
    {
        return $this->belongsToMany(RentalResale::class, 'developer_rental_resale', 'developer_id', 'rental_resale_id');
    }

    public function offplanListings()
    {
        return $this->belongsToMany(Offplan::class, 'developer_offplan', 'developer_id', 'offplan_id');
    }
}
