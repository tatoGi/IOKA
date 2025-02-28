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
        $developers = Developer::all();

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
        $developer = new Developer;
        $developer->title = $request->title;
        $developer->paragraph = $request->paragraph;
        $developer->phone = $request->phone;
        $developer->whatsapp = $request->whatsapp;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $developer->photo = $path;
        }

        $developer->rental_listings = $request->rental_listings;
        $developer->offplan_listings = $request->offplan_listings;

        $developer->save();

        // Handle developer awards
        if ($request->award_title || $request->award_year || $request->award_description || $request->hasFile('award_photo')) {
            $award = new DeveloperAward;
            $award->developer_id = $developer->id;
            $award->award_title = $request->award_title;
            $award->award_year = $request->award_year;
            $award->award_description = $request->award_description;

            if ($request->hasFile('award_photo')) {
                $awardPath = $request->file('award_photo')->store('awards', 'public');
                $award->photo = $awardPath;
            }

            $award->save();
        }

        return redirect()->route('admin.developer.list');
    }

    // Show the form for editing the specified developer
    public function edit($id)
    {
        $developer = Developer::findOrFail($id);
        $rentalListings = RentalResale::where('property_type', 'rental')->get();
        $resaleListings = RentalResale::where('property_type', 'resale')->get();
        $offplanListings = Offplan::all();

        return view('admin.developer.edit', compact('developer', 'rentalListings', 'resaleListings', 'offplanListings'));
    }

    // Update the specified developer in storage
    public function update(Request $request, $id)
    {
        $developer = Developer::findOrFail($id);
        $developer->title = $request->title;
        $developer->paragraph = $request->paragraph;
        $developer->phone = $request->phone;
        $developer->whatsapp = $request->whatsapp;

        if ($request->hasFile('photo')) {
            if ($developer->photo) {
                Storage::disk('public')->delete($developer->photo);
            }
            $path = $request->file('photo')->store('photos', 'public');
            $developer->photo = $path;
        }

        $developer->rental_listings = $request->rental_listings;
        $developer->offplan_listings = $request->offplan_listings;

        $developer->save();

        // Handle developer awards
        if ($request->award_title || $request->award_year || $request->award_description || $request->hasFile('award_photo')) {
            $award = DeveloperAward::where('developer_id', $developer->id)->first() ?? new DeveloperAward;
            $award->developer_id = $developer->id;
            $award->title = $request->award_title;
            $award->year = $request->award_year;
            $award->description = $request->award_description;

            if ($request->hasFile('award_photo')) {
                if ($award->photo) {
                    Storage::disk('public')->delete($award->photo);
                }
                $awardPath = $request->file('award_photo')->store('awards', 'public');
                $award->photo = $awardPath;
            }

            $award->save();
        }

        return redirect()->route('admin.developer.index');
    }

    // Remove the specified developer from storage
    public function destroy($id)
    {
        $developer = Developer::findOrFail($id);
        $developer->delete();

        return redirect()->route('admin.developer.list');
    }
}
