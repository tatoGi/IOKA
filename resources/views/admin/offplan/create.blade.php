@extends('admin.layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Create New Offplan</h1>
        <form action="{{ route('admin.offplan.store') }}" method="POST" enctype="multipart/form-data" class="shadow p-4 rounded bg-light">
            @csrf
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                            </div>
                           
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">slug</label>
                                    <input type="text" class="form-control" id="slug" name="slug">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount (in dollars)</label>
                                    <input type="number" step="0.01" class="form-control amount" id="amount" name="amount" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount_dirhams" class="form-label">Amount (in Dirhams)</label>
                                    <input type="number" step="0.01" class="form-control amount_dirhams" id="amount_dirhams" name="amount_dirhams" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control editor" id="description" name="description" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="features" class="form-label">Features</label>
                                    <div id="features_repeater">
                                        <div class="features_item">
                                            <input type="text" class="form-control mb-2" name="features[0]" placeholder="Feature">
                                            <button type="button" class="btn btn-danger btn-sm remove-feature">Remove</button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-secondary" id="add_feature">Add More</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="amenities" class="form-label">Amenities</label>
                                    <div id="amenities_repeater">
                                        <div class="amenities_item mb-3 p-3 border rounded">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label class="form-label">Amenity Name</label>
                                                    <input type="text" class="form-control mb-2" name="amenities[0]" placeholder="Enter amenity name" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Icon (SVG recommended)</label>
                                                    <input type="file" class="form-control mb-2" name="amenities_icon[0]" accept="image/svg+xml,image/png,image/jpeg">
                                                    <div class="icon-preview"></div>
                                                </div>
                                                <div class="col-md-1 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger btn-sm remove-amenities">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-secondary mt-2" id="add_amenities">
                                        <i class="fas fa-plus"></i> Add Amenity
                                    </button>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="map_location" class="form-label">Map Location</label>
                                    <input type="text" class="form-control" id="map_location" name="map_location">
                                </div>
                            </div>
                        </div>
                      

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="near_by" class="form-label">Near By</label>
                                    <div id="near_by_repeater">
                                        <div class="near_by_item">
                                            <input type="text" class="form-control mb-2" name="near_by[0][title]" placeholder="Title">
                                            <input type="number" step="0.1" class="form-control mb-2" name="near_by[0][distance]" placeholder="Distance (e.g., 4.5)">
                                            <button type="button" class="btn btn-danger btn-sm remove-near-by">Remove</button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-secondary" id="add_near_by">Add More</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="main_photo" class="form-label">Main Photo(737 x 461)</label>
                                    <input type="file" class="form-control" id="main_photo" name="main_photo" accept="image/*">
                                    <input type="text" class="form-control mt-2" name="main_photo_alt" placeholder="Alt text for main photo">
                                    <div id="main_photo_preview" class="uploaded-files"></div>
                                    <input type="hidden" id="main_photo_path" name="main_photo_path">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="mobile_image" class="form-label">Mobile Photo</label>
                                    <div class="mobile-image-upload" id="mobile-upload-blog_mobile_image">
                                        <div class="mb-2">
                                            <div class="d-flex align-items-center">
                                                <input type="file" class="form-control mobile-image-input @error('mobile_image') is-invalid @enderror" 
                                                    id="input-blog_mobile_image" name="mobile_image" accept="image/*" capture="environment"
                                                    data-field="blog_mobile_image"
                                                    onchange="handleMobileImageSelect(this)">
                                                @error('mobile_image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="compression-options mb-2 d-none">
                                            <div class="card p-3">
                                                <div class="mb-2">
                                                    <label class="form-label">Image Quality</label>
                                                    <input type="range" class="form-range quality-slider" min="10" max="100" value="70" 
                                                        id="quality-blog_mobile_image" data-field="blog_mobile_image">
                                                    <div class="d-flex justify-content-between">
                                                        <small>Lower (Smaller File)</small>
                                                        <small class="quality-value">70%</small>
                                                        <small>Higher (Better Quality)</small>
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-2">
                                                    <label class="form-label">Max Width</label>
                                                    <select class="form-select max-width" id="max-width-blog_mobile_image" data-field="blog_mobile_image" style="display: block !important; width: 100%;">
                                                        <option value="800" selected>Small (800px)</option>
                                                        <option value="1200">Medium (1200px)</option>
                                                        <option value="1600">Large (1600px)</option>
                                                        <option value="0">Original Size</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="image-preview-container mb-2 d-none">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <label class="form-label mb-0">Preview</label>
                                                        <div class="file-info small text-muted"></div>
                                                    </div>
                                                    <img src="" class="img-fluid img-thumbnail preview-image" style="max-height: 200px;">
                                                </div>
                                                
                                                <div class="d-flex justify-content-between">
                                                    <button type="button" class="btn btn-secondary btn-sm cancel-compression" 
                                                        onclick="cancelCompression('blog_mobile_image')">Cancel</button>
                                                    <button type="button" class="btn btn-primary btn-sm apply-compression" 
                                                        onclick="applyCompression('blog_mobile_image')">Apply & Upload</button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <input type="hidden" name="mobile_image_compressed" id="compressed-blog_mobile_image" class="compressed-image-data">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="main_banner" class="form-label">Banner(1560  x 405)</label>
                                    <input type="file" class="form-control" id="main_banner" name="main_banner">
                                    <input type="text" class="form-control mt-2" name="banner_photo_alt" placeholder="Alt text for banner photo">
                                    <div id="main_banner_preview" class="uploaded-files"></div>
                                    <input type="hidden" id="main_banner_path" name="main_banner">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="banner_title" class="form-label">Banner Title</label>
                                        <input type="text" class="form-control" id="banner_title" name="banner_title" placeholder="Enter banner title">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="mobile_banner_image" class="form-label">Mobile Banner Photo</label>
                                    <div class="mobile-image-upload" id="mobile-upload-blog_mobile_banner_image">
                                        <div class="mb-2">
                                            <div class="d-flex align-items-center">
                                                <input type="file" class="form-control mobile-image-input @error('mobile_banner_image') is-invalid @enderror" 
                                                    id="input-blog_mobile_banner_image" name="mobile_banner_image" accept="image/*" capture="environment"
                                                    data-field="blog_mobile_banner_image"
                                                    onchange="handleMobileImageSelect(this)">
                                                @error('mobile_banner_image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="compression-options mb-2 d-none">
                                            <div class="card p-3">
                                                <div class="mb-2">
                                                    <label class="form-label">Image Quality</label>
                                                    <input type="range" class="form-range quality-slider" min="10" max="100" value="70" 
                                                        id="quality-blog_mobile_banner_image" data-field="blog_mobile_banner_image">
                                                    <div class="d-flex justify-content-between">
                                                        <small>Lower (Smaller File)</small>
                                                        <small class="quality-value">70%</small>
                                                        <small>Higher (Better Quality)</small>
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-2">
                                                    <label class="form-label">Max Width</label>
                                                    <select class="form-select max-width" id="max-width-blog_mobile_banner_image" data-field="blog_mobile_banner_image" style="display: block !important; width: 100%;">
                                                        <option value="800" selected>Small (800px)</option>
                                                        <option value="1200">Medium (1200px)</option>
                                                        <option value="1600">Large (1600px)</option>
                                                        <option value="0">Original Size</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="image-preview-container mb-2 d-none">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <label class="form-label mb-0">Preview</label>
                                                        <div class="file-info small text-muted"></div>
                                                    </div>
                                                    <img src="" class="img-fluid img-thumbnail preview-image" style="max-height: 200px;">
                                                </div>
                                                
                                                <div class="d-flex justify-content-between">
                                                    <button type="button" class="btn btn-secondary btn-sm cancel-compression" 
                                                        onclick="cancelCompression('blog_mobile_banner_image')">Cancel</button>
                                                    <button type="button" class="btn btn-primary btn-sm apply-compression" 
                                                        onclick="applyCompression('blog_mobile_banner_image')">Apply & Upload</button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <input type="hidden" name="mobile_banner_image_compressed" id="compressed-blog_mobile_banner_image" class="compressed-image-data">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="exterior_gallery" class="form-label">Exterior Gallery(372 x 272)</label>
                                    <input type="file" class="form-control" id="exterior_gallery" name="exterior_gallery[]" multiple accept="image/*">
                                    <div id="exterior_gallery_preview" class="uploaded-files"></div>
                                    <input type="hidden" id="exterior_gallery_paths" name="exterior_gallery_paths">
                                    <input type="hidden" id="exterior_gallery_alt" name="exterior_gallery_alt[]">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="interior_gallery" class="form-label">Interior Gallery(372 x 272)</label>
                                    <input type="file" class="form-control" id="interior_gallery" name="interior_gallery[]" multiple accept="image/*">
                                    <div id="interior_gallery_preview" class="uploaded-files"></div>
                                    <input type="hidden" id="interior_gallery_paths" name="interior_gallery_paths">
                                    <input type="hidden" id="interior_gallery_alt" name="interior_gallery_alt[]">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="property_type" class="form-label">Property Type</label>
                                    <select class="form-control select2" id="property_type" name="property_type">
                                        <option value="Villa">Villa</option>
                                        <option value="Townhouse">Townhouse</option>
                                        <option value="Apartment">Apartment</option>
                                        <option value="Land">Land</option>
                                        <option value="Full Building">Full Building</option>
                                        <option value="Commercial">Commercial</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="bathroom" class="form-label">Bathroom</label>
                                    <input type="number" class="form-control" id="bathroom" name="bathroom">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="bedroom" class="form-label">Bedroom</label>
                                    <input type="number" class="form-control" id="bedroom" name="bedroom">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="garage" class="form-label">Garage</label>
                                    <input type="number" class="form-control" id="garage" name="garage">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="sq_ft" class="form-label">Square Feet</label>
                                    <input type="number" class="form-control" id="sq_ft" name="sq_ft">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="qr_title" class="form-label">QR Title</label>
                                    <input type="text" class="form-control" id="qr_title" name="qr_title">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="qr_photo" class="form-label">QR Photo (149 x 149)</label>
                                    <input type="file" class="form-control" id="qr_photo" name="qr_photo">
                                    <input type="text" class="form-control mt-2" name="qr_photo_alt" placeholder="Alt text for QR photo">
                                    <input type="hidden" id="qr_photo_path" name="qr_photo_path">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="qr_text" class="form-label">QR Text</label>
                                    <textarea class="form-control editor" id="qr_text" name="qr_text"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="download_brochure" class="form-label">Download Brochure</label>
                                    <input type="text" class="form-control" id="download_brochure" name="download_brochure">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="agent_title" class="form-label">Agent Title</label>
                                    <input type="text" class="form-control" id="agent_title" name="agent_title">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="agent_status" class="form-label">Agent Status</label>
                                    <input type="text" class="form-control" id="agent_status" name="agent_status">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="agent_telephone" class="form-label">Agent Telephone</label>
                                    <input type="text" class="form-control" id="agent_telephone" name="agent_telephone">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="agent_whatsapp" class="form-label">Agent WhatsApp</label>
                                    <input type="text" class="form-control" id="agent_whatsapp" name="agent_whatsapp">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="agent_linkedin" class="form-label">Agent LinkedIn</label>
                                    <input type="text" class="form-control" id="agent_linkedin" name="agent_linkedin">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group mt-3">
                                    <label for="agent_email">Agent Email</label>
                                    <input type="email" class="form-control" id="agent_email" name="agent_email">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="agent_image" class="form-label">Agent Image (92 x 92)</label>
                                    <input type="file" class="form-control" id="agent_image" name="agent_image" accept="image/*">
                                    <input type="text" class="form-control mt-2" name="agent_image_alt" placeholder="Alt text for agent photo">
                                    <div id="agent_image_preview" class="uploaded-files"></div>
                                    <input type="hidden" id="agent_image_path" name="agent_image_path">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="agent_languages">Agent Languages</label>
                                    <div id="agent_languages_repeater">
                                        <div class="agent_languages_item">
                                            <input type="text" class="form-control mb-2" name="agent_languages[0]" placeholder="Language">
                                            <button type="button" class="btn btn-danger btn-sm remove-agent-language">Remove</button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-secondary" id="add_agent_language">Add More</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="location_id" class="form-label">Location</label>
                                    <select class="form-control select2" id="location_id" name="location_id">
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                </div>

                <!-- Meta Data Tab -->
                <div class="tab-pane" id="metadata-tab" role="tabpanel">
                    <x-metadata-form :model="new App\Models\Offplan" />
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Create Offplan</button>
            </div>
        </form>
    </div>

    <script>
        function handleFileInput(event, previewId, hiddenInputId) {
            const files = event.target.files;
            const preview = document.getElementById(previewId);
            const hiddenInput = document.getElementById(hiddenInputId);
            preview.innerHTML = '';
            let filePaths = [];
            let altTexts = [];

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                reader.onload = function(e) {
                    const fileDiv = document.createElement('div');
                    fileDiv.classList.add('uploaded-file');
                    fileDiv.innerHTML = `
                        <img src="${e.target.result}" alt="Property ${file.name}" class="img-thumbnail" style="max-width: 100px;">
                       
                        <button type="button" class="btn btn-danger btn-sm remove-file" onclick="removeFile('${previewId}', ${i})">Remove</button>
                    `;
                    preview.appendChild(fileDiv);
                    filePaths.push(file.name);
                    hiddenInput.value = JSON.stringify(filePaths);
                };
                reader.readAsDataURL(file);
            }
        }

        function removeFile(previewId, index) {
            const preview = document.getElementById(previewId);
            const files = preview.children;
            if (files[index]) {
                files[index].remove();
            }
        }

        document.getElementById('main_photo').addEventListener('change', function(event) {
            handleFileInput(event, 'main_photo_preview', 'main_photo_path');
        });
        document.getElementById('main_banner').addEventListener('change', function(event) {
            handleFileInput(event, 'main_banner_preview', 'main_banner_path');
        });

        document.getElementById('exterior_gallery').addEventListener('change', function(event) {
            handleFileInput(event, 'exterior_gallery_preview', 'exterior_gallery_paths');
        });

        document.getElementById('interior_gallery').addEventListener('change', function(event) {
            handleFileInput(event, 'interior_gallery_preview', 'interior_gallery_paths');
        });

        document.getElementById('agent_image').addEventListener('change', function(event) {
            handleFileInput(event, 'agent_image_preview', 'agent_image_path');
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
        // Add new amenity field
        document.getElementById('add_amenities').addEventListener('click', function() {
            var repeater = document.getElementById('amenities_repeater');
            var index = repeater.children.length;
            var newItem = document.createElement('div');
            newItem.classList.add('amenities_item', 'mb-3', 'p-3', 'border', 'rounded');
            newItem.innerHTML = `
                <div class="row">
                    <div class="col-md-5">
                        <label class="form-label">Amenity Name</label>
                        <input type="text" class="form-control mb-2" name="amenities[${index}]" placeholder="Enter amenity name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Icon (SVG recommended)</label>
                        <input type="file" class="form-control mb-2" name="amenities_icon[${index}]" accept="image/svg+xml,image/png,image/jpeg">
                        <div class="icon-preview"></div>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-amenities">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            repeater.appendChild(newItem);
            
            // Add event listener to the new remove button
            newItem.querySelector('.remove-amenities').addEventListener('click', function() {
                const item = this.closest('.amenities_item');
                const hiddenInput = item.querySelector('input[type="hidden"][name^="existing_amenities_icon"]');
                
                // If there's an existing icon, mark it for deletion
                if (hiddenInput && hiddenInput.value) {
                    const deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = 'deleted_amenities_icon[]';
                    deleteInput.value = hiddenInput.value;
                    document.querySelector('form').appendChild(deleteInput);
                }
                
                item.remove();
                reindexAmenities();
            });
            
            // Add event listener for file input change
            const fileInput = newItem.querySelector('input[type="file"]');
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewDiv = newItem.querySelector('.icon-preview');
                            if (previewDiv) {
                                previewDiv.innerHTML = `
                                    <div class="mt-2">
                                        <small>Preview:</small>
                                        <img src="${e.target.result}" alt="Preview" style="max-width: 30px; max-height: 30px;" class="ms-2">
                                    </div>
                                `;
                            }
                        };
                        reader.readAsDataURL(file);
                    }
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
                }
                
                // Update existing_amenities_icon name if it exists
                const existingIconInput = item.querySelector('input[type="hidden"][name^="existing_amenities_icon"]');
                if (existingIconInput) {
                    existingIconInput.name = `existing_amenities_icon[${index}]`;
                }
            });
        }
        
        // Handle remove icon button click
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-icon')) {
                e.preventDefault();
                const button = e.target.closest('.remove-icon');
                const item = button.closest('.amenities_item');
                const hiddenInput = item.querySelector('input[type="hidden"][name^="existing_amenities_icon"]');
                
                if (hiddenInput && hiddenInput.value) {
                    // Mark the icon for deletion on the server side
                    const deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = 'deleted_amenities_icon[]';
                    deleteInput.value = hiddenInput.value;
                    document.querySelector('form').appendChild(deleteInput);
                    
                    // Remove the preview and hidden input
                    const previewDiv = button.closest('.mt-2');
                    if (previewDiv) {
                        previewDiv.remove();
                    }
                    hiddenInput.remove();
                    
                    // Reset the file input
                    const fileInput = item.querySelector('input[type="file"]');
                    if (fileInput) {
                        fileInput.value = '';
                    }
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
            if (event.target.closest('.remove-amenities')) {
                event.target.closest('.amenities_item').remove();
                // Re-index the remaining amenities
                reindexAmenities();
            }
            if (event.target.classList.contains('remove-agent-language')) {
                event.target.parentElement.remove();
            }
        });

        // Mobile Main Photo Handling
            // Object to store original files for compression
            const originalFiles = {};
        
        // Handle mobile image file selection
        function handleMobileImageSelect(input) {
            const inputId = input.id;
            const file = input.files[0];
            
            if (!file) return;
            
            // Parse the input ID to determine field identifier
            const parts = inputId.split('-');
            const fieldIdentifier = parts[1];
            console.log('Field detected:', fieldIdentifier);
            
            // Store original file for later use - use a unique key for each field
            originalFiles[fieldIdentifier] = file;
            console.log('Stored file for field:', fieldIdentifier, 'File name:', file.name);
            
            // Verify the hidden input exists
            const hiddenInput = document.getElementById('compressed-' + fieldIdentifier);
            if (!hiddenInput) {
                console.error('Hidden input not found for field:', fieldIdentifier);
            } else {
                console.log('Hidden input found for field:', fieldIdentifier, 'ID:', hiddenInput.id);
            }
            
            // Show compression options
            const container = document.getElementById('mobile-upload-' + fieldIdentifier);
            const options = container.querySelector('.compression-options');
            options.classList.remove('d-none');
            
            // Set up quality slider
            const qualitySlider = document.getElementById('quality-' + fieldIdentifier);
            const qualityValue = qualitySlider.parentElement.querySelector('.quality-value');
            
            qualitySlider.addEventListener('input', function() {
                qualityValue.textContent = this.value + '%';
                previewCompressedImage(fieldIdentifier);
            });
            
            // Set up max width select
            const widthSelect = document.getElementById('max-width-' + fieldIdentifier);
            if (widthSelect) {
                // Fix for select dropdown display issues
                widthSelect.style.display = 'block';
                widthSelect.style.width = '100%';
                
                widthSelect.addEventListener('click', function(e) {
                    // Prevent any default behavior that might be interfering
                    e.stopPropagation();
                    
                    // Force the dropdown to show if it's not showing
                    if (this.size <= 1) {
                        this.size = 4; // Show 4 options at once
                        setTimeout(() => {
                            // Reset after selection
                            document.addEventListener('click', function closeDropdown() {
                                widthSelect.size = 1;
                                document.removeEventListener('click', closeDropdown);
                            }, { once: true });
                        }, 0);
                    }
                });
                
                // Make sure options are visible
                const options = widthSelect.querySelectorAll('option');
                options.forEach(option => {
                    option.style.display = 'block';
                });
                
                widthSelect.addEventListener('change', function() {
                    previewCompressedImage(fieldIdentifier);
                });
            }
            
            // Generate initial preview
            previewCompressedImage(fieldIdentifier);
        }
        
        // Preview the compressed image with current settings
        function previewCompressedImage(fieldIdentifier) {
            const file = originalFiles[fieldIdentifier];
            if (!file) return;
            
            const container = document.getElementById('mobile-upload-' + fieldIdentifier);
            const qualitySlider = document.getElementById('quality-' + fieldIdentifier);
            const widthSelect = document.getElementById('max-width-' + fieldIdentifier);
            const previewContainer = container.querySelector('.image-preview-container');
            const previewImage = container.querySelector('.preview-image');
            const fileInfo = container.querySelector('.file-info');
            
            // Show preview container
            previewContainer.classList.remove('d-none');
            
            // Get settings
            const quality = parseInt(qualitySlider.value) / 100;
            const maxWidth = parseInt(widthSelect.value);
            
            // Create a FileReader to read the image
            const reader = new FileReader();
            reader.onload = function(e) {
                // Create an image element to get dimensions
                const img = new Image();
                img.onload = function() {
                    // Calculate new dimensions while maintaining aspect ratio
                    let newWidth = img.width;
                    let newHeight = img.height;
                    
                    if (maxWidth > 0 && img.width > maxWidth) {
                        newWidth = maxWidth;
                        newHeight = (img.height * maxWidth) / img.width;
                    }
                    
                    // Create canvas for compression
                    const canvas = document.createElement('canvas');
                    canvas.width = newWidth;
                    canvas.height = newHeight;
                    
                    // Draw and compress image
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, newWidth, newHeight);
                    
                    // Get compressed image as data URL
                    const compressedDataUrl = canvas.toDataURL('image/jpeg', quality);
                    
                    // Update preview
                    previewImage.src = compressedDataUrl;
                    
                    // Calculate and display file size information
                    const originalSizeKB = Math.round(file.size / 1024);
                    
                    // Estimate compressed size from data URL
                    const base64 = compressedDataUrl.split(',')[1];
                    const compressedSizeKB = Math.round((base64.length * 3/4) / 1024);
                    
                    const savedPercent = Math.round((1 - (compressedSizeKB / originalSizeKB)) * 100);
                    
                    fileInfo.textContent = `Original: ${originalSizeKB}KB → Compressed: ~${compressedSizeKB}KB (${savedPercent}% saved)`;
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
        
        // Apply compression and store in hidden field for form submission
        function applyCompression(fieldIdentifier) {
            const file = originalFiles[fieldIdentifier];
            if (!file) {
                console.error('No file found for field:', fieldIdentifier);
                return;
            }
            
            console.log('Applying compression for field:', fieldIdentifier);
            
            // Get the file input element
            const fileInput = document.getElementById('input-' + fieldIdentifier);
            if (!fileInput) {
                console.error('File input not found for field:', fieldIdentifier);
                return;
            }
            
            // Get all the necessary elements for this specific field
            const container = document.getElementById('mobile-upload-' + fieldIdentifier);
            const qualitySlider = document.getElementById('quality-' + fieldIdentifier);
            const widthSelect = document.getElementById('max-width-' + fieldIdentifier);
            const hiddenInput = document.getElementById('compressed-' + fieldIdentifier);
            const options = container.querySelector('.compression-options');
            
            // Log all the elements to make sure they're correctly identified
            console.log('Elements for field', fieldIdentifier, ':', {
                container: container ? 'Found' : 'Missing',
                qualitySlider: qualitySlider ? 'Found' : 'Missing',
                widthSelect: widthSelect ? 'Found' : 'Missing',
                hiddenInput: hiddenInput ? 'Found' : 'Missing',
                options: options ? 'Found' : 'Missing'
            });
            
            if (!container || !qualitySlider || !widthSelect || !hiddenInput || !fileInput || !options) {
                console.error('Missing elements for field:', fieldIdentifier);
                return;
            }
            
            // Get settings
            const quality = parseInt(qualitySlider.value) / 100;
            const maxWidth = parseInt(widthSelect.value);
            
            // Create a FileReader to read the image
            const reader = new FileReader();
            reader.onload = function(e) {
                // Create an image element to get dimensions
                const img = new Image();
                img.onload = function() {
                    // Calculate new dimensions while maintaining aspect ratio
                    let newWidth = img.width;
                    let newHeight = img.height;
                    
                    if (maxWidth > 0 && img.width > maxWidth) {
                        newWidth = maxWidth;
                        newHeight = (img.height * maxWidth) / img.width;
                    }
                    
                    // Create canvas for compression
                    const canvas = document.createElement('canvas');
                    canvas.width = newWidth;
                    canvas.height = newHeight;
                    
                    // Draw and compress image
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, newWidth, newHeight);
                    
                    // Get compressed image as data URL
                    const compressedDataUrl = canvas.toDataURL('image/jpeg', quality);
                    
                    // Upload to server for optimization
                    const formData = new FormData();
                    formData.append('image', compressedDataUrl);
                    formData.append('quality', Math.round(quality * 100));
                    formData.append('maxWidth', maxWidth);
                    
                    // Show loading state
                    options.innerHTML = '<div class="alert alert-info">Uploading and optimizing image...</div>';
                    
                    // Disable file input during upload
                    fileInput.disabled = true;
                    
                    // Send to server
                    fetch('{{ route("mobile.image.upload") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Server response success for field:', fieldIdentifier);
                            console.log('Image path from server:', data.path);
                            console.log('Image URL from server:', data.url);
                            
                            // Store the server-optimized image path in hidden input
                            hiddenInput.value = data.path;
                            console.log('Updated hidden input value for', fieldIdentifier, 'to:', hiddenInput.value);
                            
                            // Disable the file input to prevent double submission
                            fileInput.disabled = true;
                            
                            // Show success message with server-side optimization details
                            options.innerHTML = `
                                <div class="alert alert-success">
                                    <strong>Image optimized successfully!</strong><br>
                                    Data usage has been optimized for mobile networks.<br>
                                    <img src="${data.url}" class="img-fluid img-thumbnail mt-2" style="max-height: 200px;">
                                </div>
                            `;
                        } else {
                            console.error('Server error for field:', fieldIdentifier, data.message);
                            // Show error message
                            options.innerHTML = `<div class="alert alert-danger">Server error: ${data.message}</div>`;
                            // Re-enable file input
                            fileInput.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        // Show error message
                        options.innerHTML = `<div class="alert alert-danger">Network error: ${error.message}</div>`;
                        // Re-enable file input
                        fileInput.disabled = false;
                    });
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
        
        // Cancel compression and reset the file input
        function cancelCompression(fieldIdentifier) {
            console.log('Cancelling compression for field:', fieldIdentifier);
            
            // Get elements
            const fileInput = document.getElementById('input-' + fieldIdentifier);
            const container = document.getElementById('mobile-upload-' + fieldIdentifier);
            const options = container.querySelector('.compression-options');
            const hiddenInput = document.getElementById('compressed-' + fieldIdentifier);
            
            // Reset file input
            if (fileInput) {
                fileInput.value = '';
                fileInput.disabled = false;
            }
            
            // Hide options
            if (options) {
                options.classList.add('d-none');
            }
            
            // Clear hidden input
            if (hiddenInput) {
                hiddenInput.value = '';
            }
            
            // Remove from original files
            delete originalFiles[fieldIdentifier];
        }
    </script>
@endsection
