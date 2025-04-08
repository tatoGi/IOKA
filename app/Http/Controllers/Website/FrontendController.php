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
    public function submission(Request $request)
    {
        dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'message' => 'required|string',
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
}
