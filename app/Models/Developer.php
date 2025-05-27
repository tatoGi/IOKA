<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\HasMetaData;

class Developer extends Model
{
    use HasFactory, HasMetaData;

    protected $fillable = [
        'title',
        'slug',
        'paragraph',
        'phone',
        'whatsapp',
        'photo',
        'logo',
        'logo_alt',
        'rental_listings',
        'offplan_listings',
        'tags',
        'banner_image',
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
    public function scopeSearch(Builder $query, string $searchTerm)
    {
        return $query->where(function($q) use ($searchTerm) {
            // Search developer fields
            $q->where('title', 'LIKE', "%{$searchTerm}%")
              ->orWhere('paragraph', 'LIKE', "%{$searchTerm}%")
              ->orWhere('phone', 'LIKE', "%{$searchTerm}%")
              ->orWhere('whatsapp', 'LIKE', "%{$searchTerm}%")
              ->orWhereJsonContains('tags', $searchTerm);

            // Search through relationships
            $q->orWhereHas('awards', function($awardQuery) use ($searchTerm) {
                $awardQuery->where('title', 'LIKE', "%{$searchTerm}%");

            })
            ->orWhereHas('rentalResaleListings', function($rentalQuery) use ($searchTerm) {
                $rentalQuery->where('title', 'LIKE', "%{$searchTerm}%")
                             ->orWhere('description', 'LIKE', "%{$searchTerm}%");

            })
            ->orWhereHas('offplanListings', function($offplanQuery) use ($searchTerm) {
                $offplanQuery->where('title', 'LIKE', "%{$searchTerm}%")
                             ->orWhere('description', 'LIKE', "%{$searchTerm}%");

            });
        });
    }
    public function metadata()
    {
        return $this->morphOne(MetaData::class, 'metadatable');
    }

}
