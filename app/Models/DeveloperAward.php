<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function developer()
    {
        return $this->belongsTo(Developer::class);
    }
}
