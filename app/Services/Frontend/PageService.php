<?php

namespace App\Services\Frontend;

use App\Models\Page;
use App\Models\Section;
use App\Models\BlogPost;

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
            'pages' => $pages->map(fn ($page) => $this->formatPage($page)),
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
            'sections' => $sections->map(fn ($section) => $this->formatSection($section)),
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
            'sections' => $page->sections->map(fn ($section) => $this->formatSection($section)),
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
            'photo' => $section->photo ? asset('storage/'.$section->photo) : null,
            'additional_fields' => $section->additional_fields,
            'sort_order' => $section->sort_order,
            'active' => $section->active,
            'created_at' => $section->created_at,
            'updated_at' => $section->updated_at,
        ];
    }
    public function getBlogs()
    {
        return BlogPost::with('tags')->paginate(10);
    }

}
