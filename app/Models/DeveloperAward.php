<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DeveloperAward extends Model
{
    use HasFactory;

    protected $fillable = [
        'developer_id',
        'award_title',
        'award_year',
        'award_description',
        'award_photo',
    ];

    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class);
    }

    public function photoAlt(): HasOne
    {
        return $this->hasOne(DeveloperAwardPhotoAlt::class);
    }
}
