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
                                        <div class="amenities_item">
                                            <input type="text" class="form-control mb-2" name="amenities[0]" placeholder="amenities">
                                            <button type="button" class="btn btn-danger btn-sm remove-amenities">Remove</button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-secondary" id="add_amenities">Add More</button>
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
                                    <label for="main_photo" class="form-label">Main Photo</label>
                                    <input type="file" class="form-control" id="main_photo" name="main_photo" accept="image/*">
                                    <input type="text" class="form-control mt-2" name="main_photo_alt" placeholder="Alt text for main photo">
                                    <div id="main_photo_preview" class="uploaded-files"></div>
                                    <input type="hidden" id="main_photo_path" name="main_photo_path">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="mobile_main_photo" class="form-label">Mobile Main Photo</label>
                                    <input type="file" class="form-control" id="mobile_main_photo" name="mobile_main_photo" accept="image/*" capture="environment">
                                    <input type="text" class="form-control mt-2" name="mobile_main_photo_alt" placeholder="Alt text for mobile main photo">
                                    <div class="mt-2">
                                        <label for="mobile_main_photo_quality" class="form-label">Compression Quality: <span id="mobile_main_photo_quality_value">80</span>%</label>
                                        <input type="range" class="form-range" id="mobile_main_photo_quality" min="10" max="100" value="80">
                                    </div>
                                    <div class="mt-2">
                                        <label for="mobile_main_photo_max_width" class="form-label">Max Width: <span id="mobile_main_photo_max_width_value">1200</span>px</label>
                                        <select class="form-select" id="mobile_main_photo_max_width">
                                            <option value="800">800px</option>
                                            <option value="1000">1000px</option>
                                            <option value="1200" selected>1200px</option>
                                            <option value="1600">1600px</option>
                                        </select>
                                    </div>
                                    <div id="mobile_main_photo_preview" class="mt-2 uploaded-files"></div>
                                    <input type="hidden" id="mobile_main_photo_compressed" name="mobile_main_photo_compressed">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="main_banner" class="form-label">Banner</label>
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
                                    <label for="mobile_banner_photo" class="form-label">Mobile Banner Photo</label>
                                    <input type="file" class="form-control" id="mobile_banner_photo" name="mobile_banner_photo" accept="image/*" capture="environment">
                                    <input type="text" class="form-control mt-2" name="mobile_banner_photo_alt" placeholder="Alt text for mobile banner photo">
                                    <div class="mt-2">
                                        <label for="mobile_banner_photo_quality" class="form-label">Compression Quality: <span id="mobile_banner_photo_quality_value">80</span>%</label>
                                        <input type="range" class="form-range" id="mobile_banner_photo_quality" min="10" max="100" value="80">
                                    </div>
                                    <div class="mt-2">
                                        <label for="mobile_banner_photo_max_width" class="form-label">Max Width: <span id="mobile_banner_photo_max_width_value">1200</span>px</label>
                                        <select class="form-select" id="mobile_banner_photo_max_width">
                                            <option value="800">800px</option>
                                            <option value="1000">1000px</option>
                                            <option value="1200" selected>1200px</option>
                                            <option value="1600">1600px</option>
                                        </select>
                                    </div>
                                    <div id="mobile_banner_photo_preview" class="mt-2 uploaded-files"></div>
                                    <input type="hidden" id="mobile_banner_photo_compressed" name="mobile_banner_photo_compressed">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="exterior_gallery" class="form-label">Exterior Gallery</label>
                                    <input type="file" class="form-control" id="exterior_gallery" name="exterior_gallery[]" multiple accept="image/*">
                                    <div id="exterior_gallery_preview" class="uploaded-files"></div>
                                    <input type="hidden" id="exterior_gallery_paths" name="exterior_gallery_paths">
                                    <input type="hidden" id="exterior_gallery_alt" name="exterior_gallery_alt[]">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="interior_gallery" class="form-label">Interior Gallery</label>
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
                                    <label for="qr_photo" class="form-label">QR Photo</label>
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
                                    <label for="agent_image" class="form-label">Agent Image</label>
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

        // Mobile Main Photo Handling
        document.getElementById('mobile_main_photo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    const quality = parseInt(document.getElementById('mobile_main_photo_quality').value) / 100;
                    const maxWidth = parseInt(document.getElementById('mobile_main_photo_max_width').value);
                    
                    // Compress the image
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;
                    
                    if (width > maxWidth) {
                        height = Math.round(height * maxWidth / width);
                        width = maxWidth;
                    }
                    
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);
                    
                    // Convert to base64
                    const compressedDataUrl = canvas.toDataURL('image/jpeg', quality);
                    
                    // Display preview
                    const preview = document.getElementById('mobile_main_photo_preview');
                    preview.innerHTML = `<img src="${compressedDataUrl}" class="img-thumbnail" style="max-height: 150px;">`;
                    
                    // Store compressed image data
                    document.getElementById('mobile_main_photo_compressed').value = compressedDataUrl;
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });

        // Mobile Banner Photo Handling
        document.getElementById('mobile_banner_photo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    const quality = parseInt(document.getElementById('mobile_banner_photo_quality').value) / 100;
                    const maxWidth = parseInt(document.getElementById('mobile_banner_photo_max_width').value);
                    
                    // Compress the image
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;
                    
                    if (width > maxWidth) {
                        height = Math.round(height * maxWidth / width);
                        width = maxWidth;
                    }
                    
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);
                    
                    // Convert to base64
                    const compressedDataUrl = canvas.toDataURL('image/jpeg', quality);
                    
                    // Display preview
                    const preview = document.getElementById('mobile_banner_photo_preview');
                    preview.innerHTML = `<img src="${compressedDataUrl}" class="img-thumbnail" style="max-height: 150px;">`;
                    
                    // Store compressed image data
                    document.getElementById('mobile_banner_photo_compressed').value = compressedDataUrl;
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });

        // Update quality and max width display values
        document.getElementById('mobile_main_photo_quality').addEventListener('input', function() {
            document.getElementById('mobile_main_photo_quality_value').textContent = this.value;
        });

        document.getElementById('mobile_main_photo_max_width').addEventListener('change', function() {
            document.getElementById('mobile_main_photo_max_width_value').textContent = this.value;
        });

        document.getElementById('mobile_banner_photo_quality').addEventListener('input', function() {
            document.getElementById('mobile_banner_photo_quality_value').textContent = this.value;
        });

        document.getElementById('mobile_banner_photo_max_width').addEventListener('change', function() {
            document.getElementById('mobile_banner_photo_max_width_value').textContent = this.value;
        });
    </script>
@endsection
