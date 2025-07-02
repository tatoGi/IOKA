@extends('admin.layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Create Rental Resale Post</h1>
        <form id="rental-resale-form" action="{{ route('admin.postypes.rental_resale.store') }}" method="POST" enctype="multipart/form-data" class="shadow p-4 rounded bg-light">
            @csrf
            <input type="hidden" id="postId" value="">
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
                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="tags" name="tags[]" multiple required>
                            <option value="6">Resale</option>
                            <option value="5">Rental</option>
                        </select>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount (in dollars) </label>
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
                    </div>
                    <div class="mb-3">
                        <label for="property_type" class="form-label">Property Type <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="property_type" name="property_type" required>
                            <option value="Villa">Villa</option>
                            <option value="Townhouse">Townhouse</option>
                            <option value="Apartment">Apartment</option>
                            <option value="Land">Land</option>
                            <option value="Full Building">Full Building</option>
                            <option value="Commercial">Commercial</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Subtitle<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle" required>
                    </div>

                    <div class="container">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="bathroom" class="form-label">Bathroom<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="bathroom" name="bathroom" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="bedroom" class="form-label">Bedroom<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="bedroom" name="bedroom" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="sq_ft" class="form-label">SQ Ft<span class="text-danger">*</span></label>
                                    <input type="number" step="0.1" class="form-control" id="sq_ft" name="sq_ft" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="garage" class="form-label">Garage<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="garage" name="garage" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description<span class="text-danger">*</span></label>
                        <textarea class="form-control editor" id="description" name="description" required></textarea>
                    </div>
                    <div class="container">
                        <div class="row">
                            <!-- Details Repeater -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="details" class="form-label">Details<span class="text-danger">*</span></label>
                                    <div class="details-repeater">
                                        <div data-repeater-list="details">
                                            <div data-repeater-item class="repeater-item mb-2">
                                                <input type="text" class="form-control mb-2" name="title" placeholder="Title" required>
                                                <input type="text" class="form-control mb-2" name="info" placeholder="Information" required>
                                                <button type="button" class="btn btn-danger" data-repeater-delete>
                                                    <i class="fas fa-trash-alt"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary mt-2" data-repeater-create>
                                            <i class="fas fa-plus"></i> Add Detail
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Amenities Repeater -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="amenities" class="form-label">Amenities<span class="text-danger">*</span></label>
                                    <div class="amenities-repeater">
                                        <div data-repeater-list="amenities">
                                            <div data-repeater-item class="repeater-item mb-2">
                                                <input type="text" class="form-control mb-2" name="amenity" placeholder="Amenity" required>
                                                <button type="button" class="btn btn-danger" data-repeater-delete>
                                                    <i class="fas fa-trash-alt"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary mt-2" data-repeater-create>
                                            <i class="fas fa-plus"></i> Add Amenity
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Addresses Repeater -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="addresses" class="form-label">Addresses<span class="text-danger">*</span></label>
                                    <div class="addresses-repeater">
                                        <div data-repeater-list="addresses">
                                            <div data-repeater-item class="repeater-item mb-2">
                                                <input type="text" class="form-control mb-2" name="address" placeholder="Address" required>
                                                <button type="button" class="btn btn-danger" data-repeater-delete>
                                                    <i class="fas fa-trash-alt"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary mt-2" data-repeater-create>
                                            <i class="fas fa-plus"></i> Add Address
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="agent_title" class="form-label">Agent Title<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="agent_title" name="agent_title" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="agent_status" class="form-label">Agent Status<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="agent_status" name="agent_status" required>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="agent_call" class="form-label">Agent Call<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="agent_call" name="agent_call" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="agent_whatsapp" class="form-label">Agent WhatsApp<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="agent_whatsapp" name="agent_whatsapp" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="agent_email" class="form-label">Agent Email</label>
                                    <input type="email" class="form-control" id="agent_email" name="agent_email">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="agent_photo" class="form-label">Agent Photo<span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="agent_photo" name="agent_photo" >
                                    <input type="text" class="form-control mt-2" name="alt_texts[agent_photo]" placeholder="Alt text for agent photo">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="mobile_agent_photo" class="form-label">Mobile Agent Photo</label>
                                    <div class="mobile-image-upload" id="mobile-upload-mobile_agent_photo">
                                        <div class="mb-2">
                                            <div class="d-flex align-items-center">
                                                <input type="file" class="form-control mobile-image-input @error('mobile_agent_photo') is-invalid @enderror"
                                                    id="input-mobile_agent_photo" name="mobile_agent_photo" accept="image/*" capture="environment"
                                                    data-field="mobile_agent_photo"
                                                    onchange="handleMobileImageSelect(this)">
                                                @error('mobile_agent_photo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="compression-options mb-2 d-none">
                                            <div class="card p-3">
                                                <div class="mb-2">
                                                    <label class="form-label">Image Quality</label>
                                                    <input type="range" class="form-range quality-slider" min="10" max="100" value="70"
                                                        id="quality-mobile_agent_photo" data-field="mobile_agent_photo">
                                                    <div class="d-flex justify-content-between">
                                                        <small>Lower (Smaller File)</small>
                                                        <small class="quality-value">70%</small>
                                                        <small>Higher (Better Quality)</small>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label">Max Width</label>
                                                    <select class="form-select max-width" id="max-width-mobile_agent_photo" data-field="mobile_agent_photo" style="display: block !important; width: 100%;">
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
                                                        onclick="cancelCompression('mobile_agent_photo')">Cancel</button>
                                                    <button type="button" class="btn btn-primary btn-sm apply-compression"
                                                        onclick="applyCompression('mobile_agent_photo')">Apply & Upload</button>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="mobile_agent_photo_compressed" id="compressed-mobile_agent_photo" class="compressed-image-data">
                                        {{-- If editing, show preview --}}
                                        @if(isset($edit) && $edit && !empty($post->mobile_agent_photo))
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $post->mobile_agent_photo) }}" class="img-fluid img-thumbnail" style="max-height: 120px;">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="languages" class="form-label">languages<span class="text-danger">*</span></label>
                                    <div class="languages-repeater">
                                        <div data-repeater-list="languages">
                                            <div data-repeater-item class="repeater-item mb-2">
                                                <input type="text" class="form-control mb-2" name="languages" placeholder="languages" required>
                                                <button type="button" class="btn btn-danger" data-repeater-delete>
                                                    <i class="fas fa-trash-alt"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary mt-2" data-repeater-create>
                                            <i class="fas fa-plus"></i> Add languages
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="location_link" class="form-label">Location Link<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="location_link" name="location_link" required>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="location_id" class="form-label">Location</label>
                            <select class="form-control select2" id="location_id" name="location_id[]" >
                                <option value="">Select Location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="qr_photo" class="form-label">QR Photo<span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="qr_photo" name="qr_photo" required>
                    </div>
                    <div class="mb-3">
                        <label for="qr_photo" class="form-label">Qr Mobile Photo</label>
                        <div class="mobile-image-upload" id="qr-upload-qr_mobile_photo">
                            <div class="mb-2">
                                <div class="d-flex align-items-center">
                                    <input type="file" class="form-control mobile-image-input @error('qr_mobile_photo') is-invalid @enderror"
                                        id="input-qr_mobile_photo" name="qr_mobile_photo" accept="image/*" capture="environment"
                                        data-field="qr_mobile_photo"
                                        onchange="handleMobileImageSelect(this)">
                                    @error('qr_mobile_photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="compression-options mb-2 d-none">
                                <div class="card p-3">
                                    <div class="mb-2">
                                        <label class="form-label">Image Quality</label>
                                        <input type="range" class="form-range quality-slider" min="10" max="100" value="70"
                                            id="quality-qr_mobile_photo" data-field="qr_mobile_photo">
                                        <div class="d-flex justify-content-between">
                                            <small>Lower (Smaller File)</small>
                                            <small class="quality-value">70%</small>
                                            <small>Higher (Better Quality)</small>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Max Width</label>
                                        <select class="form-select max-width" id="max-width-qr_mobile_photo" data-field="qr_mobile_photo" style="display: block !important; width: 100%;">
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
                                            onclick="cancelCompression('qr_mobile_photo')">Cancel</button>
                                        <button type="button" class="btn btn-primary btn-sm apply-compression"
                                            onclick="applyCompression('qr_mobile_photo')">Apply & Upload</button>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="qr_mobile_photo_compressed" id="compressed-qr_mobile_photo" class="compressed-image-data">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="reference" class="form-label">Reference<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="reference" name="reference" required>
                    </div>
                    <div class="mb-3">
                        <label for="dld_permit_number" class="form-label">DLD Permit Number<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="dld_permit_number" name="dld_permit_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="is_top" class="form-label">Mark as Top Listing<span class="text-danger">*</span></label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_top" name="top" value="1">
                            <label class="form-check-label" for="is_top">Check this box to mark the property as a top listing</label>
                        </div>
                    </div>

                            <!-- Mobile Gallery -->
                            <div class="col-12">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">Mobile Gallery</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mobile-gallery-upload">
                                            <div class="row" id="mobile-gallery-preview">
                                                <!-- Gallery preview items will be added here -->
                                                <div class="col-md-3 mb-3">
                                                    <div class="gallery-upload-btn" style="height: 150px; border: 2px dashed #ccc; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer;" onclick="document.getElementById('mobile_gallery_images').click()">
                                                        <div class="text-center">
                                                            <i class="fas fa-plus-circle fa-2x text-muted"></i>
                                                            <p class="mb-0">Add Gallery Images</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="file" class="d-none" id="mobile_gallery_images" name="mobile_gallery_images[]" accept="image/*" multiple onchange="previewMobileGallery(event)">
                                            <div id="gallery-alt-texts-container" class="mt-3">
                                                <!-- Alt text inputs will be added here -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <div class="mb-3">
                        <label for="gallery" class="form-label">Gallery<span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="gallery" name="gallery_images[]" multiple>
                        <div id="gallery-alt-texts" class="mt-2">
                            <!-- Alt text inputs will be added here dynamically -->
                        </div>
                        <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#galleryModal">Manage Gallery</button>
                    </div>
                </div>

                <!-- Meta Data Tab -->
                @include('components.metadata-tab')
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Create Rental Resale</button>
            </div>
        </form>
    </div>

    <!-- Gallery Modal -->
    <div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="galleryModalLabel">Manage Gallery</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="gallery-images">
                        <!-- Gallery images will be loaded here dynamically -->
                    </div>
                    <div id="gallery-alt-texts-container" class="mt-3">
                        <!-- Alt text inputs will be added here dynamically -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden element to pass uploadedImages variable -->
    <div id="uploadedImages" style="display: none;">{{ json_encode($uploadedImages ?? []) }}</div>

    <script src="{{ asset('storage/admin/assets/rental_resale.js') }}"></script>
    <script>
        // Object to store original files for compression
        const originalFiles = {};

        // Initialize mobile upload functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Handle gallery image file input change
            const galleryInput = document.getElementById('gallery');
            const galleryAltTexts = document.getElementById('gallery-alt-texts');

            if (galleryInput && galleryAltTexts) {
                galleryInput.addEventListener('change', function() {
                    galleryAltTexts.innerHTML = ''; // Clear existing alt text inputs

                    Array.from(this.files).forEach((file, index) => {
                        const altTextDiv = document.createElement('div');
                        altTextDiv.className = 'mb-2';
                        altTextDiv.innerHTML = `
                            <input type="text"
                                   class="form-control"
                                   name="alt_texts[gallery_images][${index}]"
                                   placeholder="Alt text for ${file.name}">
                        `;
                        galleryAltTexts.appendChild(altTextDiv);
                    });
                });
            }

            // Initialize all mobile image uploaders
            initializeMobileUploaders();
        });

        // Initialize all mobile uploaders on the page
        function initializeMobileUploaders() {
            // Find all mobile image upload containers
            const uploaders = document.querySelectorAll('.mobile-image-upload');

            uploaders.forEach(uploader => {
                const input = uploader.querySelector('input[type="file"]');
                if (input) {
                    // Add event listener for file selection
                    input.addEventListener('change', function() {
                        handleMobileImageSelect(this);
                    });

                    // Initialize quality sliders
                    const qualitySlider = uploader.querySelector('.quality-slider');
                    if (qualitySlider) {
                        qualitySlider.addEventListener('input', function() {
                            const qualityValue = this.parentElement.querySelector('.quality-value');
                            if (qualityValue) {
                                qualityValue.textContent = this.value + '%';
                            }
                            const field = this.dataset.field || '';
                            if (field) {
                                previewCompressedImage(field);
                            }
                        });
                    }

                    // Initialize max width select
                    const maxWidthSelect = uploader.querySelector('.max-width');
                    if (maxWidthSelect) {
                        maxWidthSelect.addEventListener('change', function() {
                            const field = this.dataset.field || '';
                            if (field) {
                                previewCompressedImage(field);
                            }
                        });
                    }
                }
            });
        }

        // Handle mobile image file selection
        function handleMobileImageSelect(input) {
            if (!input || !input.files || input.files.length === 0) return;

            const file = input.files[0];
            const container = input.closest('.mobile-image-upload');
            if (!container) return;

            const fieldIdentifier = input.dataset.field || input.id.replace('input-', '');
            console.log('Handling image selection for field:', fieldIdentifier);

            // Store original file
            originalFiles[fieldIdentifier] = file;

            // Show compression options
            const options = container.querySelector('.compression-options');
            if (options) {
                options.classList.remove('d-none');

                // Initialize quality slider
                const qualitySlider = container.querySelector('.quality-slider');
                const qualityValue = container.querySelector('.quality-value');

                if (qualitySlider && qualityValue) {
                    qualitySlider.addEventListener('input', function() {
                        qualityValue.textContent = this.value + '%';
                        previewCompressedImage(fieldIdentifier);
                    });
                }

                // Initialize preview
                previewCompressedImage(fieldIdentifier);
            }
        }

        // Preview the compressed image with current settings
        function previewCompressedImage(fieldIdentifier) {
            const file = originalFiles[fieldIdentifier];
            if (!file) return;

            const container = document.querySelector(`[data-field="${fieldIdentifier}"]`).closest('.mobile-image-upload');
            if (!container) return;

            const qualitySlider = container.querySelector('.quality-slider');
            const widthSelect = container.querySelector('.max-width');
            const previewContainer = container.querySelector('.image-preview-container');
            const previewImage = container.querySelector('.preview-image');
            const fileInfo = container.querySelector('.file-info');

            if (!qualitySlider || !widthSelect || !previewContainer || !previewImage || !fileInfo) {
                console.error('Missing required elements for preview');
                return;
            }

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

                    // Store the compressed data URL for later use
                    const compressedInput = container.querySelector('.compressed-image-data');
                    if (compressedInput) {
                        compressedInput.value = compressedDataUrl;
                    }
                };
                img.onerror = function() {
                    console.error('Error loading image for preview');
                    fileInfo.textContent = 'Error: Could not load image for preview';
                };
                img.src = e.target.result;
            };
            reader.onerror = function() {
                console.error('Error reading file');
                if (fileInfo) {
                    fileInfo.textContent = 'Error: Could not read file';
                }
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

            const container = document.querySelector(`[data-field="${fieldIdentifier}"]`).closest('.mobile-image-upload');
            if (!container) {
                console.error('Container not found for field:', fieldIdentifier);
                return;
            }

            const fileInput = container.querySelector('input[type="file"]');
            const qualitySlider = container.querySelector('.quality-slider');
            const widthSelect = container.querySelector('.max-width');
            const hiddenInput = container.querySelector('.compressed-image-data');
            const options = container.querySelector('.compression-options');

            if (!fileInput || !qualitySlider || !widthSelect || !hiddenInput || !options) {
                console.error('Missing required elements for compression');
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
                    canvas.toBlob(function(blob) {
                        // Create a new file with the compressed image
                        const compressedFile = new File([blob], file.name, {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        });

                        // Create a new DataTransfer object and add the compressed file
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(compressedFile);

                        // Update the file input with the compressed file
                        fileInput.files = dataTransfer.files;

                        // Update the hidden input with the compressed data URL
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            hiddenInput.value = e.target.result;

                            // Show success message
                            options.innerHTML = `
                                <div class="alert alert-success">
                                    <strong>Image optimized successfully!</strong><br>
                                    File size reduced for better mobile performance.
                                </div>
                            `;

                            // Disable the file input to prevent changes
                            fileInput.disabled = true;

                            // Update the preview with the compressed image
                            const previewImage = container.querySelector('.preview-image');
                            if (previewImage) {
                                previewImage.src = e.target.result;
                            }

                            // Update file info
                            const originalSizeKB = Math.round(file.size / 1024);
                            const compressedSizeKB = Math.round(compressedFile.size / 1024);
                            const savedPercent = Math.round((1 - (compressedFile.size / file.size)) * 100);

                            const fileInfo = container.querySelector('.file-info');
                            if (fileInfo) {
                                fileInfo.textContent = `Original: ${originalSizeKB}KB → Compressed: ~${compressedSizeKB}KB (${savedPercent}% saved)`;
                            }
                        };
                        reader.readAsDataURL(compressedFile);

                    }, 'image/jpeg', quality);
                };
                img.onerror = function() {
                    console.error('Error loading image for compression');
                    if (options) {
                        options.innerHTML = '<div class="alert alert-danger">Error: Could not load image for compression</div>';
                    }
                };
                img.src = e.target.result;
            };
            reader.onerror = function() {
                console.error('Error reading file');
                if (options) {
                    options.innerHTML = '<div class="alert alert-danger">Error: Could not read file</div>';
                }
            };
            reader.readAsDataURL(file);
        }

        // Cancel compression and reset the file input
        function cancelCompression(fieldIdentifier) {
            console.log('Cancelling compression for field:', fieldIdentifier);

            // Find the container using the field identifier
            const input = document.querySelector(`[data-field="${fieldIdentifier}"]`);
            if (!input) {
                console.error('Input not found for field:', fieldIdentifier);
                return;
            }

            const container = input.closest('.mobile-image-upload');
            if (!container) {
                console.error('Container not found for field:', fieldIdentifier);
                return;
            }

            // Get elements
            const fileInput = container.querySelector('input[type="file"]');
            const options = container.querySelector('.compression-options');
            const previewContainer = container.querySelector('.image-preview-container');
            const hiddenInput = container.querySelector('.compressed-image-data');

            // Reset file input
            if (fileInput) {
                fileInput.value = '';
                fileInput.disabled = false;
            }

            // Hide options and preview
            if (options) {
                options.classList.add('d-none');
            }
            if (previewContainer) {
                previewContainer.classList.add('d-none');
            }

            // Clear hidden input
            if (hiddenInput) {
                hiddenInput.value = '';
            }

            // Remove from original files
            delete originalFiles[fieldIdentifier];

            console.log('Compression cancelled for field:', fieldIdentifier);
        }

        // Add this function to prevent JS error and provide basic preview
        function previewMobileGallery(event) {
            const input = event.target;
            const previewContainer = document.getElementById('mobile-gallery-preview');
            // Remove all previews except the upload button
            previewContainer.querySelectorAll('.gallery-image-preview').forEach(el => el.remove());

            if (input.files && input.files.length > 0) {
                Array.from(input.files).forEach((file, idx) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Insert preview before the upload button
                        const col = document.createElement('div');
                        col.className = 'col-md-3 mb-3 gallery-image-preview';
                        col.innerHTML = `
                            <div class="card">
                                <img src="${e.target.result}" class="card-img-top" style="height: 120px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <small class="text-muted">${file.name}</small>
                                </div>
                            </div>
                        `;
                        // Insert before the upload button (first child)
                        previewContainer.insertBefore(col, previewContainer.firstChild);
                    };
                    reader.readAsDataURL(file);
                });
            }
        }
    </script>
@endsection
