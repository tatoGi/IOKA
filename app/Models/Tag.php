<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Define the many-to-many relationship with BlogPost.
     */
    public function blogPosts()
    {
        return $this->belongsToMany(BlogPost::class, 'blog_post_tag', 'tag_id', 'blog_post_id');
    }
}
