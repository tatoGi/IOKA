<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'paragraph',
        'phone',
        'whatsapp',
        'photo',
        'rental_listings',
        'offplan_listings',
    ];

    protected $casts = [
        'rental_listings' => 'array',
        'offplan_listings' => 'array',
    ];

    public function awards()
    {
        return $this->hasMany(DeveloperAward::class);
    }

    public function rentalResaleListings()
    {
        return $this->hasMany(RentalResale::class, 'developer_id');
    }

    public function offplanListings()
    {
        return $this->hasMany(Offplan::class, 'developer_id');
    }
}
