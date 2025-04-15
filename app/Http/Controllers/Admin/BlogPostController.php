<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        $blogPosts = BlogPost::with('tags')
            ->when($request->search, function ($query) use ($request) {
                $query->where('title', 'like', "%{$request->search}%");
            })
            ->when($request->date_from && $request->date_to, function ($query) use ($request) {
                $query->whereBetween('date', [$request->date_from, $request->date_to]);
            })
            ->paginate(10);

        return view('admin.blogposts.index', compact('blogPosts'));
    }

    public function create()
    {
        $tags = Tag::all();

        return view('admin.blogposts.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'body' => 'required',
            'slug' => 'required|string|max:255|unique:blog_posts,slug',
            'date' => 'required|date',
            'show_on_main_page' => 'boolean',
            'tags' => 'array',
            'image' => 'nullable|image|max:2048',
            'banner_image' => 'nullable|image|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'banner_image_alt' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = $this->generateUniqueSlug($validated['slug']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('blog_images', 'public');
        }

        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('blog_banners', 'public');
        }

        $blogPost = BlogPost::create($validated);
        $tags = collect($request->tags)->map(function ($tagName) {
            return Tag::firstOrCreate(['name' => $tagName])->id;
        });

        $blogPost->tags()->sync($tags);

        return redirect()->route('blogposts.index')->with('success', 'Blog post created successfully.');
    }

    public function edit(BlogPost $blogPost)
    {
        $tags = Tag::all();

        return view('admin.blogposts.edit', compact('blogPost', 'tags'));
    }

    public function update(Request $request, BlogPost $blogPost)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'body' => 'required',
            'slug' => 'required|string|max:255|unique:blog_posts,slug,'.$blogPost->id,
            'date' => 'required|date',
            'show_on_main_page' => 'sometimes|boolean',
            'tags' => 'array',
            'image' => 'nullable|image|max:2048',
            'banner_image' => 'nullable|image|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'banner_image_alt' => 'nullable|string|max:255',
        ]);

        if ($validated['slug'] !== $blogPost->slug) {
            $validated['slug'] = $this->generateUniqueSlug($validated['slug']);
        }

        if ($request->hasFile('image')) {
            if ($blogPost->image) {
                Storage::disk('public')->delete($blogPost->image);
            }
            $validated['image'] = $request->file('image')->store('blog_images', 'public');
        }

        if ($request->hasFile('banner_image')) {
            if ($blogPost->banner_image) {
                Storage::disk('public')->delete($blogPost->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('blog_banners', 'public');
        }

        $blogPost->update($validated);
        $tags = collect($request->tags)->map(function ($tagName) {
            return Tag::firstOrCreate(['name' => $tagName])->id;
        });

        $blogPost->tags()->sync($tags);

        return redirect()->route('blogposts.index')->with('success', 'Blog post updated successfully.');
    }

    public function removeImage(Request $request, BlogPost $blogPost)
    {
        $type = $request->input('type');

        if ($type === 'image' && $blogPost->image) {
            Storage::disk('public')->delete($blogPost->image);
            $blogPost->update(['image' => null, 'image_alt' => null]);
        }

        if ($type === 'banner_image' && $blogPost->banner_image) {
            Storage::disk('public')->delete($blogPost->banner_image);
            $blogPost->update(['banner_image' => null, 'banner_image_alt' => null]);
        }

        return response()->json(['success' => true, 'message' => ucfirst(str_replace('_', ' ', $type)).' removed successfully.']);
    }

    public function destroy(BlogPost $blogPost)
    {
        if ($blogPost->image) {
            Storage::disk('public')->delete($blogPost->image);
        }

        if ($blogPost->banner_image) {
            Storage::disk('public')->delete($blogPost->banner_image);
        }

        $blogPost->delete();

        return redirect()->route('blogposts.index')->with('success', 'Blog post deleted successfully.');
    }

    private function generateUniqueSlug(string $slug): string
    {
        // Replace spaces with dashes
        $slug = str_replace(' ', '-', $slug);

        // Alternatively, use Laravel's helper for a cleaner slug
        // $slug = \Illuminate\Support\Str::slug($slug);

        $originalSlug = $slug;
        $counter = 1;

        // Ensure the slug is unique
        while (BlogPost::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
