<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
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
            ->orderBy('created_at', 'desc')
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
                        'slug' => 'required|string|max:255|unique:blog_posts',
            'body' => 'required|string',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'banner_title' => 'nullable|string|max:255',
            'banner_image_alt' => 'nullable|string|max:255',
            'image_alt' => 'nullable|string|max:255',
            'mobile_image_compressed' => 'nullable|string',
            'mobile_image_alt' => 'nullable|string|max:255',
            'mobile_banner_image_compressed' => 'nullable|string',
            'mobile_banner_image_alt' => 'nullable|string|max:255',
            'show_on_main_page' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            // Metadata validation
            'metadata.meta_title' => 'nullable|string|max:255',
            'metadata.meta_description' => 'nullable|string',
            'metadata.meta_keywords' => 'nullable|string',
            'metadata.og_title' => 'nullable|string|max:255',
            'metadata.og_description' => 'nullable|string',
            'metadata.og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'metadata.twitter_card' => 'nullable|string|in:summary,summary_large_image',
            'metadata.twitter_title' => 'nullable|string|max:255',
            'metadata.twitter_description' => 'nullable|string',
            'metadata.twitter_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle file uploads for the blog post
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('blog-images', 'public');
        }
        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('blog-banners', 'public');
        }

        // Handle mobile image from compressed data
        if ($request->filled('mobile_image_compressed')) {
            $validated['mobile_image'] = $request->input('mobile_image_compressed');
        }

        // Handle mobile banner image from compressed data
        if ($request->filled('mobile_banner_image_compressed')) {
            $validated['mobile_banner_image'] = $request->input('mobile_banner_image_compressed');
        }

        // Ensure banner_title is included in the validated data
        if ($request->has('banner_title')) {
            $validated['banner_title'] = $request->input('banner_title');
        }

        // Create the blog post
        $blogPost = BlogPost::create($validated);

        // Handle metadata if provided
        if ($request->has('metadata')) {
            $metadata = $request->input('metadata');

            // Handle metadata file uploads
            if ($request->hasFile('og_image')) {
                $metadata['og_image'] = $request->file('og_image')->store('meta-images/og', 'public');
            }

            if ($request->hasFile('twitter_image')) {
                $metadata['twitter_image'] = $request->file('twitter_image')->store('meta-images/twitter', 'public');
            }

            // Create or update metadata
            $blogPost->updateMetadata($metadata);
        }

        // Handle tags
        if ($request->has('tags')) {
            $blogPost->syncTags($request->input('tags'));
        }

        return redirect()->route('blogposts.index')
            ->with('success', 'Blog post created successfully.');
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
                        'body' => 'required',
            'slug' => 'required|string|max:255|unique:blog_posts,slug,'.$blogPost->id,
            'date' => 'required|date',
            'show_on_main_page' => 'sometimes|boolean',
            'tags' => 'array',
            'image' => 'nullable',
            'banner_image' => 'nullable',
            'image_alt' => 'nullable|string|max:255',
            'banner_title' => 'nullable|string|max:255',
            'banner_image_alt' => 'nullable|string|max:255',
            'mobile_image' => 'nullable',
            'mobile_image_alt' => 'nullable|string|max:255',
            'mobile_banner_image' => 'nullable',
            'mobile_banner_image_alt' => 'nullable|string|max:255',
            // Metadata validation
            'metadata.meta_title' => 'nullable|string|max:255',
            'metadata.meta_description' => 'nullable|string',
            'metadata.meta_keywords' => 'nullable|string',
            'metadata.og_title' => 'nullable|string|max:255',
            'metadata.og_description' => 'nullable|string',
            'metadata.og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'metadata.twitter_card' => 'nullable|   string|in:summary,summary_large_image',
            'metadata.twitter_title' => 'nullable|string|max:255',
            'metadata.twitter_description' => 'nullable|string',
            'metadata.twitter_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
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

        // Handle mobile image from compressed data
        if ($request->filled('mobile_image_compressed')) {
            $mobileImagePath = $request->input('mobile_image_compressed');

            // Ensure we have a clean path
            $mobileImagePath = ltrim($mobileImagePath, '/');

            // Remove any storage/app/public prefix if it exists
            $mobileImagePath = preg_replace('#^storage/app/public/#', '', $mobileImagePath);

            // Remove any storage/ prefix
            $mobileImagePath = preg_replace('#^storage/#', '', $mobileImagePath);

            // Verify the file exists in storage
            if (Storage::disk('public')->exists($mobileImagePath)) {
                if ($blogPost->mobile_image && $blogPost->mobile_image !== $mobileImagePath) {
                    Storage::disk('public')->delete($blogPost->mobile_image);
                }
                // Store the relative path without any prefixes
                $validated['mobile_image'] = $mobileImagePath;
            } else {
                // If file doesn't exist, log the issue but don't update the path
                Log::warning('Mobile image not found in storage', [
                    'requested_path' => $mobileImagePath,
                    'full_path' => Storage::disk('public')->path($mobileImagePath),
                    'exists' => Storage::disk('public')->exists($mobileImagePath) ? 'yes' : 'no',
                    'files_in_sections' => Storage::disk('public')->files('sections')
                ]);
            }
        }

        // Handle mobile banner image from compressed data
        if ($request->filled('mobile_banner_image_compressed')) {
            $mobileBannerPath = $request->input('mobile_banner_image_compressed');

            // Ensure we have a clean path
            $mobileBannerPath = ltrim($mobileBannerPath, '/');

            // Remove any storage/app/public prefix if it exists
            $mobileBannerPath = preg_replace('#^storage/app/public/#', '', $mobileBannerPath);

            // Remove any storage/ prefix
            $mobileBannerPath = preg_replace('#^storage/#', '', $mobileBannerPath);

            // Verify the file exists in storage
            if (Storage::disk('public')->exists($mobileBannerPath)) {
                if ($blogPost->mobile_banner_image && $blogPost->mobile_banner_image !== $mobileBannerPath) {
                    Storage::disk('public')->delete($blogPost->mobile_banner_image);
                }
                // Store the relative path without any prefixes
                $validated['mobile_banner_image'] = $mobileBannerPath;
            } else {
                // If file doesn't exist, log the issue but don't update the path
                Log::warning('Mobile banner image not found in storage', [
                    'requested_path' => $mobileBannerPath,
                    'full_path' => Storage::disk('public')->path($mobileBannerPath),
                    'exists' => Storage::disk('public')->exists($mobileBannerPath) ? 'yes' : 'no',
                    'files_in_sections' => Storage::disk('public')->files('sections')
                ]);
            }
        }

        // Ensure banner_title is included in the validated data
        if ($request->has('banner_title')) {
            $validated['banner_title'] = $request->input('banner_title');
        }

        $blogPost->update($validated);

        // Handle metadata if provided
        if ($request->has('metadata')) {
            $metadata = $request->input('metadata');

            // Handle metadata file uploads
            if ($request->hasFile('og_image')) {
                if ($blogPost->metadata?->og_image) {
                    Storage::disk('public')->delete($blogPost->metadata->og_image);
                }
                $metadata['og_image'] = $request->file('og_image')->store('meta-images/og', 'public');
            }

            if ($request->hasFile('twitter_image')) {
                if ($blogPost->metadata?->twitter_image) {
                    Storage::disk('public')->delete($blogPost->metadata->twitter_image);
                }
                $metadata['twitter_image'] = $request->file('twitter_image')->store('meta-images/twitter', 'public');
            }

            // Update metadata
            $blogPost->updateMetadata($metadata);
        }

        $tags = collect($request->tags)->map(function ($tagName) {
            return Tag::firstOrCreate(['name' => $tagName])->id;
        });

        $blogPost->tags()->sync($tags);

        return redirect()->back()->with('success', 'Blog post updated successfully.');
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

        if ($type === 'mobile_image' && $blogPost->mobile_image) {
            Storage::disk('public')->delete($blogPost->mobile_image);
            $blogPost->update(['mobile_image' => null, 'mobile_image_alt' => null]);
        }

        if ($type === 'mobile_banner_image' && $blogPost->mobile_banner_image) {
            Storage::disk('public')->delete($blogPost->mobile_banner_image);
            $blogPost->update(['mobile_banner_image' => null, 'mobile_banner_image_alt' => null]);
        }

        if ($type === 'og_image' && $blogPost->metadata && $blogPost->metadata->og_image) {
            Storage::disk('public')->delete($blogPost->metadata->og_image);
            $blogPost->metadata->update(['og_image' => null]);
        }

        if ($type === 'twitter_image' && $blogPost->metadata && $blogPost->metadata->twitter_image) {
            Storage::disk('public')->delete($blogPost->metadata->twitter_image);
            $blogPost->metadata->update(['twitter_image' => null]);
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

    /**
     * Delete the OG image for the specified blog post.
     */
    public function deleteOgImage(BlogPost $blogpost)
    {
        if ($blogpost->metadata && $blogpost->metadata->og_image) {
            Storage::disk('public')->delete($blogpost->metadata->og_image);
            $blogpost->metadata->update(['og_image' => null]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'No OG image found.'], 404);
    }

    /**
     * Delete the Twitter image for the specified blog post.
     */
    public function deleteTwitterImage(BlogPost $blogpost)
    {
        if ($blogpost->metadata && $blogpost->metadata->twitter_image) {
            Storage::disk('public')->delete($blogpost->metadata->twitter_image);
            $blogpost->metadata->update(['twitter_image' => null]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'No Twitter image found.'], 404);
    }
}
