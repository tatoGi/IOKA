<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Tag;
use Illuminate\Http\Request;

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
            'date' => 'required|date',
            'show_on_main_page' => 'boolean',
            'tags' => 'array',
        ]);

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
            'date' => 'required|date',
            'show_on_main_page' => 'boolean',
            'tags' => 'array',
        ]);

        $blogPost->update($validated);
        $tags = collect($request->tags)->map(function ($tagName) {
            return Tag::firstOrCreate(['name' => $tagName])->id;
        });

        $blogPost->tags()->sync($tags);

        return redirect()->route('blogposts.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blogPost)
    {
        $blogPost->delete();
        return redirect()->route('blogposts.index')->with('success', 'Blog post deleted successfully.');
    }
}
