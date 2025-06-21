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

    /**
     * Sync tags for the blog post.
     * Creates new tags if they don't exist and associates them with the post.
     */
    public function syncTags(array $tagNames)
    {
        $tagIds = collect($tagNames)->map(function ($tagName) {
            return Tag::firstOrCreate(['name' => $tagName])->id;
        });

        return $this->tags()->sync($tagIds);
    }

    /**
     * Get the metadata associated with the blog post.
     */
    public function metadata()
    {
        return $this->morphOne(MetaData::class, 'metadatable');
    }

    /**
     * Update or create metadata for the blog post.
     */
    public function updateMetadata(array $metadata)
    {
        if ($this->metadata) {
            return $this->metadata->update($metadata);
        }

        return $this->metadata()->create($metadata);
    }
}
