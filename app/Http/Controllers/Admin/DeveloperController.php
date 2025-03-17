<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Developer;
use App\Models\DeveloperAward;
use App\Models\Offplan;
use App\Models\RentalResale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DeveloperController extends Controller
{
    public function index()
    {
        $developers = Developer::orderBy('created_at', 'desc')->paginate(10);

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
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:developers,slug',
            'paragraph' => 'required|string',
            'phone' => 'required|string|max:20',
            'whatsapp' => 'required|string|max:20',
            'photo.*.file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo.*.alt' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',
            'rental_listings' => 'nullable|array',
            'rental_listings.*' => 'exists:rental_resale,id',
            'offplan_listings' => 'nullable|array',
            'offplan_listings.*' => 'exists:offplans,id',
            'awards' => 'nullable|array',
        ]);

        // Add conditional validation for awards
        if ($request->has('awards')) {
            foreach ($request->awards as $index => $award) {
                // Only validate if both title and year are present
                if (!empty($award['title']) && !empty($award['year'])) {
                    $validator->sometimes("awards.$index.title", 'required|string|max:255', function ($input) use ($award) {
                        return true;
                    });

                    $validator->sometimes("awards.$index.year", 'required|integer|min:1900|max:' . date('Y'), function ($input) use ($award) {
                        return true;
                    });
                }
            }
        }

        // Validate the request
        $validator->validate();

        // Handle multiple photo uploads with alt text
        $photos = [];
        if ($request->has('photo')) {
            foreach ($request->photo as $photo) {
                if (isset($photo['file'])) {
                    $path = $photo['file']->store('photos', 'public');
                    $photos[] = [
                        'file' => $path,
                        'alt' => $photo['alt'] ?? '',
                    ];
                }
            }
        }
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('developer_logos', 'public');
        }
        // Create the developer
        $developer = Developer::create([
            'title' => $request->input('title'),
            'slug' => $this->generateUniqueSlug($request->input('slug')),
            'paragraph' => $request->input('paragraph'),
            'phone' => $request->input('phone'),
            'whatsapp' => $request->input('whatsapp'),
            'photo' => json_encode($photos),
            'logo' => $logoPath,
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
        $awards = [];
    if ($request->has('awards')) {
        foreach ($request->awards as $awardData) {
            if (!empty($awardData['title']) && !empty($awardData['year'])) {
                $award = new DeveloperAward([
                    'award_title' => $awardData['title'],
                    'award_year' => $awardData['year'],
                    'award_description' => $awardData['description'] ?? null,
                ]);

                // Upload award photo if present
                if (isset($awardData['photo'])) {
                    $awardPhotoPath = $awardData['photo']->store('award_photos', 'public');
                    $award->award_photo = $awardPhotoPath;
                }

                $awards[] = $award;
            }
        }
    }

    // Save awards using sync
    $developer->awards()->saveMany($awards);

        return redirect()->route('admin.developer.list')->with('success', 'Developer created successfully!');
    }
    // Show the form for editing the specified developer
    public function edit($id)
    {
        $developer = Developer::with(['offplanListings', 'awards', 'rentalResaleListings'])->findOrFail($id);
        $offplanListings = Offplan::all(); // Fetch all offplan listings
        $rentalListings = RentalResale::all(); // Fetch all rental listings
        $awards = $developer->awards; // Fetch all awards associated with the developer
        // Decode JSON fields for the form
        $photos = json_decode($developer->photo, true) ?? [];
        $tags = json_decode($developer->tags, true) ?? [];
        $rentalListingsArray = json_decode($developer->rental_listings, true) ?? []; // Decode rental_listings JSON

        return view('admin.developer.edit', compact(
            'developer',
            'offplanListings',
            'rentalListings',
            'photos',
            'awards', // Pass all awards to the view
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
            'photo.*.file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo.*.alt' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',
            'rental_listings' => 'nullable|array',
            'rental_listings.*' => 'exists:rental_resale,id',
            'offplan_listings' => 'nullable|array',
            'offplan_listings.*' => 'exists:offplans,id',
            'awards' => 'nullable|array',
            'awards.*.title' => 'required|string|max:255',
            'awards.*.year' => 'required|integer|min:1900|max:' . date('Y'),
            'awards.*.description' => 'nullable|string',
            'awards.*.photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle multiple photo uploads with alt text
        $photos = json_decode($developer->photo, true) ?? [];
        if ($request->has('photo')) {
            foreach ($request->photo as $photo) {
                if (isset($photo['file'])) {
                    $path = $photo['file']->store('photos', 'public');
                    $photos[] = [
                        'file' => $path,
                        'alt' => $photo['alt'] ?? '',
                    ];
                }
            }
        }
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('developer_logos', 'public');
        }
        // Update the developer
        $developer->update([
            'title' => $request->input('title'),
            'slug' =>  $this->generateUniqueSlug($request->input('slug')),
            'paragraph' => $request->input('paragraph'),
            'phone' => $request->input('phone'),
            'whatsapp' => $request->input('whatsapp'),
            'photo' => json_encode($photos),
            'logo' => $logoPath,
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
        if ($request->has('awards')) {
            // Delete existing awards
            $developer->awards()->delete();

            // Add new awards
            foreach ($request->awards as $awardData) {
                if (!empty($awardData['title']) && !empty($awardData['year'])) {
                    $award = new DeveloperAward([
                        'award_title' => $awardData['title'],
                        'award_year' => $awardData['year'],
                        'award_description' => $awardData['description'] ?? null,
                    ]);

                    // Handle award photo upload
                    if (isset($awardData['photo'])) {
                        $awardPhotoPath = $awardData['photo']->store('award_photos', 'public');
                        $award->award_photo = $awardPhotoPath;
                    }

                    $developer->awards()->save($award);
                }
            }
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
    public function deletePhoto(Request $request)
    {
        $request->validate([
            'developer_id' => 'required|exists:developers,id', // Ensure a valid developer ID is provided
            'photo_path' => 'required|string', // Ensure a valid photo path is provided
        ]);

        $developerId = $request->input('developer_id');
        $photoPath = $request->input('photo_path');

        // Delete the file from storage
        if (Storage::exists('storage/' . $photoPath)) {
            Storage::delete('storage/' . $photoPath);
        }

        // Update the database to remove the photo from the developer's data
        $developer = Developer::findOrFail($developerId);
        $photos = json_decode($developer->photo, true) ?? [];

        // Find and remove the photo from the array
        $photos = array_filter($photos, function($photo) use ($photoPath) {
            return $photo['file'] !== $photoPath;
        });

        // Update the developer record
        $developer->photo = json_encode(array_values($photos));
        $developer->save();

        return response()->json(['success' => true]);
    }
    public function deleteAward(Request $request)
{
    $request->validate([
        'award_id' => 'required|exists:developer_awards,id', // Ensure a valid award ID is provided
    ]);

    $awardId = $request->input('award_id');

    // Find and delete the award
    $award = DeveloperAward::findOrFail($awardId);

    // Delete the award photo from storage if it exists
    if ($award->award_photo && Storage::exists('public/' . $award->award_photo)) {
        Storage::delete('public/' . $award->award_photo);
    }

    $award->delete();

    return response()->json(['success' => true]);
}
}
