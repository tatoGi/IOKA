<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Developer;
use App\Models\DeveloperAward;
use App\Models\Offplan;
use App\Models\RentalResale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeveloperController extends Controller
{
    public function index()
    {
        $developers = Developer::paginate(10);

        return view('admin.developer.index', compact('developers'));
    }

    // Show the form for creating a new developer
    public function create()
    {
        $rentalandresaleListings = RentalResale::all();
        $offplanListings = Offplan::all();
        return view('admin.developer.create', compact('rentalandresaleListings', 'offplanListings'));
    }

    // Store a newly created developer in storage
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:developers,slug',
            'paragraph' => 'required|string',
            'phone' => 'required|string|max:20',
            'whatsapp' => 'required|string|max:20',
            'photo.*.file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate multiple photos
            'photo.*.alt' => 'nullable|string|max:255', // Validate alt text
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',
            'rental_listings' => 'nullable|array',
            'rental_listings.*' => 'exists:rental_resales,id',
            'offplan_listings' => 'nullable|array',
            'offplan_listings.*' => 'exists:offplans,id',
            'awards' => 'nullable|array', // Validate awards array
            'awards.*.award_title' => 'required|string|max:255', // Validate award title
            'awards.*.award_year' => 'required|integer|min:1900|max:' . date('Y'), // Validate award year
            'awards.*.award_description' => 'nullable|string', // Validate award description
            'awards.*.award_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate award photo
        ]);

        // Handle multiple photo uploads with alt text
        $photos = [];
        if ($request->has('photo')) {
            foreach ($request->photo as $photo) {
                if (isset($photo['file'])) {
                    $path = $photo['file']->store('photos', 'public');
                    $photos[] = [
                        'file' => $path,
                        'alt' => $photo['alt'] ?? '', // Save alt text (if provided)
                    ];
                }
            }
        }

        // Create the developer
        $developer = Developer::create([
            'title' => $request->input('title'),
            'slug' => $this->generateUniqueSlug($request->input('slug')),
            'paragraph' => $request->input('paragraph'),
            'phone' => $request->input('phone'),
            'whatsapp' => $request->input('whatsapp'),
            'photo' => json_encode($photos), // Store photos with alt text as a JSON array
            'tags' => json_encode($request->input('tags')),
            'rental_listings' => json_encode($request->input('rental_listings')),
            'offplan_listings' => json_encode($request->input('offplan_listings')),
        ]);

        // Attach rental listings
        if ($request->has('rental_listings')) {
            $developer->rentalResaleListings()->sync($request->input('rental_listings'));
        }

        // Attach offplan listings
        if ($request->has('offplan_listings')) {
            $developer->offplanListings()->sync($request->input('offplan_listings'));
        }

        // Handle awards
        if ($request->has('awards')) {
            foreach ($request->awards as $awardData) {
                $award = new DeveloperAward([
                    'award_title' => $awardData['award_title'],
                    'award_year' => $awardData['award_year'],
                    'award_description' => $awardData['award_description'] ?? null,
                ]);

                // Handle award photo upload
                if (isset($awardData['award_photo'])) {
                    $awardPhotoPath = $awardData['award_photo']->store('award_photos', 'public');
                    $award->award_photo = $awardPhotoPath;
                }

                $developer->awards()->save($award);
            }
        }

        return redirect()->route('admin.developer.list')->with('success', 'Developer created successfully!');
    }

    // Show the form for editing the specified developer
    public function edit($id)
{
    $developer = Developer::with(['offplanListings', 'awards', 'rentalResaleListings'])->findOrFail($id);
    $offplanListings = Offplan::all(); // Fetch all offplan listings
    $rentalListings = RentalResale::all(); // Fetch all rental listings
    $award = DeveloperAward::where('developer_id', $id)->first(); // Fetch all awards
    // Decode JSON fields for the form
    $photos = json_decode($developer->photo, true) ?? [];
    $tags = json_decode($developer->tags, true) ?? [];
    $rentalListingsArray = json_decode($developer->rental_listings, true) ?? []; // Decode rental_listings JSON

    return view('admin.developer.edit', compact(
        'developer',
        'offplanListings',
        'rentalListings',
        'photos',
        'award',
        'tags',
        'rentalListingsArray' // Pass the decoded rental_listings array to the view
    ));
}


    // Update the specified developer in storage
    public function update(Request $request, $id)
    {
        $developer = Developer::findOrFail($id);

        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:developers,slug,' . $developer->id,
            'paragraph' => 'required|string',
            'phone' => 'required|string|max:20',
            'whatsapp' => 'required|string|max:20',
            'photo.*.file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate multiple photos
            'photo.*.alt' => 'nullable|string|max:255', // Validate alt text
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',
            'rental_listings' => 'nullable|array',
            'rental_listings.*' => 'exists:rental_resales,id',
            'offplan_listings' => 'nullable|array',
            'offplan_listings.*' => 'exists:offplans,id',
            'award_title' => 'required|string|max:255', // Validate award title
            'award_year' => 'required|integer|min:1900|max:' . date('Y'), // Validate award year
            'award_description' => 'nullable|string', // Validate award description
            'award_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate award photo
        ]);

        // Handle multiple photo uploads with alt text
        $photos = json_decode($developer->photo, true) ?? [];
        if ($request->has('photo')) {
            foreach ($request->photo as $photo) {
                if (isset($photo['file'])) {
                    $path = $photo['file']->store('photos', 'public');
                    $photos[] = [
                        'file' => $path,
                        'alt' => $photo['alt'] ?? '', // Save alt text (if provided)
                    ];
                }
            }
        }

        // Update the developer
        $developer->update([
            'title' => $request->input('title'),
            'slug' => $request->input('slug'),
            'paragraph' => $request->input('paragraph'),
            'phone' => $request->input('phone'),
            'whatsapp' => $request->input('whatsapp'),
            'photo' => json_encode($photos), // Store photos with alt text as a JSON array
            'tags' => json_encode($request->input('tags')),
            'rental_listings' => json_encode($request->input('rental_listings')),
            'offplan_listings' => json_encode($request->input('offplan_listings')),
        ]);

        // Sync rental listings
        if ($request->has('rental_listings')) {
            $developer->rentalResaleListings()->sync($request->input('rental_listings'));
        }

        // Sync offplan listings
        if ($request->has('offplan_listings')) {
            $developer->offplanListings()->sync($request->input('offplan_listings'));
        }

        // Handle awards update
        $award = $developer->awards()->first(); // Get the existing award

        if ($award) {
            // Update existing award
            $award->update([
                'award_title' => $request['award_title'],
                'award_year' => $request['award_year'],
                'award_description' => $request['award_description'] ?? null,
            ]);

            // Handle award photo upload
            if (isset($request['award_photo'])) {
                $awardPhotoPath = $request['award_photo']->store('award_photos', 'public');
                $award->award_photo = $awardPhotoPath;
                $award->save();
            }
        } else {
            // Create new award if none exists
            $award = new DeveloperAward([
                'award_title' => $request['award_title'],
                'award_year' => $request['award_year'],
                'award_description' => $request['award_description'] ?? null,
            ]);

            // Handle award photo upload
            if (isset($request['award_photo'])) {
                $awardPhotoPath = $request['award_photo']->store('award_photos', 'public');
                $award->award_photo = $awardPhotoPath;
            }

            $developer->awards()->save($award);
        }

        return redirect()->route('admin.developer.list')->with('success', 'Developer updated successfully!');
    }

    // Remove the specified developer from storage
    public function destroy($id)
    {
        $developer = Developer::findOrFail($id);
        $developer->delete();

        return redirect()->route('admin.developer.list');
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
        while (Developer::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
