<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'title',
        'description',
        'slug',
        'page_id',
        'section_key',
        'additional_fields',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'additional_fields' => 'array',
        'active' => 'boolean',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
