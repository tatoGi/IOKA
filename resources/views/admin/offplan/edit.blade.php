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
                                    $amenities_items = $offplan->amenities;
                                    if (is_string($amenities_items)) {
                                        $amenities_items = json_decode($amenities_items, true);
                                    }
                                    if (!is_array($amenities_items)) {
                                        $amenities_items = [];
                                    }
                                @endphp
                                @foreach ($amenities_items as $index => $amenity)
                                    <div class="amenities_item">
                                        <input type="text" class="form-control mb-2"
                                            name="amenities[{{ $index }}]" value="{{ $amenity }}"
                                            placeholder="amenities">
                                        <button type="button"
                                            class="btn btn-danger btn-sm remove-amenities">Remove</button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary" id="add_amenities">Add More</button>
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
        document.getElementById('add_amenities').addEventListener('click', function() {
            var repeater = document.getElementById('amenities_repeater');
            var index = repeater.children.length;
            var newItem = document.createElement('div');
            newItem.classList.add('amenities_item');
            newItem.innerHTML = `
                <input type="text" class="form-control mb-2" name="amenities[${index}]" placeholder="amenities">
                <button type="button" class="btn btn-danger btn-sm remove-amenities">Remove</button>
            `;
            repeater.appendChild(newItem);
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

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-feature')) {
                event.target.parentElement.remove();
            }
            if (event.target.classList.contains('remove-near-by')) {
                event.target.parentElement.remove();
            }
            if (event.target.classList.contains('remove-amenities')) {
                event.target.parentElement.remove();
            }
            if (event.target.classList.contains('remove-agent-language')) {
                event.target.parentElement.remove();
            }
        });
    </script>
@endsection
