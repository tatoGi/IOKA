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
                            <label for="subtitle" class="form-label">Subtitle</label>
                            <input type="text" class="form-control" id="subtitle" name="subtitle">
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
                            <textarea class="form-control editor" id="amenities" name="amenities"></textarea>
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
                            <div id="main_photo_preview" class="uploaded-files"></div>
                            <input type="hidden" id="main_photo_path" name="main_photo_path">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="exterior_gallery" class="form-label">Exterior Gallery</label>
                            <input type="file" class="form-control" id="exterior_gallery" name="exterior_gallery[]" multiple accept="image/*">
                            <div id="exterior_gallery_preview" class="uploaded-files"></div>
                            <input type="hidden" id="exterior_gallery_paths" name="exterior_gallery_paths">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="interior_gallery" class="form-label">Interior Gallery</label>
                            <input type="file" class="form-control" id="interior_gallery" name="interior_gallery[]" multiple accept="image/*">
                            <div id="interior_gallery_preview" class="uploaded-files"></div>
                            <input type="hidden" id="interior_gallery_paths" name="interior_gallery_paths">
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
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="agent_image" class="form-label">Agent Image</label>
                            <input type="file" class="form-control" id="agent_image" name="agent_image" accept="image/*">
                            <div id="agent_image_preview" class="uploaded-files"></div>
                            <input type="hidden" id="agent_image_path" name="agent_image_path">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success w-100">Submit</button>
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
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                reader.onload = function(e) {
                    const fileDiv = document.createElement('div');
                    fileDiv.classList.add('uploaded-file');
                    fileDiv.innerHTML = `
                        <img src="${e.target.result}" alt="${file.name}" class="img-thumbnail" style="max-width: 100px;">
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

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-feature')) {
                event.target.parentElement.remove();
            }
            if (event.target.classList.contains('remove-near-by')) {
                event.target.parentElement.remove();
            }
        });
    </script>
@endsection
