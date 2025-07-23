@extends('admin.layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Edit Offplan</h1>
        <form action="{{ route('admin.offplan.update', $offplan->id) }}" method="POST" enctype="multipart/form-data"
            class="shadow p-4 rounded bg-light">
            @csrf
            @method('PUT')
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Nav tabs -->
            <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#basic-info" role="tab">Basic Information</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#metadata-tab" role="tab">Meta Data</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Basic Info Tab -->
                <div class="tab-pane active" id="basic-info" role="tabpanel">
                    <div class="container">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="{{ $offplan->title }}" required>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug" class="form-label">slug</label>
                                <input type="text" class="form-control" id="slug" name="slug"
                                    value="{{ $offplan->slug }}">
                            </div>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Amount (in dollars)</label>
                                        <input type="text"   class="form-control amount" id="amount"
                                            name="amount" value="{{ $offplan->amount }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="amount_dirhams" class="form-label">Amount (in Dirhams)</label>
                                        <input type="text"  class="form-control amount_dirhams"
                                            id="amount_dirhams" name="amount_dirhams" value="{{ $offplan->amount_dirhams }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control editor" id="description" name="description" required>{{ $offplan->description }}</textarea>
                        </div>
                        <div class="form-group mt-2">
                            <label for="features">Features</label>
                            <div id="features_repeater">
                                {{-- {{ dd($offplan) }} --}}
                                @php
                                    $features_items = $offplan->features;
                                    if (is_string($features_items)) {
                                        $features_items = json_decode($features_items, true);
                                    }
                                    if (!is_array($features_items)) {
                                        $features_items = [];
                                    }
                                @endphp
                                @foreach ($features_items as $index => $feature)
                                    <div class="features_item">
                                        <input type="text" class="form-control mb-2" name="features[{{ $index }}]"
                                            value="{{ $feature }}" placeholder="Feature">
                                        <button type="button" class="btn btn-danger btn-sm remove-feature">Remove</button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary" id="add_feature">Add More</button>
                        </div>
                       
                        <div class="form-group mt-2">
                            <label for="amenities">Amenities</label>
                            <div id="amenities_repeater">
                                @php
                                    $amenities = $offplan->amenities;
                                    
                                    if (is_string($amenities)) {
                                        $amenities = json_decode($amenities, true);
                                    }
                                    if (!is_array($amenities)) {
                                        $amenities = [];
                                    }
                                @endphp
                                @foreach ($amenities as $index => $amenity)
                                    @php
                                        $amenity = is_array($amenity) ? $amenity : ['name' => $amenity, 'icon' => ''];
                                        $icon = $amenity['icon'] ?? '';
                                        $amenityName = $amenity['name'] ?? '';
                                    @endphp
                                    <div class="amenities_item mb-3 p-3 border rounded">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label class="form-label">Amenity Name</label>
                                                <input type="text" class="form-control mb-2" name="amenities[{{ $index }}]" value="{{ $amenityName }}" placeholder="Enter amenity name" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Icon (SVG recommended)</label>
                                                <input type="file" class="form-control mb-2 file-upload" name="amenities_icon[{{ $index }}]" accept="image/svg+xml,image/png,image/jpeg" data-index="{{ $index }}">
                                                <div class="icon-preview mt-2" id="icon-preview-{{ $index }}">
                                                    @if(!empty($icon))
                                                        <div class="existing-icon">
                                                            <small>Current Icon:</small>
                                                            @if(Storage::disk('public')->exists($icon))
                                                                <img src="{{ asset('storage/' . $icon) }}" alt="{{ $amenityName }}" style="max-width: 30px; max-height: 30px;" class="ms-2">
                                                            @else
                                                                <span class="text-muted ms-2">[Icon not found]</span>
                                                            @endif
                                                            <button type="button" class="btn btn-sm btn-outline-danger remove-icon ms-2" data-icon-path="{{ $icon }}">
                                                                <i class="fas fa-trash"></i> Remove
                                                            </button>
                                                            <input type="hidden" name="existing_amenities_icon[{{ $index }}]" value="{{ $icon }}">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm remove-amenities">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary mt-2" id="add_amenities">
                                <i class="fas fa-plus"></i> Add Amenity
                            </button>
                        </div>
                        <div class="form-group">
                            <label for="map_location">Map Location</label>
                            <input type="text" class="form-control" id="map_location" name="map_location"
                                value="{{ $offplan->map_location }}">
                        </div>


                        <div class="form-group">
                            <label for="near_by">Near By</label>
                            <div id="near_by_repeater">
                                @php
                                    $near_by_items = $offplan->near_by;
                                    if (is_string($near_by_items)) {
                                        $near_by_items = json_decode($near_by_items, true);
                                    }
                                    if (!is_array($near_by_items)) {
                                        $near_by_items = [];
                                    }
                                @endphp
                                @foreach ($near_by_items as $index => $nearBy)
                                    <div class="near_by_item">
                                        <input type="text" class="form-control mb-2"
                                            name="near_by[{{ $index }}][title]" value="{{ $nearBy['title'] }}"
                                            placeholder="Title">
                                        <input type="number" step="0.1" class="form-control mb-2"
                                            name="near_by[{{ $index }}][distance]"
                                            value="{{ $nearBy['distance'] }}" placeholder="Distance (e.g., 4.5)">
                                        <button type="button"
                                            class="btn btn-danger btn-sm remove-near-by">Remove</button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary" id="add_near_by">Add More</button>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="main_photo">Main Photo(737 x 461)</label>
                                        <input type="file" class="form-control" id="main_photo" name="main_photo"
                                            accept="image/*">
                                        <input type="text" class="form-control mt-2" name="main_photo_alt"
                                            placeholder="Alt text for main photo"
                                            value="{{ $offplan->alt_texts['main_photo'] ?? '' }}">
                                        <div id="main_photo_preview" class="uploaded-files">
                                            @if ($offplan->main_photo)
                                                <div class="uploaded-file">
                                                    <img src="{{ asset('storage/' . $offplan->main_photo) }}"
                                                        alt="{{ $offplan->alt_texts['main_photo'] ?? 'Main Property Photo - ' . $offplan->title }}"
                                                        class="img-thumbnail" style="max-width: 100px;">
                                                    <button type="button" class="btn btn-danger btn-sm remove-image"
                                                        data-id="{{ $offplan->id }}" data-type="main_photo"
                                                        data-path="{{ $offplan->main_photo }}">Delete</button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="main_photo">Banner (1280 x 461)</label>
                                        <input type="file" class="form-control" id="banner_photo"
                                            name="banner_photo">
                                        <input type="text" class="form-control mt-2" name="banner_photo_alt"
                                            placeholder="Alt text for banner photo"
                                            value="{{ $offplan->alt_texts['banner_photo'] ?? '' }}">
                                        <div id="main_photo_preview" class="uploaded-files">
                                            @if ($offplan->banner_photo)
                                                <div class="uploaded-file">
                                                    <img src="{{ asset('storage/' . $offplan->banner_photo) }}"
                                                        alt="{{ $offplan->alt_texts['banner_photo'] ?? 'Property Banner - ' . $offplan->title }}"
                                                        class="img-thumbnail" style="max-width: 100px;">
                                                    <button type="button" class="btn btn-danger btn-sm remove-image"
                                                        data-id="{{ $offplan->id }}" data-type="banner_photo"
                                                        data-path="{{ $offplan->banner_photo }}">Delete</button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="banner_title">Banner Title</label>
                                    <input type="text" class="form-control" id="banner_title" name="banner_title"
                                        value="{{ $offplan->banner_title }}" placeholder="Enter banner title">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="form-group">
                                        <label for="exterior_gallery">Exterior Gallery(372 x 272)</label>
                                        <input type="file" class="form-control" id="exterior_gallery"
                                            name="exterior_gallery[]" multiple accept="image/*">
                                        <div id="exterior_gallery_preview" class="uploaded-files gallery-container" style="border: 1px solid #ddd; border-radius: 5px; padding: 15px; margin-top: 10px;">
                                            @php
                                                $exterior_gallery_items = $offplan->exterior_gallery;
                                                if (is_string($exterior_gallery_items)) {
                                                    $exterior_gallery_items = json_decode(
                                                        $exterior_gallery_items,
                                                        true,
                                                    );
                                                }
                                                if (!is_array($exterior_gallery_items)) {
                                                    $exterior_gallery_items = []; // Default to empty array if not an array
                                                }
                                            @endphp
                                            <div class="container">
                                                <div class="row">
                                                    @if (!empty($exterior_gallery_items))
                                                        @foreach ($exterior_gallery_items as $index => $photo)
                                                            <div class="col-4 mb-3">
                                                                <div class="uploaded-file" style="padding: 10px;">
                                                                    <img src="{{ asset('storage/' . $photo) }}"
                                                                        alt="{{ $offplan->alt_texts['exterior_gallery'][$index] ?? 'Exterior Photo ' . ($index + 1) . ' - ' . $offplan->title }}"
                                                                        class="img-thumbnail" style="width: 100%; height: auto;">
                                                                    <input type="text" class="form-control mt-2"
                                                                        name="exterior_gallery_alt[]"
                                                                        placeholder="Alt text for exterior photo {{ $index + 1 }}"
                                                                        value="{{ $offplan->alt_texts['exterior_gallery'][$index] ?? '' }}">
                                                                    <button type="button" class="btn btn-danger btn-sm remove-image mt-2 w-100"
                                                                        data-id="{{ $offplan->id }}" data-type="exterior_gallery"
                                                                        data-path="{{ $photo }}">Delete</button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <p>No exterior gallery photos available.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="form-group">
                                        <label for="interior_gallery">Interior Gallery(372 x 272)</label>
                                        <input type="file" class="form-control" id="interior_gallery"
                                            name="interior_gallery[]" multiple accept="image/*">
                                        <div id="interior_gallery_preview" class="uploaded-files gallery-container" style="border: 1px solid #ddd; border-radius: 5px; padding: 15px; margin-top: 10px;">
                                            @php
                                                $interior_gallery_items = $offplan->interior_gallery;
                                                if (is_string($interior_gallery_items)) {
                                                    $interior_gallery_items = json_decode(
                                                        $interior_gallery_items,
                                                        true,
                                                    );
                                                }
                                                if (!is_array($interior_gallery_items)) {
                                                    $interior_gallery_items = []; // Default to empty array if not an array
                                                }
                                            @endphp
                                            <div class="container">
                                                <div class="row">
                                                    @if (!empty($interior_gallery_items))
                                                        @foreach ($interior_gallery_items as $index => $photo)
                                                            <div class="col-4 mb-3">
                                                                <div class="uploaded-file" style="padding: 10px;">
                                                                    <img src="{{ asset('storage/' . $photo) }}"
                                                                        alt="{{ $offplan->alt_texts['interior_gallery'][$index] ?? 'Interior Photo ' . ($index + 1) . ' - ' . $offplan->title }}"
                                                                        class="img-thumbnail" style="width: 100%; height: auto;">
                                                                    <input type="text" class="form-control mt-2"
                                                                        name="interior_gallery_alt[]"
                                                                        placeholder="Alt text for interior photo {{ $index + 1 }}"
                                                                        value="{{ $offplan->alt_texts['interior_gallery'][$index] ?? '' }}">
                                                                    <button type="button" class="btn btn-danger btn-sm remove-image mt-2 w-100"
                                                                        data-id="{{ $offplan->id }}" data-type="interior_gallery"
                                                                        data-path="{{ $photo }}">Delete</button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <p>No interior gallery photos available.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="property_type">Property Type</label>
                            <select class="form-control" id="property_type" name="property_type">
                                <option value="Villa" {{ $offplan->property_type == 'Villa' ? 'selected' : '' }}>Villa
                                </option>
                                <option value="Townhouse" {{ $offplan->property_type == 'Townhouse' ? 'selected' : '' }}>
                                    Townhouse
                                </option>
                                <option value="Apartment" {{ $offplan->property_type == 'Apartment' ? 'selected' : '' }}>
                                    Apartment
                                </option>
                                <option value="Land" {{ $offplan->property_type == 'Land' ? 'selected' : '' }}>Land
                                </option>
                                <option value="Full Building"
                                    {{ $offplan->property_type == 'Full Building' ? 'selected' : '' }}>Full
                                    Building</option>
                                <option value="Commercial"
                                    {{ $offplan->property_type == 'Commercial' ? 'selected' : '' }}>Commercial
                                </option>
                            </select>
                        </div>
                        <div class="container mt-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bathroom">Bathroom</label>
                                        <input type="number" class="form-control" id="bathroom" name="bathroom"
                                            value="{{ $offplan->bathroom }}">
                                    </div>
                                </div>
                                <div class="col-md-3">

                                    <div class="form-group">
                                        <label for="bedroom">Bedroom</label>
                                        <input type="number" class="form-control" id="bedroom" name="bedroom"
                                            value="{{ $offplan->bedroom }}">
                                    </div>
                                </div>
                                <div class="col-md-3">

                                    <div class="form-group">
                                        <label for="garage">Garage</label>
                                        <input type="number" class="form-control" id="garage" name="garage"
                                            value="{{ $offplan->garage }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="sq_ft">Square Feet</label>
                                        <input type="number" class="form-control" id="sq_ft" name="sq_ft"
                                            value="{{ $offplan->sq_ft }}">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group mt-3">
                            <label for="qr_title">QR Title</label>
                            <input type="text" class="form-control" id="qr_title" name="qr_title"
                                value="{{ $offplan->qr_title }}">
                        </div>
                        <div class="form-group mt-3">
                            <label for="qr_photo">QR Photo(149 x 149)</label>
                            <input type="file" class="form-control" id="qr_photo" name="qr_photo" accept="image/*">
                            <input type="text" class="form-control mt-2" name="qr_photo_alt"
                                placeholder="Alt text for QR photo" value="{{ $offplan->alt_texts['qr_photo'] ?? '' }}">
                            <div id="qr_photo_preview" class="uploaded-files">
                                @if ($offplan->qr_photo)
                                    <div class="uploaded-file">
                                        <img src="{{ asset('storage/' . $offplan->qr_photo) }}"
                                            alt="{{ $offplan->alt_texts['qr_photo'] ?? 'QR Code - ' . $offplan->title }}"
                                            class="img-thumbnail" style="max-width: 100px;">
                                        <button type="button" class="btn btn-danger btn-sm remove-image"
                                            data-id="{{ $offplan->id }}" data-type="qr_photo"
                                            data-path="{{ $offplan->qr_photo }}">Delete</button>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="qr_text">QR Text</label>
                            <textarea class="form-control editor" id="qr_text" name="qr_text">{{ $offplan->qr_text }}</textarea>
                        </div>
                        <div class="form-group mt-3">
                            <label for="download_brochure">Download Brochure</label>
                            <input type="text" class="form-control" id="download_brochure" name="download_brochure"
                                value="{{ $offplan->download_brochure }}">
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group mt-3">
                                        <label for="agent_title">Agent Title</label>
                                        <input type="text" class="form-control" id="agent_title" name="agent_title"
                                            value="{{ $offplan->agent_title }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mt-3">
                                        <label for="agent_status">Agent Status</label>
                                        <input type="text" class="form-control" id="agent_status" name="agent_status"
                                            value="{{ $offplan->agent_status }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mt-3">
                                        <label for="agent_telephone">Agent Telephone</label>
                                        <input type="text" class="form-control" id="agent_telephone"
                                            name="agent_telephone" value="{{ $offplan->agent_telephone }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mt-3">
                                        <label for="agent_whatsapp">Agent WhatsApp</label>
                                        <input type="text" class="form-control" id="agent_whatsapp"
                                            name="agent_whatsapp" value="{{ $offplan->agent_whatsapp }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mt-3">
                                        <label for="agent_linkedin">Agent LinkedIn</label>
                                        <input type="text" class="form-control" id="agent_linkedin"
                                            name="agent_linkedin" value="{{ $offplan->agent_linkedin }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mt-3">
                                        <label for="agent_email">Agent Email</label>
                                        <input type="email" class="form-control" id="agent_email" name="agent_email"
                                            value="{{ $offplan->agent_email }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label for="agent_languages">Agent Languages</label>
                            <div id="agent_languages_repeater">
                                @php
                                    $agent_languages_items = $offplan->agent_languages;
                                    if (is_string($agent_languages_items)) {
                                        $agent_languages_items = json_decode($agent_languages_items, true);
                                    }
                                    if (!is_array($agent_languages_items)) {
                                        $agent_languages_items = [];
                                    }
                                @endphp
                                @if ($agent_languages_items)
                                    @foreach ($agent_languages_items as $index => $language)
                                        <div class="agent_languages_item">
                                            <input type="text" class="form-control mb-2"
                                                name="agent_languages[{{ $index }}]" value="{{ $language }}"
                                                placeholder="Language">
                                            <button type="button"
                                                class="btn btn-danger btn-sm remove-agent-language">Remove</button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn btn-secondary" id="add_agent_language">Add More</button>
                        </div>

                        <div class="form-group mt-3">
                            <label for="agent_image">Agent Image(92 x 92)</label>
                            <input type="file" class="form-control" id="agent_image" name="agent_image"
                                accept="image/*">
                            <input type="text" class="form-control mt-2" name="agent_image_alt"
                                placeholder="Alt text for agent photo"
                                value="{{ $offplan->alt_texts['agent_image'] ?? '' }}">
                            <div id="agent_image_preview" class="uploaded-files">
                                @if ($offplan->agent_image)
                                    <div class="uploaded-file">
                                        <img src="{{ asset('storage/' . $offplan->agent_image) }}"
                                            alt="{{ $offplan->alt_texts['agent_image'] ?? 'Agent Photo - ' . $offplan->agent_title }}"
                                            class="img-thumbnail" style="max-width: 100px;">
                                        <button type="button" class="btn btn-danger btn-sm remove-image"
                                            data-id="{{ $offplan->id }}" data-type="agent_image"
                                            data-path="{{ $offplan->agent_image }}">Delete</button>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="location_id">Locations</label>
                            <select name="location_id" id="location_id" class="form-control select2">
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}"
                                        {{ $selectedLocation == $location->id ? 'selected' : '' }}>
                                        {{ $location->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Meta Data Tab -->
                <div class="tab-pane" id="metadata-tab" role="tabpanel">
                    <x-metadata-form :model="$offplan" />
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Update Offplan</button>
            </div>
        </form>
    </div>

    <script>
        function handleFileInput(event, previewId) {
            const files = event.target.files;
            const preview = document.getElementById(previewId);

            // Clear the preview and create container structure
            preview.innerHTML = '';

            // Create container and row for grid layout
            const container = document.createElement('div');
            container.classList.add('container');

            const row = document.createElement('div');
            row.classList.add('row');
            container.appendChild(row);

            // Process each file
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function(e) {
                    // Create column for grid layout
                    const colDiv = document.createElement('div');
                    colDiv.classList.add('col-4', 'mb-3');

                    // Create file container with proper styling
                    const fileDiv = document.createElement('div');
                    fileDiv.classList.add('uploaded-file');
                    fileDiv.style.padding = '10px';

                    fileDiv.innerHTML = `
                        <img src="${e.target.result}" alt="${file.name}" class="img-thumbnail" style="width: 100%; height: auto;">
                        <input type="text" class="form-control mt-2" name="${previewId.replace('_preview', '')}_alt[]" placeholder="Alt text for photo ${i+1}">
                    `;

                    // Add to column and row
                    colDiv.appendChild(fileDiv);
                    row.appendChild(colDiv);
                };

                reader.readAsDataURL(file);
            }

            // Add everything to the preview
            preview.appendChild(container);
        }

        document.getElementById('main_photo').addEventListener('change', function(event) {
            handleFileInput(event, 'main_photo_preview');
        });
        document.getElementById('banner_photo').addEventListener('change', function(event) {
            handleFileInput(event, 'main_banner_preview');
        });
        document.getElementById('exterior_gallery').addEventListener('change', function(event) {
            handleFileInput(event, 'exterior_gallery_preview');
        });

        document.getElementById('interior_gallery').addEventListener('change', function(event) {
            handleFileInput(event, 'interior_gallery_preview');
        });

        document.getElementById('qr_photo').addEventListener('change', function(event) {
            handleFileInput(event, 'qr_photo_preview');
        });

        document.getElementById('agent_image').addEventListener('change', function(event) {
            handleFileInput(event, 'agent_image_preview');
        });

        document.getElementById('add_feature').addEventListener('click', function() {
            var repeater = document.getElementById('features_repeater');
            var index = repeater.children.length;
            var newItem = document.createElement('div');
            newItem.classList.add('features_item');
            newItem.innerHTML = `
            <input type="text" class="form-control mb-2" name="features[${index}]" placeholder="Feature">
            <button type="button" class="btn btn-danger btn-sm remove-feature">Remove</button>
        `;
            repeater.appendChild(newItem);
        });
        // Function to handle file input changes
        function handleFileInputChange(input) {
            const file = input.files[0];
            const previewDiv = input.closest('.row').querySelector('.icon-preview');
            const hiddenInput = input.closest('.amenities_item').querySelector('input[type="hidden"][name^="existing_amenities_icon"]');
            
            // Clear any existing preview
            if (previewDiv) {
                previewDiv.innerHTML = '';
            }
            
            // If no file was selected, clear any existing preview and return
            if (!file) {
                if (hiddenInput) {
                    hiddenInput.value = '';
                }
                return;
            }
            
            // If there was a previous icon, clear any deletion markers for it
            if (hiddenInput && hiddenInput.value) {
                const deleteInputs = document.querySelectorAll('input[name="deleted_amenities_icon[]"][value="' + hiddenInput.value + '"]');
                deleteInputs.forEach(deleteInput => {
                    deleteInput.remove();
                });
                console.log('Cleared deletion marker for previous icon');
                
                // Mark the old icon for deletion
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'deleted_amenities_icon[]';
                deleteInput.value = hiddenInput.value;
                input.closest('form').appendChild(deleteInput);
                
                // Clear the hidden input value
                hiddenInput.value = '';
            }
            
            // Clear any removal markers for this input
            const removeMarkers = input.closest('.amenities_item').querySelectorAll('input[name^="removed_amenities_icon"]');
            removeMarkers.forEach(marker => marker.remove());

            // Create preview
            const reader = new FileReader();
            reader.onload = function(e) {
                if (!previewDiv) return;
                
                // Clear any existing previews first
                previewDiv.innerHTML = '';
                
                const preview = document.createElement('div');
                preview.className = 'mt-2';
                preview.innerHTML = `
                    <small>New Icon:</small>
                    <img src="${e.target.result}" alt="Preview" style="max-width: 30px; max-height: 30px;" class="ms-2">
                    <button type="button" class="btn btn-sm btn-outline-danger ms-2 remove-new-icon">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                `;
                
                // Add event listener to the remove button
                const removeBtn = preview.querySelector('.remove-new-icon');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Clear the file input
                        input.value = '';
                        
                        // Clear the preview
                        if (previewDiv) {
                            previewDiv.innerHTML = '';
                        }
                        
                        // If there was a previous icon, restore the deletion marker
                        if (hiddenInput && hiddenInput.dataset.originalValue) {
                            const deleteInput = document.createElement('input');
                            deleteInput.type = 'hidden';
                            deleteInput.name = 'deleted_amenities_icon[]';
                            deleteInput.value = hiddenInput.dataset.originalValue;
                            input.closest('form').appendChild(deleteInput);
                            
                            // Restore the original value
                            hiddenInput.value = hiddenInput.dataset.originalValue;
                            delete hiddenInput.dataset.originalValue;
                        }
                        
                        console.log('New icon removed');
                    });
                }
                
                previewDiv.appendChild(preview);
            };
            
            reader.onerror = function() {
                console.error('Error reading file');
                if (previewDiv) {
                    previewDiv.innerHTML = '<div class="text-danger">Error loading preview</div>';
                }
            };
            
            reader.readAsDataURL(file);
        }

        // Function to handle amenity removal
        function handleRemoveAmenity(button) {
            const item = button.closest('.amenities_item');
            if (!item) return;
            
            const hiddenInput = item.querySelector('input[type="hidden"][name^="existing_amenities_icon"]');
            const nameInput = item.querySelector('input[type="text"][name^="amenities"]');
            const fileInput = item.querySelector('input[type="file"][name^="amenities_icon"]');
            
            // If there's an existing icon, mark it for deletion
            if (hiddenInput && hiddenInput.value) {
                // Check if we already have this icon marked for deletion
                const existingDelete = document.querySelector(`input[name="deleted_amenities_icon[]"][value="${hiddenInput.value}"]`);
                
                if (!existingDelete) {
                    const deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = 'deleted_amenities_icon[]';
                    deleteInput.value = hiddenInput.value;
                    document.querySelector('form').appendChild(deleteInput);
                    console.log('Marked icon for deletion:', hiddenInput.value);
                }
            }
            
            // If this was an existing amenity (has a name input with a value), mark it for removal
            if (nameInput && nameInput.value) {
                const removedInput = document.createElement('input');
                removedInput.type = 'hidden';
                removedInput.name = 'removed_amenities[]';
                removedInput.value = nameInput.value;
                document.querySelector('form').appendChild(removedInput);
                console.log('Marked amenity for removal:', nameInput.value);
            }

            // Remove any file input value if present
            if (fileInput) {
                fileInput.value = '';
            }

            // Remove the entire amenity item
            item.remove();
            
            // Re-index the remaining amenities
            reindexAmenities();
            
            console.log('Amenity removed, reindexed items');
        }

        // Function to set up icon preview and event listeners for a single file input
        function setupAmenityFileInput(input) {
            // Remove any existing change event listeners to prevent duplicates
            const newInput = input.cloneNode(true);
            input.parentNode.replaceChild(newInput, input);
            
            // Add new change event listener
            newInput.addEventListener('change', function() {
                // Clear any existing previews first
                const previewDiv = this.closest('.row').querySelector('.icon-preview');
                if (previewDiv) {
                    previewDiv.innerHTML = '';
                }
                handleFileInputChange(this);
            });
            
            // If there's an existing icon, show its preview
            const previewDiv = newInput.closest('.row').querySelector('.icon-preview');
            const hiddenInput = newInput.closest('.amenities_item').querySelector('input[type="hidden"][name^="existing_amenities_icon"]');
            
            if (hiddenInput && hiddenInput.value && previewDiv) {
                const iconPath = hiddenInput.value;
                if (iconPath) {
                    // Check if we already have a preview for this icon
                    if (!previewDiv.querySelector('img')) {
                        const img = document.createElement('img');
                        img.src = '{{ asset("storage/") }}/' + iconPath;
                        img.alt = 'Current icon';
                        img.style.maxWidth = '30px';
                        img.style.maxHeight = '30px';
                        img.className = 'ms-2';
                        
                        const preview = document.createElement('div');
                        preview.className = 'mt-2';
                        preview.innerHTML = '<small>Current Icon:</small>';
                        preview.appendChild(img);
                        
                        // Add remove button for existing icon
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'btn btn-sm btn-outline-danger ms-2 remove-icon';
                        removeBtn.innerHTML = '<i class="fas fa-trash"></i> Remove';
                        removeBtn.dataset.iconPath = iconPath;
                        
                        preview.appendChild(removeBtn);
                        previewDiv.appendChild(preview);
                        
                        // Add event listener to the remove button
                        removeBtn.addEventListener('click', async function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            const iconPath = this.dataset.iconPath;
                            const amenityItem = this.closest('.amenities_item');
                            const amenityIndex = Array.from(amenityItem.parentNode.children).indexOf(amenityItem);
                            
                            try {
                                // Make an AJAX call to remove the icon
                                const response = await fetch(`{{ route('admin.offplan.remove_amenity_icon', $offplan) }}`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    body: JSON.stringify({
                                        icon_path: iconPath,
                                        amenity_index: amenityIndex
                                    })
                                });
                                
                                const result = await response.json();
                                
                                if (result.success) {
                                    // Remove the preview
                                    previewDiv.innerHTML = '';
                                    
                                    // Clear the file input
                                    const fileInput = newInput.closest('.amenities_item').querySelector('input[type="file"]');
                                    if (fileInput) {
                                        fileInput.value = '';
                                    }
                                    
                                    // Clear the hidden input value
                                    if (hiddenInput) {
                                        hiddenInput.value = '';
                                    }
                                    
                                    console.log('Icon removed successfully');
                                } else {
                                    console.error('Failed to remove icon:', result.message || 'Unknown error');
                                    alert('Failed to remove icon: ' + (result.message || 'Unknown error'));
                                }
                            } catch (error) {
                                console.error('Error removing icon:', error);
                                alert('Error removing icon: ' + error.message);
                            }
                        });
                    }
                }
            }
            
            return newInput;
        }

        // Initialize existing amenities
        document.addEventListener('DOMContentLoaded', function() {
            // Set up file input change handlers for existing amenities
            document.querySelectorAll('.amenities_item input[type="file"]').forEach(input => {
                setupAmenityFileInput(input);
            });

            // Set up remove button handlers for existing amenities
            document.querySelectorAll('.amenities_item .remove-amenities').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    handleRemoveAmenity(this);
                });
            });
            
            // Log for debugging
            console.log('Amenities initialization complete');
        });

        // Add new amenity field
        document.getElementById('add_amenities').addEventListener('click', function() {
            const repeater = document.getElementById('amenities_repeater');
            const index = document.querySelectorAll('#amenities_repeater .amenities_item').length;
            
            const newItem = document.createElement('div');
            newItem.classList.add('amenities_item', 'mb-3', 'p-3', 'border', 'rounded');
            newItem.innerHTML = `
                <div class="row">
                    <div class="col-md-5">
                        <label class="form-label">Amenity Name</label>
                        <input type="text" class="form-control mb-2" name="amenities[${index}]" placeholder="Enter amenity name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Icon (SVG recommended)</label>
                        <input type="file" class="form-control mb-2 file-upload" name="amenities_icon[${index}]" accept="image/svg+xml,image/png,image/jpeg">
                        <div class="icon-preview" id="icon-preview-${index}"></div>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-amenities">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            repeater.appendChild(newItem);

            // Set up event listeners for the new amenity
            const fileInput = newItem.querySelector('input[type="file"]');
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    handleFileInputChange(this);
                });
            }

            const removeBtn = newItem.querySelector('.remove-amenities');
            if (removeBtn) {
                removeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    handleRemoveAmenity(this);
                });
            }
        });

        // Function to re-index amenities after removal
        function reindexAmenities() {
            const items = document.querySelectorAll('#amenities_repeater .amenities_item');
            items.forEach((item, index) => {
                // Update amenities name
                const nameInput = item.querySelector('input[type="text"]');
                if (nameInput) {
                    nameInput.name = `amenities[${index}]`;
                }

                // Update amenities_icon name
                const fileInput = item.querySelector('input[type="file"]');
                if (fileInput) {
                    fileInput.name = `amenities_icon[${index}]`;
                    // Update the preview ID
                    const previewDiv = item.querySelector('.icon-preview');
                    if (previewDiv) {
                        previewDiv.id = `icon-preview-${index}`;
                    }
                }

                // Update existing_amenities_icon name if it exists
                const existingIconInput = item.querySelector('input[type="hidden"][name^="existing_amenities_icon"]');
                if (existingIconInput) {
                    existingIconInput.name = `existing_amenities_icon[${index}]`;
                }
            });
        }


        // Handle remove icon button click
        document.addEventListener('click', async function(e) {
            if (e.target.closest('.remove-icon')) {
                e.preventDefault();
                const button = e.target.closest('.remove-icon');
                const item = button.closest('.amenities_item');
                const hiddenInput = item.querySelector('input[type="hidden"][name^="existing_amenities_icon"]');
                const previewContainer = item.querySelector('.icon-preview');
                const fileInput = item.querySelector('input[type="file"]');
                
                if (hiddenInput && hiddenInput.value) {
                    // Store the icon path for later use
                    const iconPath = hiddenInput.value;
                    
                    // Mark the icon for deletion on the server side
                    const deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = 'deleted_amenities_icon[]';
                    deleteInput.value = iconPath;
                    document.querySelector('form').appendChild(deleteInput);
                    
                    // Clear the file input if it exists
                    if (fileInput) {
                        fileInput.value = '';
                    }
                    
                    // Clear the preview container
                    if (previewContainer) {
                        previewContainer.innerHTML = '';
                    }
                    
                    // Remove the hidden input
                    hiddenInput.remove();
                    
                    // Also remove any existing previews
                    const existingPreviews = item.querySelectorAll('.existing-icon, .new-icon-preview');
                    existingPreviews.forEach(preview => preview.remove());
                    
                    console.log('Icon marked for deletion:', iconPath);
                }
            }
        });
        document.getElementById('add_near_by').addEventListener('click', function() {
            var repeater = document.getElementById('near_by_repeater');
            var index = repeater.children.length;
            var newItem = document.createElement('div');
            newItem.classList.add('near_by_item');
            newItem.innerHTML = `
            <input type="text" class="form-control mb-2" name="near_by[${index}][title]" placeholder="Title">
            <input type="number" step="0.1" class="form-control mb-2" name="near_by[${index}][distance]" placeholder="Distance (e.g., 4.5)">
            <button type="button" class="btn btn-danger btn-sm remove-near-by">Remove</button>
        `;
            repeater.appendChild(newItem);
        });

        document.querySelectorAll('.remove-image').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const type = this.getAttribute('data-type');
                const path = this.getAttribute('data-path');

                fetch(`/ioka_admin/offplan/${id}/delete-image`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Ensure CSRF token is included
                        },
                        body: JSON.stringify({
                            type,
                            path
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.parentElement.remove();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });

        document.getElementById('add_agent_language').addEventListener('click', function() {
            var repeater = document.getElementById('agent_languages_repeater');
            var index = repeater.children.length;
            var newItem = document.createElement('div');
            newItem.classList.add('agent_languages_item');
            newItem.innerHTML = `
                <input type="text" class="form-control mb-2" name="agent_languages[${index}]" placeholder="Language">
                <button type="button" class="btn btn-danger btn-sm remove-agent-language">Remove</button>
            `;
            repeater.appendChild(newItem);
        });

        document.addEventListener('click', async function(event) {
            // Handle feature removal
            if (event.target.classList.contains('remove-feature')) {
                event.target.parentElement.remove();
                return;
            }
            
            // Handle near-by removal
            if (event.target.classList.contains('remove-near-by')) {
                event.target.parentElement.remove();
                return;
            }
            
            // Handle image deletion
            if (event.target.classList.contains('remove-image')) {
                event.preventDefault();
                const button = event.target;
                const container = button.closest('.col-4.mb-3');
                const type = button.getAttribute('data-type');
                const path = button.getAttribute('data-path');
                const offplanId = button.getAttribute('data-id');
                
                if (!confirm('Are you sure you want to delete this image?')) {
                    return;
                }
                
                try {
                    // Create form data to send as multipart/form-data
                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('type', type);
                    formData.append('path', path);
                    
                    const response = await fetch(`/ioka_admin/offplan/${offplanId}/delete-image`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Remove the image container from the DOM
                        container.remove();
                        
                        // Show a success message
                        alert('Image deleted successfully');
                        
                        // If no more images, show a message
                        const galleryContainer = document.getElementById(`${type}_preview`);
                        if (galleryContainer && galleryContainer.querySelectorAll('.col-4.mb-3').length === 0) {
                            const message = document.createElement('p');
                            message.textContent = `No ${type.replace('_', ' ')} photos available.`;
                            galleryContainer.querySelector('.row').appendChild(message);
                        }
                    } else {
                        throw new Error(data.error || 'Failed to delete image');
                    }
                } catch (error) {
                    console.error('Error deleting image:', error);
                    alert('Error deleting image: ' + error.message);
                }
            }
            
            // Handle amenities removal
            if (event.target.closest('.remove-amenities')) {
                event.target.closest('.amenities_item').remove();
                // Re-index the remaining amenities
                reindexAmenities();
            }
            
            // Handle agent language removal
            if (event.target.classList.contains('remove-agent-language')) {
                event.target.parentElement.remove();
            }
        });
    </script>
@endsection

