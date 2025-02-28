<?php

namespace App\Repositories;

use App\Models\Page;
use App\Repositories\Interface\PageRepositoryInterface;

class PageRepository implements PageRepositoryInterface
{
    /**
     * Get all pages with children, ordered by 'sort'.
     */
    public function getAllPages()
    {
        return Page::where('parent_id', null)
            ->orderBy('sort', 'asc')
            ->with('children')
            ->get();
    }

    /**
     * Get all parent pages (those with null or 0 parent_id).
     */
    public function getParentPages()
    {
        return Page::whereNull('parent_id')->orWhere('parent_id', 0)->get();
    }

    /**
     * Create a new page.
     */
    public function createPage(array $data)
    {
        return Page::create($data);
    }

    /**
     * Find a page by its ID.
     */
    public function findPageById($id)
    {
        return Page::find($id);
    }

    /**
     * Update a page by its ID.
     */
    public function updatePage($id, array $data)
    {
        $page = $this->findPageById($id);

        return $page ? $page->update($data) : null;
    }

    /**
     * Delete a page by its ID.
     */
    public function deletePage($id)
    {
        $page = $this->findPageById($id);

        return $page ? $page->delete() : null;
    }

    /**
     * Rearrange the pages (update their order).
     */
    public function rearrangePages(array $orderArr)
    {
        // Assuming you have a method for rearranging pages
        Page::rearrange($orderArr);
    }
}
