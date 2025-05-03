<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::latest()->paginate(10);

        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'url' => 'nullable|url',
            'alt' => 'nullable|max:255',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('partners', 'public');
            $validated['image'] = $path;
        }

        Partner::create($validated);

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partner created successfully.');
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'url' => 'nullable|url',
            'alt' => 'nullable|max:255',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($partner->image) {
                Storage::disk('public')->delete($partner->image);
            }
            $path = $request->file('image')->store('partners', 'public');
            $validated['image'] = $path;
        }

        $partner->update($validated);

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partner updated successfully.');
    }

    public function destroy(Partner $partner)
    {
        if ($partner->image) {
            Storage::disk('public')->delete($partner->image);
        }
        $partner->delete();

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partner deleted successfully.');
    }

    public function deleteImage($id)
    {
        try {
            $partner = Partner::findOrFail($id);

            // Delete the image file from storage
            if ($partner->image) {
                Storage::disk('public')->delete($partner->image);
            }

            // Update the partner record
            $partner->update(['image' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting image: ' . $e->getMessage()
            ], 500);
        }
    }
}
