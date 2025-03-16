<?php

namespace App\Services\Frontend;

use App\Models\Page;
use App\Models\Section;
use App\Models\BlogPost;
use App\Models\Developer;
use App\Models\Offplan;
use App\Models\RentalResale;

class PageService
{
    /**
     * Get all pages with their sections
     */
    public function getAllPages()
    {
        $pages = Page::with(['sections' => function ($query) {
            $query->orderBy('sort_order', 'asc');
        }])->get();

        return [
            'pages' => $pages->map(fn($page) => $this->formatPage($page)),
            'meta' => [
                'total' => $pages->count(),
            ],
        ];
    }

    /**
     * Get a specific page by slug
     */
    public function getPageBySlug($slug)
    {
        $page = Page::with(['sections' => function ($query) {
            $query->orderBy('sort_order', 'asc');
        }])->where('slug', $slug)->first();

        if (! $page) {
            return null;
        }

        return [
            'page' => $this->formatPage($page),
        ];
    }

    /**
     * Get all sections
     */
    public function getAllSections()
    {
        $sections = Section::orderBy('sort_order', 'asc')->get();

        return [
            'sections' => $sections->map(fn($section) => $this->formatSection($section)),
            'meta' => [
                'total' => $sections->count(),
            ],
        ];
    }

    /**
     * Get a specific section by ID
     */
    public function getSectionById($id)
    {
        $section = Section::find($id);

        if (! $section) {
            return null;
        }

        return [
            'section' => $this->formatSection($section),
        ];
    }

    /**
     * Format page data
     */
    private function formatPage($page)
    {
        return [
            'id' => $page->id,
            'title' => $page->title,
            'keywords' => $page->keywords,
            'slug' => $page->slug,
            'desc' => $page->desc,
            'parent_id' => $page->parent_id,
            'type_id' => $page->type_id,
            'sort' => $page->sort,
            'active' => $page->active,
            'created_at' => $page->created_at,
            'updated_at' => $page->updated_at,
        ];
    }

    /**
     * Format section data
     */
    private function formatSection($section)
    {
        return [
            'id' => $section->id,
            'section_key' => $section->section_key,
            'page_id' => $section->page_id,
            'title' => $section->title,
            'slug' => $section->slug,
            'description' => $section->description,
            'redirect_link' => $section->redirect_link,
            'photo' => $section->photo ? asset('storage/' . $section->photo) : null,
            'additional_fields' => $section->additional_fields,
            'sort_order' => $section->sort_order,
            'active' => $section->active,
            'created_at' => $section->created_at,
            'updated_at' => $section->updated_at,
        ];
    }

    public function getBlogs()
    {
        return BlogPost::with('tags')->orderBy('created_at', 'desc')->paginate(10);
    }

    /**
     * Get a specific blog by slug
     */
    public function getBlogBySlug($slug)
    {
        $blog = BlogPost::with('tags')->orderBy('created_at', 'desc')->where('slug', $slug)->first();

        if (! $blog) {
            return null;
        }

        $relatedBlogs = BlogPost::with('tags')
            ->whereHas('tags', function ($query) use ($blog) {
                $query->whereIn('name', $blog->tags->pluck('name'));
            })
            ->where('id', '!=', $blog->id)
            ->get();

        return [
            'blog' => $this->formatBlog($blog),
            'related_blogs' => $relatedBlogs->map(fn($relatedBlog) => $this->formatBlog($relatedBlog)),
        ];
    }

    /**
     * Format blog data
     */
    private function formatBlog($blog)
    {
        return [
            'id' => $blog->id,
            'title' => $blog->title,
            'subtitle' => $blog->subtitle,
            'body' => $blog->body,
            'slug' => $blog->slug,
            'date' => $blog->date,
            'show_on_main_page' => $blog->show_on_main_page,
            'banner_image' => $blog->banner_image,
            'banner_image_alt' => $blog->banner_image_alt,
            'image' => $blog->image,
            'image_alt' => $blog->image_alt,
            'tags' => $blog->tags->pluck('name'),
            'created_at' => $blog->created_at,
            'updated_at' => $blog->updated_at,
        ];
    }
    public function  getAllDevelopers()
    {
        return Developer::orderBy('created_at', 'desc')->paginate(10);
    }
    public function getDeveloperBySlug($slug)
    {
        // Fetch the developer with related awards, offplans, and rental_resale
        $developer = Developer::where('slug', $slug)
            ->with(['awards', 'offplanListings', 'rentalResaleListings'])
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$developer) {
            return null;
        }

        return $developer;
    }
    public function getAlloffplan()
    {
        return Offplan::orderBy('created_at', 'desc')->paginate(10);
    }
    public function getOffplanBySlug($slug)
    {
        // Fetch the current offplan by slug
        $offplan = Offplan::where('slug', $slug)->orderBy('created_at', 'desc')->first();

        if (!$offplan) {
            return null;
        }

        // Fetch the last 4 added offplans excluding the current one
        $lastAddedOffplans = Offplan::where('id', '!=', $offplan->id)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // Return the current offplan along with the last added offplans
        return [
            'offplan' => $offplan,
            'lastAddedOffplans' => $lastAddedOffplans,
        ];
    }
    public function getRentalResale()
    {
        return RentalResale::orderBy('created_at', 'desc')->with('amount')->paginate(10);
    }
    public function getRentalResaleBySlug($slug)
    {
        $rentalResale = RentalResale::where('slug', $slug)->with('amount')->first();

        if (!$rentalResale) {
            return null;
        }

        return $rentalResale;
        // Fetch the last 4 added rental resales excluding the current one
    }
}
