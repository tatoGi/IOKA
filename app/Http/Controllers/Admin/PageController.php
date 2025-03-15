<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequestStore;
use App\Models\Page;
use App\Repositories\Interface\PageRepositoryInterface; // Import the repository interface
use Illuminate\Http\Request;

class PageController extends Controller
{
    private $pageRepository;

    // Inject the PageRepositoryInterface into the controller
    public function __construct(PageRepositoryInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    // Index method to display all pages
    public function index()
    {
        $pages = $this->pageRepository->getAllPages(); // Use the repository to get pages

        return view('admin.pages.index', compact('pages'));
    }

    // Method to show the form for creating a new page
    public function create()
    {
        $parentPages = $this->pageRepository->getParentPages(); // Get parent pages using the repository

        return view('admin.pages.create', compact('parentPages'));
    }

    // Store method to create a new page
    public function store(PageRequestStore $request)
    {
        // Get validated data directly from the request
        $validatedData = $request->validated();

        // Check if the 'active' field is present and set it to 1, otherwise 0
        $validatedData['active'] = $request->has('active') ? 1 : 0;

        // Generate a unique slug
        $validatedData['slug'] = $this->generateUniqueSlug($validatedData['slug']);

        // Use the repository to create a new page
        $this->pageRepository->createPage($validatedData);

        // Redirect with a success message
        return redirect()->route('menu.index')->with('success', 'Page created successfully!');
    }

    /**
     * Generate a unique slug by appending a suffix if necessary.
     */
    private function generateUniqueSlug(string $slug): string
    {
        // Replace spaces with dashes
        $slug = str_replace(' ', '-', $slug);

        // Alternatively, use Laravel's helper for a cleaner slug
        // $slug = \Illuminate\Support\Str::slug($slug);

        $originalSlug = $slug;
        $counter = 1;

        // Ensure the slug is unique
        while (Page::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    // Method to show the edit form for a page
    public function edit($id)
    {
        $page = $this->pageRepository->findPageById($id); // Use the repository to find the page
        $parentPages = $this->pageRepository->getParentPages(); // Get parent pages using the repository

        return view('admin.pages.edit', compact('page', 'parentPages'));
    }

    // Update method to save the changes made to a page
    public function update(Request $request, $id)
    {

        // Use the repository to update the page
        $this->pageRepository->updatePage($id, $request->all());

        return redirect()->route('menu.index');
    }

    // Rearrange pages method
    public function arrange(Request $request)
    {
        $array = $request->input('orderArr');

        // Use the repository to rearrange pages
        $this->pageRepository->rearrangePages($array);

        return ['error' => false];
    }

    // Method to delete a page
    public function destroy($id)
    {
        // Use the repository to delete the page
        $this->pageRepository->deletePage($id);

        // Redirect with a success message
        return redirect()->route('menu.index')->with('success', 'Page deleted successfully.');
    }
}
