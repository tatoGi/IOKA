@extends('admin.layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Edit Developer</h1>
        <div class="card-body">
            <form action="{{ route('admin.developer.update', $developer->id) }}" method="POST" enctype="multipart/form-data"
                class="shadow p-4 rounded bg-light">
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
                        <!-- Existing fields for title, slug, paragraph, etc. -->
                        <div class="form-group mb-3">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ $developer->title }}"
                                required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="slug">Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" id="slug" class="form-control" value="{{ $developer->slug }}"
                                required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="paragraph">Paragraph <span class="text-danger">*</span></label>
                            <textarea name="paragraph" id="paragraph" class="form-control editor" required>{{ $developer->paragraph }}</textarea>
                        </div>
                        <div class="container mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone <span class="text-danger">*</span> </label>
                                        <input type="text" name="phone" id="phone" class="form-control"
                                            value="{{ $developer->phone }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="whatsapp">WhatsApp <span class="text-danger">*</span></label>
                                        <input type="text" name="whatsapp" id="whatsapp" class="form-control"
                                            value="{{ $developer->whatsapp }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="photo">Photos</label>
                            <div id="photo-container" class="row">


                                @foreach ($photos as $photo)
                                    <div class="col-md-4 mb-3">
                                        <div class="gallery-image-wrapper">
                                            <img src="{{ asset('storage/' . ($photo['file'] ?? '')) }}" class="img-fluid" alt="{{ $photo['alt'] ?? '' }}">
                                            <input type="file" name="photo[][file]" class="form-control mt-2">
                                            <input type="text" name="photo[][alt]" class="form-control mt-2"
                                                value="{{ $photo['alt'] ?? '' }}" placeholder="Alt text for this photo">
                                            <button type="button" class="remove-gallery-image" data-photo="{{ $photo['file'] }}"
                                                data-developer-id="{{ $developer->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" id="add-photo" class="btn btn-secondary mt-3">Add Another Photo</button>
                        </div>

                        <div class="form-group mb-3">
                            <label for="logo">Logo</label>
                            <input type="file" name="logo" id="logo" class="form-control">
                            <input type="text" name="logo_alt" class="form-control mt-2" value="{{ $developer->logo_alt ?? '' }}" placeholder="Alt text for logo">
                            @if (isset($developer) && $developer->logo)
                                <img src="{{ asset('storage/' . $developer->logo) }}" class="mt-2" width="100" alt="{{ $developer->logo_alt ?? '' }}">
                            @endif
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="mobile_photo">Mobile Photo (optimized for mobile)</label>
                            <input type="file" name="mobile_photo" id="mobile_photo" class="form-control" accept="image/*" capture="environment">
                            <input type="text" name="mobile_photo_alt" class="form-control mt-2" value="{{ $developer->mobile_photo_alt ?? '' }}" placeholder="Alt text for mobile photo">
                            
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label>Compression Quality: <span id="mobile_photo_quality_value">80</span>%</label>
                                    <input type="range" id="mobile_photo_quality" class="form-range" min="10" max="100" value="80">
                                </div>
                                <div class="col-md-6">
                                    <label>Max Width: <span id="mobile_photo_max_width_value">1200</span>px</label>
                                    <select id="mobile_photo_max_width" class="form-select">
                                        <option value="800">800px</option>
                                        <option value="1000">1000px</option>
                                        <option value="1200" selected>1200px</option>
                                        <option value="1500">1500px</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div id="mobile_photo_preview" class="mt-2">
                                @if (isset($developer) && $developer->mobile_photo)
                                    <img src="{{ asset('storage/' . $developer->mobile_photo) }}" class="img-thumbnail" style="max-height: 150px;" alt="{{ $developer->mobile_photo_alt ?? '' }}">
                                    <button type="button" class="btn btn-sm btn-danger mt-1" onclick="deleteMobilePhoto({{ $developer->id }})">Remove Mobile Photo</button>
                                @endif
                            </div>
                            
                            <input type="hidden" name="mobile_photo_compressed" id="mobile_photo_compressed">
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="mobile_logo">Mobile Logo (optimized for mobile)</label>
                            <input type="file" name="mobile_logo" id="mobile_logo" class="form-control" accept="image/*" capture="environment">
                            <input type="text" name="mobile_logo_alt" class="form-control mt-2" value="{{ $developer->mobile_logo_alt ?? '' }}" placeholder="Alt text for mobile logo">
                            
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label>Compression Quality: <span id="mobile_logo_quality_value">80</span>%</label>
                                    <input type="range" id="mobile_logo_quality" class="form-range" min="10" max="100" value="80">
                                </div>
                                <div class="col-md-6">
                                    <label>Max Width: <span id="mobile_logo_max_width_value">800</span>px</label>
                                    <select id="mobile_logo_max_width" class="form-select">
                                        <option value="400">400px</option>
                                        <option value="600">600px</option>
                                        <option value="800" selected>800px</option>
                                        <option value="1000">1000px</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div id="mobile_logo_preview" class="mt-2">
                                @if (isset($developer) && $developer->mobile_logo)
                                    <img src="{{ asset('storage/' . $developer->mobile_logo) }}" class="img-thumbnail" style="max-height: 150px;" alt="{{ $developer->mobile_logo_alt ?? '' }}">
                                    <button type="button" class="btn btn-sm btn-danger mt-1" onclick="deleteMobileLogo({{ $developer->id }})">Remove Mobile Logo</button>
                                @endif
                            </div>
                            
                            <input type="hidden" name="mobile_logo_compressed" id="mobile_logo_compressed">
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="banner_image">Banner Image</label>
                            <input type="file" name="banner_image" id="banner_image" class="form-control">
                            @if (isset($developer) && $developer->banner_image)
                                <img src="{{ asset('storage/' . $developer->banner_image) }}" class="mt-2" width="200" alt="Banner Image">
                            @endif
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="mobile_banner_image">Mobile Banner Image (optimized for mobile)</label>
                            <input type="file" name="mobile_banner_image" id="mobile_banner_image" class="form-control" accept="image/*" capture="environment">
                            <input type="text" name="mobile_banner_image_alt" class="form-control mt-2" value="{{ $developer->mobile_banner_image_alt ?? '' }}" placeholder="Alt text for mobile banner image">
                            
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label>Compression Quality: <span id="mobile_banner_image_quality_value">80</span>%</label>
                                    <input type="range" id="mobile_banner_image_quality" class="form-range" min="10" max="100" value="80">
                                </div>
                                <div class="col-md-6">
                                    <label>Max Width: <span id="mobile_banner_image_max_width_value">1500</span>px</label>
                                    <select id="mobile_banner_image_max_width" class="form-select">
                                        <option value="1000">1000px</option>
                                        <option value="1200">1200px</option>
                                        <option value="1500" selected>1500px</option>
                                        <option value="2000">2000px</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div id="mobile_banner_image_preview" class="mt-2">
                                @if (isset($developer) && $developer->mobile_banner_image)
                                    <img src="{{ asset('storage/' . $developer->mobile_banner_image) }}" class="img-thumbnail" style="max-height: 150px;" alt="{{ $developer->mobile_banner_image_alt ?? '' }}">
                                    <button type="button" class="btn btn-sm btn-danger mt-1" onclick="deleteMobileBannerImage({{ $developer->id }})">Remove Mobile Banner Image</button>
                                @endif
                            </div>
                            
                            <input type="hidden" name="mobile_banner_image_compressed" id="mobile_banner_image_compressed">
                        </div>

                        <!-- Awards Section -->
                        <div class="form-group mb-3">
                            <label for="awards">Awards</label>
                            <div id="awards-container">
                                @foreach ($developer->awards as $index => $award)
                                <div class="award-item mb-3">
                                    <input type="hidden" name="awards[{{ $index }}][id]" value="{{ $award->id }}">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Award Title</label>
                                            <input type="text" name="awards[{{ $index }}][title]" class="form-control" value="{{ $award->award_title }}" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Award Year</label>
                                            <input type="number" name="awards[{{ $index }}][year]" class="form-control" value="{{ $award->award_year }}" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Award Description</label>
                                            <textarea name="awards[{{ $index }}][description]" class="form-control editor">{{ $award->award_description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label>Award Photo</label>
                                            <input type="file" name="awards[{{ $index }}][photo]" class="form-control">
                                            @if($award->award_photo)
                                                <img src="{{ asset('storage/' . $award->award_photo) }}" alt="{{ $award->photoAlt?->alt_text }}" class="img-thumbnail mt-2" style="max-width: 200px;">
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label>Photo Alt Text</label>
                                            <input type="text" name="awards[{{ $index }}][photo_alt]" class="form-control" value="{{ $award->photoAlt?->alt_text }}" placeholder="Alt text for award photo">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm mt-2 remove-award">Remove Award</button>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" id="add-award" class="btn btn-secondary">Add Another Award</button>
                        </div>

                       <!-- Tags and Listings -->
                       @php
                            $developerTags = $developer->tags ?? [];
                            if (is_string($developerTags)) {
                                $developerTags = is_array($developerTags) ? $developerTags : (json_decode($developerTags, true) ?? []);
                            }
                            if (!is_array($developerTags)) {
                                $developerTags = [];
                            }
                        @endphp
                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <select class="form-control tags" id="tags" name="tags[]" multiple="multiple">
                                @foreach ($developerTags as $tag)
                                    <option value="{{ $tag }}" selected>{{ $tag }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="rental_listings">Rental Listings</label>
                            <select name="rental_listings[]" id="rental_listings" class="form-control" multiple>
                                @foreach ($rentalListings as $listing)
                                    <option value="{{ $listing->id }}"
                                        {{ in_array($listing->id, $developer->rental_listings ?? []) ? 'selected' : '' }}>
                                        {{ $listing->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="offplan_listings">Offplan Listings</label>
                            <select name="offplan_listings[]" id="offplan_listings" class="form-control" multiple>
                                @foreach ($offplanListings as $listing)
                                    <option value="{{ $listing->id }}"
                                        {{ in_array($listing->id, $developer->offplan_listings ?? []) ? 'selected' : '' }}>
                                        {{ $listing->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Meta Data Tab -->
                        <div class="tab-pane" id="metadata-tab" role="tabpanel">
                            <x-metadata-form :model="$developer" />
                        </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('storage/admin/assets/developer.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for all select elements
            try {
                // Initialize rental listings
                $('#rental_listings').select2({
                    tags: true,
                    tokenSeparators: [','],
                    placeholder: 'Select rental listings',
                    allowClear: true,
                    width: '100%'
                });

                // Initialize offplan listings
                $('#offplan_listings').select2({
                    tags: true,
                    tokenSeparators: [','],
                    placeholder: 'Select offplan listings',
                    allowClear: true,
                    width: '100%'
                });

                // Initialize tags
                $('.tags').select2({
                    tags: true,
                    tokenSeparators: [','],
                    placeholder: 'Type or select tags',
                    allowClear: true,
                    width: '100%',
                    minimumInputLength: 1,
                    createTag: function(params) {
                        if (params.term.trim() === '') {
                            return null;
                        }
                        return {
                            id: params.term,
                            text: params.term,
                            newTag: true
                        };
                    }
                });
            } catch (error) {
                console.error('Error initializing Select2:', error);
            }

            $('#add-photo').click(function() {
                $('#photo-container').append(`
                    <div class="photo-input-group mb-3">
                        <input type="file" name="photo[][file]" class="form-control">
                        <input type="text" name="photo[][alt]" class="form-control mt-2" placeholder="Alt text for this photo" required>
                        <button type="button" class="btn btn-danger remove-photo">Remove</button>
                    </div>
                `);
            });
            $(document).on('click', '.remove-photo', function() {
                const photoPath = $(this).data('photo'); // Get the photo path (for backend)
                const developerId = $(this).data('developer-id'); // Get the developer ID
                const photoGroup = $(this).closest('.photo-input-group'); // The photo input group to remove
                console.log(photoGroup, photoPath);
                // Send an AJAX request to remove the photo from the server and database
                if (photoPath && developerId) {
                    $.ajax({
                        url: '/ioka_admin/developer/delete-photo', // Define your route for deleting photos
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}', // Include CSRF token
                            developer_id: developerId, // Include developer ID
                            photo_path: photoPath // Include photo path
                        },
                        success: function(response) {
                            // If successful, remove the photo group from the DOM
                            photoGroup.remove();
                        },
                        error: function(error) {
                            alert('Error removing photo');
                        }
                    });
                } else {
                    // If there's no photo path or developer ID, just remove the input group (newly added photo)
                    photoGroup.remove();
                }
            });
            // Add more award inputs
            $('#add-award').click(function() {
                const index = $('#awards-container .award-item').length;
                $('#awards-container').append(`
                    <div class="award-item mb-3">
                        <input type="hidden" name="awards[${index}][id]" value="">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Award Title</label>
                                <input type="text" name="awards[${index}][title]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Award Year</label>
                                <input type="number" name="awards[${index}][year]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Award Description</label>
                                <input type="text" name="awards[${index}][description]" class="form-control">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label>Award Photo</label>
                                <input type="file" name="awards[${index}][photo]" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label>Photo Alt Text</label>
                                <input type="text" name="awards[${index}][photo_alt]" class="form-control" placeholder="Alt text for award photo">
                            </div>
                        </div>
                    </div>
                `);

                // Reinitialize TinyMCE for the new textarea
                tinymce.init({
                    selector: '.editor',
                    // Add other TinyMCE configuration options here
                });
            });
        });
        $(document).on('click', '.remove-award', function() {
            const awardId = $(this).data('award-id'); // Get the award ID (for backend)
            const awardGroup = $(this).closest('.award-item'); // The award input group to remove

            // If the award has an ID, send an AJAX request to delete it from the database
            if (awardId) {
                $.ajax({
                    url: '/ioka_admin/developer/delete-award', // Define your route for deleting awards
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Include CSRF token
                        award_id: awardId
                    },
                    success: function(response) {
                        // If successful, remove the award group from the DOM
                        awardGroup.remove();
                    },
                    error: function(error) {
                        alert('Error removing award');
                    }
                });
            } else {
                // If there's no award ID, just remove the input group (newly added award)
                awardGroup.remove();
            }
        });
        // OG and Twitter image removal is now handled by the metadata-form component
        
        // Mobile Photo Handling
        document.getElementById('mobile_photo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    const quality = parseInt(document.getElementById('mobile_photo_quality').value) / 100;
                    const maxWidth = parseInt(document.getElementById('mobile_photo_max_width').value);
                    
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
                    const preview = document.getElementById('mobile_photo_preview');
                    preview.innerHTML = `<img src="${compressedDataUrl}" class="img-thumbnail" style="max-height: 150px;">`;
                    
                    // Store compressed image data - ONLY for mobile_photo
                    document.getElementById('mobile_photo_compressed').value = compressedDataUrl;
                    console.log('Mobile photo compressed data set:', compressedDataUrl.substring(0, 50) + '...');
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });

        // Mobile Logo Handling
        document.getElementById('mobile_logo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    const quality = parseInt(document.getElementById('mobile_logo_quality').value) / 100;
                    const maxWidth = parseInt(document.getElementById('mobile_logo_max_width').value);
                    
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
                    const preview = document.getElementById('mobile_logo_preview');
                    preview.innerHTML = `<img src="${compressedDataUrl}" class="img-thumbnail" style="max-height: 150px;">`;
                    
                    // Store compressed image data - ONLY for mobile_logo
                    document.getElementById('mobile_logo_compressed').value = compressedDataUrl;
                    // Make sure we're not affecting other fields
                    console.log('Mobile logo compressed data set:', compressedDataUrl.substring(0, 50) + '...');
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });

        // Mobile Banner Image Handling
        document.getElementById('mobile_banner_image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    const quality = parseInt(document.getElementById('mobile_banner_image_quality').value) / 100;
                    const maxWidth = parseInt(document.getElementById('mobile_banner_image_max_width').value);
                    
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
                    const preview = document.getElementById('mobile_banner_image_preview');
                    preview.innerHTML = `<img src="${compressedDataUrl}" class="img-thumbnail" style="max-height: 150px;">`;
                    
                    // Store compressed image data - ONLY for mobile_banner_image
                    document.getElementById('mobile_banner_image_compressed').value = compressedDataUrl;
                    // Make sure we're not affecting other fields
                    console.log('Mobile banner image compressed data set:', compressedDataUrl.substring(0, 50) + '...');
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });

        // Update quality and max width display values
        document.getElementById('mobile_photo_quality').addEventListener('input', function() {
            document.getElementById('mobile_photo_quality_value').textContent = this.value;
        });

        document.getElementById('mobile_photo_max_width').addEventListener('change', function() {
            document.getElementById('mobile_photo_max_width_value').textContent = this.value;
        });

        document.getElementById('mobile_logo_quality').addEventListener('input', function() {
            document.getElementById('mobile_logo_quality_value').textContent = this.value;
        });

        document.getElementById('mobile_logo_max_width').addEventListener('change', function() {
            document.getElementById('mobile_logo_max_width_value').textContent = this.value;
        });

        document.getElementById('mobile_banner_image_quality').addEventListener('input', function() {
            document.getElementById('mobile_banner_image_quality_value').textContent = this.value;
        });

        document.getElementById('mobile_banner_image_max_width').addEventListener('change', function() {
            document.getElementById('mobile_banner_image_max_width_value').textContent = this.value;
        });
        
        // Mobile image deletion functions
        function deleteMobilePhoto(developerId) {
            if (confirm('Are you sure you want to delete this mobile photo?')) {
                $.ajax({
                    url: `/ioka_admin/developer/${developerId}/delete-mobile-photo`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#mobile_photo_preview').html('');
                            document.getElementById('mobile_photo_compressed').value = '';
                            alert('Mobile photo deleted successfully');
                        } else {
                            alert('Error: ' + (response.message || 'Error deleting mobile photo'));
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error deleting mobile photo';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        alert('Error: ' + errorMsg);
                    }
                });
            }
        }
        
        function deleteMobileLogo(developerId) {
            if (confirm('Are you sure you want to delete this mobile logo?')) {
                $.ajax({
                    url: `/ioka_admin/developer/${developerId}/delete-mobile-logo`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#mobile_logo_preview').html('');
                            alert('Mobile logo deleted successfully');
                        } else {
                            alert('Error deleting mobile logo');
                        }
                    },
                    error: function() {
                        alert('Error deleting mobile logo');
                    }
                });
            }
        }
        
        function deleteMobileBannerImage(developerId) {
            if (confirm('Are you sure you want to delete this mobile banner image?')) {
                $.ajax({
                    url: `/ioka_admin/developer/${developerId}/delete-mobile-banner-image`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#mobile_banner_image_preview').html('');
                            alert('Mobile banner image deleted successfully');
                        } else {
                            alert('Error deleting mobile banner image');
                        }
                    },
                    error: function() {
                        alert('Error deleting mobile banner image');
                    }
                });
            }
        }
    </script>
@endpush
