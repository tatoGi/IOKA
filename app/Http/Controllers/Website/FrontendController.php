<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\Frontend\PageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Services\Frontend\FilterService;
use App\Models\ContactSubmission;
use App\Models\Developer;
use App\Models\Setting;
class FrontendController extends Controller
{
    protected $pageService;
    protected $filterService;

    public function __construct(PageService $pageService, FilterService $filterService)
    {
        $this->pageService = $pageService;
        $this->filterService = $filterService;
    }


    /**
     * Get all pages with their sections
     */
    public function getPages(): JsonResponse
    {
        return response()->json($this->pageService->getAllPages());
    }

    /**
     * Get a specific page with its sections
     */
    public function getPage($slug): JsonResponse
    {
        $page = $this->pageService->getPageBySlug($slug);

        if (! $page) {
            return response()->json([
                'error' => 'Page not found',
            ], 404);
        }

        return response()->json($page);
    }

    /**
     * Get all sections
     */
    public function getSections(): JsonResponse
    {
        return response()->json($this->pageService->getAllSections());
    }

    /**
     * Get a specific section
     */
    public function getSection($id): JsonResponse
    {
        $section = $this->pageService->getSectionById($id);

        if (! $section) {
            return response()->json([
                'error' => 'Section not found',
            ], 404);
        }

        return response()->json($section);
    }

    public function getblogs()
    {
        $blog = $this->pageService->getBlogs();

        return response()->json($blog);
    }

    public function getBlog($slug): JsonResponse
    {

        $blog = $this->pageService->getBlogBySlug($slug);

        if (! $blog) {
            return response()->json([
                'error' => 'Blog not found',
            ], 404);
        }

        return response()->json($blog);
    }

    public function getdevelopers()
    {
        $developers = $this->pageService->getAllDevelopers();

        return response()->json($developers);
    }

    public function getDeveloper($slug): JsonResponse
    {
        $developer = $this->pageService->getDeveloperBySlug($slug);

        if (! $developer) {
            return response()->json([
                'error' => 'Developer not found',
            ], 404);
        }

        return response()->json($developer);
    }

    public function getOffplans()
    {
        $ofplanns = $this->pageService->getAlloffplan();

        return response()->json($ofplanns);
    }

    public function getoffplan($slug)
    {
        $ofplanns = $this->pageService->getoffplanbySlug($slug);
        if (! $ofplanns) {
            return response()->json([
                'error' => 'Offplan not found',
            ], 404);
        }

        return response()->json($ofplanns);
    }

    public function getRentalResale()
    {
        $rentalResale = $this->pageService->getRentalResale();

        return response()->json($rentalResale);
    }

    public function getRentalResaleBySlug($slug)
    {

        $rentalresale = $this->pageService->getRentalResaleBySlug($slug);
        if (! $rentalresale) {
            return response()->json([
                'error' => 'rental resale not found',
            ], 404);
        }

        return response()->json($rentalresale);
    }
    public function getPartners()
    {
        $partners = $this->pageService->Partners();

        return response()->json($partners);
    }
    public function getabout($id)
    {
        $about = $this->pageService->aboutpage($id);

        return response()->json([
            'about' => $about,
        ]);
    }
    public function getContact($id)
    {
        $contact = $this->pageService->getContactData($id);

        return response()->json([
            'contact' => $contact,
        ]);
    }
    public static  function submission(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'message' => 'required|string',
            'page_title' => 'nullable|string|max:255', // Add this line
            'page_url' => 'nullable|string|max:255', // Add this line
        ]);

        $submission = ContactSubmission::create($validated);

        return response()->json([
            'message' => 'Contact form submitted successfully!',
            'data' => $submission
        ], 201);
    }

    public function search(Request $request): JsonResponse
    {

        $query = $request->input('query');

        if (!$query) {
            return response()->json(['error' => 'Search query is required'], 400);
        }

        $results = $this->pageService->search($query);

        return response()->json($results);
    }
    public function filter_offplan(Request $request)
    {

        $filters = $request->all(); // Retrieve filters from the request
        $offplan = $this->filterService->filterOffplans($filters);

        return response()->json($offplan);
    }
    // for github //
    public function getLocations()
    {
        $locations = $this->pageService->getLocations();
        return response()->json($locations);
    }
    public function getSettings()
    {
        $settings = Setting::all()->groupBy('group');
        return response()->json($settings);
    }
    public function searchDeveloper(Request $request)
    {
        // Validate the search term
        $request->validate([
            'search' => 'required|string|min:3|max:255',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ]);

        // Get search term from request
        $searchTerm = $request->input('search');
        $perPage = $request->input('per_page', 10); // Default to 10 items per page

        // Perform the search with relationships
        $developers = Developer::with(['awards', 'rentalResaleListings', 'offplanListings'])
            ->search($searchTerm)
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Search results',
            'data' => $developers,
            'meta' => [
                'total' => $developers->total(),
                'current_page' => $developers->currentPage(),
                'per_page' => $developers->perPage(),
                'last_page' => $developers->lastPage(),
            ]
        ]);
    }
}
