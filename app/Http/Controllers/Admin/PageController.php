<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequestStore;
use App\Models\Page;
use App\Repositories\Interface\PageRepositoryInterface; // Import the repository interface
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Added import for Storage

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

        // Ensure meta_title is included in the data
        // $validatedData['meta_title'] = $request->input('meta_title'); // Already handled by PageRequestStore

        // Use the repository to create a new page
        $page = $this->pageRepository->createPage($validatedData);

        // Handle metadata if provided
        if ($request->has('metadata')) {
            $metadata = $request->input('metadata');

            // Handle metadata file uploads
            if ($request->hasFile('metadata.og_image')) {
                $metadata['og_image'] = $request->file('metadata.og_image')->store('meta-images/og', 'public');
            }

            if ($request->hasFile('metadata.twitter_image')) {
                $metadata['twitter_image'] = $request->file('metadata.twitter_image')->store('meta-images/twitter', 'public');
            }
            // Create or update metadata
            $page->updateMetadata($metadata);
        }

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

        // Get the existing page
        $page = $this->pageRepository->findPageById($id);

        // Prepare the update data
        $pageData = $request->except('metadata'); // Exclude metadata from main page update

        // Handle active state - check if the checkbox is checked
        $pageData['active'] = $request->active ? 1 : 0;

        // Check if the slug is being updated
        if (isset($pageData['slug']) && $pageData['slug'] && $pageData['slug'] !== $page->slug) {
            $pageData['slug'] = $this->generateUniqueSlug($pageData['slug']);
        }

        // Use the repository to update the page
        $this->pageRepository->updatePage($id, $pageData);

        // Handle metadata if provided
        if ($request->has('metadata')) {
            $metadata = $request->input('metadata');

            // Handle metadata file uploads
            if ($request->hasFile('metadata.og_image')) {
                if ($page->metadata && $page->metadata->og_image) {
                    Storage::disk('public')->delete($page->metadata->og_image);
                }
                $metadata['og_image'] = $request->file('metadata.og_image')->store('meta-images/og', 'public');
            } elseif (isset($metadata['remove_og_image']) && $metadata['remove_og_image'] == 1 && $page->metadata && $page->metadata->og_image) {
                Storage::disk('public')->delete($page->metadata->og_image);
                $metadata['og_image'] = null;
            }

            if ($request->hasFile('metadata.twitter_image')) {
                if ($page->metadata && $page->metadata->twitter_image) {
                    Storage::disk('public')->delete($page->metadata->twitter_image);
                }
                $metadata['twitter_image'] = $request->file('metadata.twitter_image')->store('meta-images/twitter', 'public');
            } elseif (isset($metadata['remove_twitter_image']) && $metadata['remove_twitter_image'] == 1 && $page->metadata && $page->metadata->twitter_image) {
                Storage::disk('public')->delete($page->metadata->twitter_image);
                $metadata['twitter_image'] = null;
            }

            // Clean up remove flags if they exist
            unset($metadata['remove_og_image']);
            unset($metadata['remove_twitter_image']);

            // Update metadata
            $page->updateMetadata($metadata);
        }

        return redirect()->route('menu.index')->with('success', 'Page updated successfully!');
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

    /**
     * Delete a meta image (OG or Twitter) for a page
     * 
     * @param int $page Page ID
     * @param string $type Type of image ('og' or 'twitter')
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMetaImage($page, $type)
    {
        if (!in_array($type, ['og', 'twitter'])) {
            return response()->json(['success' => false, 'message' => 'Invalid image type'], 400);
        }

        $page = $this->pageRepository->findPageById($page);
        
        if (!$page) {
            return response()->json(['success' => false, 'message' => 'Page not found'], 404);
        }

        if (!$page->metadata) {
            return response()->json(['success' => false, 'message' => 'No metadata found for this page'], 404);
        }

        // Determine which image field to update based on the type
        $imageField = $type . '_image';
        
        // Check if the image exists
        if (!$page->metadata->$imageField) {
            return response()->json(['success' => false, 'message' => 'Image not found'], 404);
        }

        // Delete the image file from storage
        $deleted = Storage::disk('public')->delete($page->metadata->$imageField);
        
        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Failed to delete the image file'], 500);
        }

        // Update metadata to remove the image reference
        $metadata = $page->metadata->toArray();
        $metadata[$imageField] = null;
        $page->updateMetadata($metadata);

        return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
    }
}
