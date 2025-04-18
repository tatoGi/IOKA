<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'body',
        'date',
        'show_on_main_page',
        'image',
        'image_alt',
        'banner_image',
        'banner_image_alt',
    ];
    protected $casts = [
        'show_on_main_page' => 'boolean',
        // your other casts
    ];
    /**
     * Define the many-to-many relationship with Tag.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_post_tag', 'blog_post_id', 'tag_id');
    }
}
