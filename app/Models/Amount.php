<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amount extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_resale_id',
        'amount',
        'amount_dirhams',
    ];

    public function rentalResale()
    {
        return $this->belongsTo(RentalResale::class);
    }
}
