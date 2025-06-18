<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Developer;
use App\Models\DeveloperAward;
use App\Models\Offplan;
use App\Models\RentalResale;
use App\Traits\HandlesMetaData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DeveloperController extends Controller
{
    use HandlesMetaData;

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
        $validatedData = $request->validate(array_merge([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:developers,slug',
            'paragraph' => 'required|string',
            'phone' => 'required|string|max:20',
            'whatsapp' => 'required|string|max:20',
            'photo.*.file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo.*.alt' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo_alt' => 'required|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',
            'rental_listings' => 'nullable|array',
            'rental_listings.*' => 'exists:rental_resale,id',
            'offplan_listings' => 'nullable|array',
            'offplan_listings.*' => 'exists:offplans,id',
            'awards' => 'nullable|array',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $this->getMetadataValidationRules()));

        if ($request->hasFile('logo')) {
            $validatedData['logo'] = $request->file('logo')->store('developer_logos', 'public');
        }

        if ($request->hasFile('banner_image')) {
            $validatedData['banner_image'] = $request->file('banner_image')->store('developer_banners', 'public');
        }

        // Handle multiple photo uploads with alt text
        $photos = [];
        if ($request->has('photo')) {
            foreach ($request->photo as $index => $photo) {
                if (isset($photo['file'])) {
                    $path = $photo['file']->store('photos', 'public');
                    $photos[] = [
                        'file' => $path,
                        'alt' => $photo['alt'] ?? '',
                    ];
                }
            }
        }

        // Create the developer
        $developer = Developer::create(array_merge($validatedData, [
            'photo' => json_encode($photos),
        ]));

        // Handle awards
        if ($request->has('awards')) {
            foreach ($request->awards as $awardData) {
                if (! empty($awardData['title']) && ! empty($awardData['year'])) {
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

                    $developer->awards()->save($award);

                    // Save award photo alt text
                    if (isset($awardData['photo_alt']) && $award->award_photo) {
                        $award->photoAlt()->create([
                            'photo_path' => $award->award_photo,
                            'alt_text' => $awardData['photo_alt']
                        ]);
                    }
                }
            }
        }

        // Handle metadata
        $this->handleMetadata($request, $developer);

        return redirect()->route('admin.developer.list')->with('success', 'Developer created successfully!');
    }

    // Show the form for editing the specified developer
    public function edit($id)
    {
        $developer = Developer::with(['offplanListings', 'awards', 'rentalResaleListings'])->findOrFail($id);
        $offplanListings = Offplan::all(); // Fetch all offplan listings
        $rentalListings = RentalResale::all(); // Fetch all rental listings
        $awards = $developer->awards; // Fetch all awards associated with the developer
        // Get the arrays directly from the model since they're already cast
        $photos = json_decode($developer->photo, true) ?? [];
        $tags = $developer->tags ?? [];
        $rentalListingsArray = $developer->rental_listings ?? []; // Get rental_listings array directly

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

        $validatedData = $request->validate(array_merge([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:developers,slug,'.$developer->id,
            'paragraph' => 'required|string',
            'phone' => 'required|string|max:20',
            'whatsapp' => 'required|string|max:20',
            'photo.*.file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo.*.alt' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo_alt' => 'required|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',
            'rental_listings' => 'nullable|array',
            'rental_listings.*' => 'exists:rental_resale,id',
            'offplan_listings' => 'nullable|array',
            'offplan_listings.*' => 'exists:offplans,id',
            'awards' => 'nullable|array',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $this->getMetadataValidationRules()));

        if ($request->hasFile('logo')) {
            if ($developer->logo) {
                Storage::disk('public')->delete($developer->logo);
            }
            $validatedData['logo'] = $request->file('logo')->store('developer_logos', 'public');
        }

        if ($request->hasFile('banner_image')) {
            if ($developer->banner_image) {
                Storage::disk('public')->delete($developer->banner_image);
            }
            $validatedData['banner_image'] = $request->file('banner_image')->store('developer_banners', 'public');
        }

        // Handle multiple photo uploads with alt text
        $photos = is_array($developer->photo) ? $developer->photo : (json_decode($developer->photo, true) ?? []);
        if ($request->has('photo')) {
            $newPhotos = [];
            foreach ($request->photo as $index => $photo) {
                if (isset($photo['file'])) {
                    // New photo upload
                    $path = $photo['file']->store('photos', 'public');
                    $newPhotos[] = [
                        'file' => $path,
                        'alt' => $photo['alt'] ?? '',
                    ];
                } else if (isset($photo['alt'])) {
                    // Only updating alt text for existing photo
                    if (isset($photos[$index])) {
                        $newPhotos[] = [
                            'file' => $photos[$index]['file'],
                            'alt' => $photo['alt'],
                        ];
                    }
                }
            }
            $photos = $newPhotos;
        }

        // Handle awards update
        if ($request->has('awards')) {
            // Get existing awards
            $existingAwards = $developer->awards->keyBy('id');

            foreach ($request->awards as $index => $awardData) {
                if (!empty($awardData['title']) && !empty($awardData['year'])) {
                    if (!empty($awardData['id']) && $existingAwards->has($awardData['id'])) {
                        // Update existing award
                        $award = $existingAwards->get($awardData['id']);
                        $award->award_title = $awardData['title'];
                        $award->award_year = $awardData['year'];
                        $award->award_description = $awardData['description'] ?? null;

                        // Only update photo if a new one is actually uploaded
                        if ($request->hasFile('awards.' . $index . '.photo')) {
                            // Delete old photo if exists
                            if ($award->award_photo && Storage::exists('public/' . $award->award_photo)) {
                                Storage::delete('public/' . $award->award_photo);
                            }
                            $awardPhotoPath = $request->file('awards.' . $index . '.photo')->store('award_photos', 'public');
                            $award->award_photo = $awardPhotoPath;
                        }

                        $award->save();

                        // Update award photo alt text
                        if (isset($awardData['photo_alt'])) {
                            if ($award->photoAlt) {
                                if ($award->award_photo) {
                                    $award->photoAlt->update([
                                        'alt_text' => $awardData['photo_alt']
                                    ]);
                                } else {
                                    $award->photoAlt->delete();
                                }
                            } else if ($award->award_photo) {
                                $award->photoAlt()->create([
                                    'photo_path' => $award->award_photo,
                                    'alt_text' => $awardData['photo_alt']
                                ]);
                            }
                        }

                        $existingAwards->forget($awardData['id']);
                    } else {
                        // Create new award
                        $award = new DeveloperAward([
                            'award_title' => $awardData['title'],
                            'award_year' => $awardData['year'],
                            'award_description' => $awardData['description'] ?? null,
                        ]);

                        if ($request->hasFile('awards.' . $index . '.photo')) {
                            $awardPhotoPath = $request->file('awards.' . $index . '.photo')->store('award_photos', 'public');
                            $award->award_photo = $awardPhotoPath;
                        }

                        $developer->awards()->save($award);

                        // Save award photo alt text
                        if (isset($awardData['photo_alt']) && $award->award_photo) {
                            $award->photoAlt()->create([
                                'photo_path' => $award->award_photo,
                                'alt_text' => $awardData['photo_alt']
                            ]);
                        }
                    }
                }
            }

            // Delete awards that were not included in the update
            if ($existingAwards->isNotEmpty()) {
                foreach ($existingAwards as $award) {
                    if ($award->award_photo && Storage::exists('public/' . $award->award_photo)) {
                        Storage::delete('public/' . $award->award_photo);
                    }
                    $award->delete();
                }
            }
        }

        // Update the developer
        $developer->update(array_merge($validatedData, [
            'photo' => json_encode($photos),
        ]));

        // Sync rental and off-plan listings
        $developer->rentalResaleListings()->sync($request->input('rental_listings', []));
        $developer->offplanListings()->sync($request->input('offplan_listings', []));

        // Handle metadata
        $this->handleMetadata($request, $developer);

        return redirect()->route('admin.developer.list')->with('success', 'Developer updated successfully!');
    }

    // Remove the specified developer from storage
    public function destroy($id)
    {
        $developer = Developer::findOrFail($id);
        $developer->delete();

        // Delete metadata images if they exist
        if ($developer->metadata?->og_image) {
            Storage::disk('public')->delete($developer->metadata->og_image);
        }
        if ($developer->metadata?->twitter_image) {
            Storage::disk('public')->delete($developer->metadata->twitter_image);
        }

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
        if (Storage::exists('storage/'.$photoPath)) {
            Storage::delete('storage/'.$photoPath);
        }

        // Update the database to remove the photo from the developer's data
        $developer = Developer::findOrFail($developerId);
        $photos = is_array($developer->photo) ? $developer->photo : (json_decode($developer->photo, true) ?? []);

        // Find and remove the photo from the array
        $photos = array_filter($photos, function ($photo) use ($photoPath) {
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
        if ($award->award_photo && Storage::exists('public/'.$award->award_photo)) {
            Storage::delete('public/'.$award->award_photo);
        }

        $award->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Delete the OG image for the specified developer.
     */
    public function deleteOgImage(Developer $developer)
    {
        if ($developer->metadata && $developer->metadata->og_image) {
            Storage::disk('public')->delete($developer->metadata->og_image);
            $developer->metadata->update(['og_image' => null]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'No OG image found.'], 404);
    }

    /**
     * Delete the Twitter image for the specified developer.
     */
    public function deleteTwitterImage(Developer $developer)
    {
        if ($developer->metadata && $developer->metadata->twitter_image) {
            Storage::disk('public')->delete($developer->metadata->twitter_image);
            $developer->metadata->update(['twitter_image' => null]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'No Twitter image found.'], 404);
    }
}
