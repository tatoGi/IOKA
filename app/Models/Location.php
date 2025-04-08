<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['title'];

    public function offplans()
    {
        return $this->belongsToMany(Offplan::class, 'offplan_location');
    }

    public function rentalResales()
    {
        return $this->belongsToMany(RentalResale::class, 'rental_resale_location');
    }
}
