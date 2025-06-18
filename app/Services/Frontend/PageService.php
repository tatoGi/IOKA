<?php

namespace App\Services\Frontend;

use App\Models\BlogPost;
use App\Models\Developer;
use App\Models\Offplan;
use App\Models\Page;
use App\Models\Partner;
use App\Models\RentalResale;
use App\Models\Section;
use App\Models\Location;
use App\Models\MetaData;
use Illuminate\Support\Facades\Schema;

class PageService
{
    /**
     * Get all pages with their sections and metadata
     */
    public function getAllPages()
    {
        $pages = Page::with(['sections' => function ($query) {
            $query->orderBy('sort_order', 'asc');
        }])->with('metadata')
        ->get();

        return [
            'pages' => $pages->map(fn ($page) => $this->formatPage($page)),
            'meta' => [
                'total' => $pages->count(),
            ],
        ];
    }

    /**
     * Get a specific page by slug with its sections and metadata
     */
    public function getPageBySlug($slug)
    {
        $page = Page::with(['sections' => function ($query) {
            $query->orderBy('sort_order', 'asc');
        }])->with('metadata')
        ->where('slug', $slug)->first();

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
            'metadata' => $page->metadata ? $page->metadata->toArray() : null,
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
        return BlogPost::with('tags')->with('metadata')->orderBy('created_at', 'asc')->paginate(10);
    }

    /**
     * Get a specific blog by slug
     */
    public function getBlogBySlug($slug)
    {
        $blog = BlogPost::with('tags')->with('metadata')->orderBy('created_at', 'desc')->where('slug', $slug)->first();

        if (! $blog) {
            return null;
        }

        $relatedBlogs = BlogPost::with('tags')->with('metadata')
            ->whereHas('tags', function ($query) use ($blog) {
                $query->whereIn('name', $blog->tags->pluck('name'));
            })
            ->where('id', '!=', $blog->id)
            ->get();

        return [
            'blog' => $this->formatBlog($blog),
            'related_blogs' => $relatedBlogs->map(fn ($relatedBlog) => $this->formatBlog($relatedBlog)),
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
            'metadata' => $blog->metadata ? $blog->metadata->toArray() : null,
        ];
    }

    public function getAllDevelopers()
    {
        return Developer::with('metadata')->orderBy('created_at', 'desc')->paginate(10);
    }

    public function getDeveloperBySlug($slug)
    {
        // Fetch the developer with related awards, offplans, rental_resale and metadata
        $developer = Developer::where('slug', $slug)
            ->with(['awards', 'offplanListings', 'rentalResaleListings', 'metadata'])
            ->orderBy('created_at', 'desc')
            ->first();

        if (! $developer) {
            return null;
        }

        return $developer;
    }

    public function getAlloffplan()
    {
        return Offplan::with('metadata')->orderBy('created_at', 'desc')->paginate(12);
    }

    public function getOffplanBySlug($slug)
    {
        // Fetch the current offplan by slug with metadata
        $offplan = Offplan::with('metadata')->where('slug', $slug)->orderBy('created_at', 'desc')->first();

        if (! $offplan) {
            return null;
        }

        // Fetch the last 4 added offplans excluding the current one, with metadata
        $lastAddedOffplans = Offplan::with('metadata')->where('id', '!=', $offplan->id)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // Return the current offplan along with the last added offplans
        return [
            'offplan' => $offplan, // metadata will be on this object
            'lastAddedOffplans' => $lastAddedOffplans, // metadata will be on these objects
        ];
    }

    public function getRentalResale()
    {
        return RentalResale::with('metadata')->with('amount')->with('locations')->orderBy('created_at', 'desc')->paginate(10);
    }

    public function getRentalResaleBySlug($slug)
    {
        $rentalResale = RentalResale::with('metadata')->with('amount')->where('slug', $slug)->first();

        if (! $rentalResale) {
            return null;
        }

        return $rentalResale;
        // Fetch the last 4 added rental resales excluding the current one
    }
    public function partners()
    {
        return Partner::orderBy('created_at', 'desc')->get();
    }
    public function aboutpage($id)
    {
        return Section::where('page_id', $id)->first();
    }
    public function getContactData($id)
    {
        return Section::where('page_id', $id)->first();
    }

    public function search($query)
    {
        $searchInModel = function ($modelClass, $query) {
            $model = new $modelClass();
            $queryBuilder = $modelClass::query();
            $table = $model->getTable();
            $columns = Schema::getColumnListing($table);

            $queryBuilder->where(function ($q) use ($columns, $query, $table) {
                foreach ($columns as $column) {
                    $q->orWhere($table . '.' . $column, 'like', "%{$query}%");
                }
            });

            if (method_exists($model, 'metadata')) {
                $queryBuilder->with('metadata');
            }

            return $queryBuilder->paginate(50);
        };

        $blogs = $searchInModel(BlogPost::class, $query);
        $developers = $searchInModel(Developer::class, $query);
        $offplans = $searchInModel(Offplan::class, $query);
        $rentalResales = $searchInModel(RentalResale::class, $query);

        return [
            'blogs' => $blogs,
            'developers' => $developers,
            'offplans' => $offplans,
            'rental_resales' => $rentalResales,
        ];
    }
    public function getLocations()
    {
        return Location::all();
    }

    public function getMetadataByType($type, $slug = null)
    {
        // Convert type to PascalCase (e.g., "blog-post" -> "BlogPost", "page" -> "Page")
        $className = str_replace(['-', '_'], ' ', $type);
        $className = str_replace(' ', '', ucwords($className));
        $modelClass = 'App\\\\Models\\\\' . $className;

        if (!class_exists($modelClass)) {
            return ['message' => "Model for type '{$type}' not found."];
        }

        // If slug is null, fetch all metadata for the type
        if ($slug === null) {
            // Ensure MetaData model is imported or use full namespace
            return MetaData::where('metadatable_type', $modelClass)->get()->toArray();
        }

        // If a slug is provided, fetch specific metadata for the entity
        $queryColumn = 'slug'; // Default column to query by
        $parentModel = $modelClass::where($queryColumn, $slug)->first();

        if ($parentModel && method_exists($parentModel, 'metadata')) {
            $metadata = $parentModel->metadata; // This loads the MetaData via morphOne
            return $metadata ? $metadata->toArray() : null;
        }

        return ['message' => "Metadata not found for '{$type}' with slug '{$slug}'."];
    }
}
