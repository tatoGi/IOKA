<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeveloperAwardPhotoAlt extends Model
{
    protected $fillable = [
        'developer_award_id',
        'photo_path',
        'alt_text',
    ];

    public function award(): BelongsTo
    {
        return $this->belongsTo(DeveloperAward::class, 'developer_award_id');
    }
}
