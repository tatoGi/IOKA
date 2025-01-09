<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'section_key',
        'page_id',
        'title',
        'slug',
        'description',
        'redirect_link',
        'photo',
        'additional_fields',
        'sort_order',
        'active'
    ];

    protected $casts = [
        'additional_fields' => 'array',
        'active' => 'boolean'
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
