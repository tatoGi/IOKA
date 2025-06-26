@extends('admin.layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Create Developer</h1>
        <div class="card-body">
            <form action="{{ route('admin.developer.store') }}" method="POST" enctype="multipart/form-data"
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
                        <!-- Title Field -->
                        <div class="form-group mb-3">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Slug Field -->
                        <div class="form-group mb-3">
                            <label for="slug">Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}" required>
                            @error('slug')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Paragraph Field -->
                        <div class="form-group mb-3">
                            <label for="paragraph">Paragraph <span class="text-danger">*</span></label>
                            <textarea name="paragraph" id="paragraph" class="form-control editor" required>{{ old('paragraph') }}</textarea>
                            @error('paragraph')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone and WhatsApp Fields -->
                        <div class="container mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" required>
                                        @error('phone')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="whatsapp">WhatsApp <span class="text-danger">*</span></label>
                                        <input type="text" name="whatsapp" id="whatsapp" class="form-control" value="{{ old('whatsapp') }}" required>
                                        @error('whatsapp')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Photos Field -->
                        <div class="form-group mb-3">
                            <label for="photo">Photos <span class="text-danger">*</span></label>
                            <div id="photo-container">
                                <div class="photo-input-group mb-3">
                                    <input type="file" name="photo[][file]" class="form-control">
                                    <input type="text" name="photo[][alt]" class="form-control mt-2" placeholder="Alt text for this photo" required>
                                    <button type="button" class="btn btn-danger btn-sm mt-2 remove-photo">Remove</button>
                                </div>
                            </div>
                            @error('photo')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <button type="button" id="add-photo" class="btn btn-secondary">Add Another Photo</button>
                        </div>

                        <!-- Mobile Photo Field -->
                        <div class="form-group mb-3">
                            <label for="mobile_photo">Mobile Photo</label>
                            <input type="file" name="mobile_photo" id="mobile_photo" class="form-control" accept="image/*" capture="environment">
                            <input type="text" name="mobile_photo_alt" class="form-control mt-2" placeholder="Alt text for mobile photo">
                            <div class="mt-2">
                                <label for="mobile_photo_quality" class="form-label">Compression Quality: <span id="mobile_photo_quality_value">80</span>%</label>
                                <input type="range" class="form-range" id="mobile_photo_quality" min="10" max="100" value="80">
                            </div>
                            <div class="mt-2">
                                <label for="mobile_photo_max_width" class="form-label">Max Width: <span id="mobile_photo_max_width_value">1200</span>px</label>
                                <select class="form-select" id="mobile_photo_max_width">
                                    <option value="800">800px</option>
                                    <option value="1000">1000px</option>
                                    <option value="1200" selected>1200px</option>
                                    <option value="1600">1600px</option>
                                </select>
                            </div>
                            <div id="mobile_photo_preview" class="mt-2"></div>
                            <input type="hidden" id="mobile_photo_compressed" name="mobile_photo_compressed">
                        </div>

                        <!-- Logo Field -->
                        <div class="form-group mb-3">
                            <label for="logo">Logo <span class="text-danger">*</span></label>
                            <input type="file" name="logo" id="logo" class="form-control mt-2" required>
                            <input type="text" name="logo_alt" class="form-control mt-2" placeholder="Alt text for logo">
                            @error('logo')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mobile Logo Field -->
                        <div class="form-group mb-3">
                            <label for="mobile_logo">Mobile Logo</label>
                            <input type="file" name="mobile_logo" id="mobile_logo" class="form-control" accept="image/*" capture="environment">
                            <input type="text" name="mobile_logo_alt" class="form-control mt-2" placeholder="Alt text for mobile logo">
                            <div class="mt-2">
                                <label for="mobile_logo_quality" class="form-label">Compression Quality: <span id="mobile_logo_quality_value">80</span>%</label>
                                <input type="range" class="form-range" id="mobile_logo_quality" min="10" max="100" value="80">
                            </div>
                            <div class="mt-2">
                                <label for="mobile_logo_max_width" class="form-label">Max Width: <span id="mobile_logo_max_width_value">1200</span>px</label>
                                <select class="form-select" id="mobile_logo_max_width">
                                    <option value="800">800px</option>
                                    <option value="1000">1000px</option>
                                    <option value="1200" selected>1200px</option>
                                    <option value="1600">1600px</option>
                                </select>
                            </div>
                            <div id="mobile_logo_preview" class="mt-2"></div>
                            <input type="hidden" id="mobile_logo_compressed" name="mobile_logo_compressed">
                        </div>

                        <!-- Mobile Banner Image Field -->
                        <div class="form-group mb-3">
                            <label for="mobile_banner_image">Mobile Banner Image</label>
                            <input type="file" name="mobile_banner_image" id="mobile_banner_image" class="form-control" accept="image/*" capture="environment">
                            <input type="text" name="mobile_banner_image_alt" class="form-control mt-2" placeholder="Alt text for mobile banner image">
                            <div class="mt-2">
                                <label for="mobile_banner_image_quality" class="form-label">Compression Quality: <span id="mobile_banner_image_quality_value">80</span>%</label>
                                <input type="range" class="form-range" id="mobile_banner_image_quality" min="10" max="100" value="80">
                            </div>
                            <div class="mt-2">
                                <label for="mobile_banner_image_max_width" class="form-label">Max Width: <span id="mobile_banner_image_max_width_value">1200</span>px</label>
                                <select class="form-select" id="mobile_banner_image_max_width">
                                    <option value="800">800px</option>
                                    <option value="1000">1000px</option>
                                    <option value="1200" selected>1200px</option>
                                    <option value="1600">1600px</option>
                                </select>
                            </div>
                            <div id="mobile_banner_image_preview" class="mt-2"></div>
                            <input type="hidden" id="mobile_banner_image_compressed" name="mobile_banner_image_compressed">
                        </div>

                        <!-- Awards Section -->
                        <div class="form-group mb-3">
                            <label for="awards">Awards</label>
                            <div id="awards-container">
                                <!-- Awards will be added here dynamically -->
                            </div>
                            <button type="button" id="add-award" class="btn btn-secondary">Add Award</button>
                        </div>

                        <!-- Tags Field -->
                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags <span class="text-danger">*</span></label>
                            <select class="form-control tags" id="tags" name="tags[]" multiple="multiple">
                                @if (old('tags'))
                                    @foreach (old('tags') as $tag)
                                        <option value="{{ $tag }}" selected>{{ $tag }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('tags')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Type to add new tags or select existing ones. Press Enter or comma to add.</small>
                        </div>

                        <!-- Rental Listings Field -->
                        <div class="form-group mb-3">
                            <label for="rental_listings">Rental Listings <span class="text-danger">*</span></label>
                            <select name="rental_listings[]" id="rental_listings" class="form-control" multiple>
                                @if($rentalandresaleListings->isEmpty())
                                    <option disabled>No available rental listings</option>
                                @else
                                    @foreach ($rentalandresaleListings as $listing)
                                        <option value="{{ $listing->id }}" {{ in_array($listing->id, old('rental_listings', [])) ? 'selected' : '' }}>{{ $listing->title }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('rental_listings')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Offplan Listings Field -->
                        <div class="form-group mb-3">
                            <label for="offplan_listings">Offplan Listings <span class="text-danger">*</span></label>
                            <select name="offplan_listings[]" id="offplan_listings" class="form-control" multiple>
                                @if($offplanListings->isEmpty())
                                    <option disabled>No available offplan listings</option>
                                @else
                                    @foreach ($offplanListings as $listing)
                                        <option value="{{ $listing->id }}" {{ in_array($listing->id, old('offplan_listings', [])) ? 'selected' : '' }}>{{ $listing->title }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('offplan_listings')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Meta Data Tab -->
                    <div class="tab-pane" id="metadata-tab" role="tabpanel">
                        <x-metadata-form :model="new App\Models\Developer" />
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Create Developer</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Debug jQuery and Select2 availability
            console.log('jQuery version:', $.fn.jquery);
            console.log('Select2 version:', $.fn.select2.amd.require.version);

            // Initialize Select2 for rental listings
            try {
                const rentalSelect = $('#rental_listings');
                console.log('Rental select element:', rentalSelect.length);
                rentalSelect.select2({
                    width: '100%',
                    placeholder: 'Select rental listings',
                    dropdownParent: rentalSelect.parent()
                });
            } catch (error) {
                console.error('Error initializing rental listings select2:', error);
            }

            // Initialize Select2 for offplan listings
            try {
                const offplanSelect = $('#offplan_listings');
                console.log('Offplan select element:', offplanSelect.length);
                offplanSelect.select2({
                    width: '100%',
                    placeholder: 'Select offplan listings',
                    dropdownParent: offplanSelect.parent()
                });
            } catch (error) {
                console.error('Error initializing offplan listings select2:', error);
            }

            // Initialize Select2 for tags
            try {
                const tagsSelect = $('.tags');
                console.log('Tags select element:', tagsSelect.length);
                tagsSelect.select2({
                    tags: true,
                    tokenSeparators: [','],
                    placeholder: 'Type or select tags',
                    allowClear: true,
                    theme: 'default',
                    width: '100%',
                    minimumInputLength: 1,
                    dropdownParent: tagsSelect.parent(),
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
                console.error('Error initializing tags select2:', error);
            }

            // Add more photo inputs
            $('#add-photo').click(function() {
                $('#photo-container').append(`
                    <div class="photo-input-group mb-3">
                        <input type="file" name="photo[][file]" class="form-control">
                        <input type="text" name="photo[][alt]" class="form-control mt-2" placeholder="Alt text for this photo" required>
                        <button type="button" class="btn btn-danger btn-sm mt-2 remove-photo">Remove</button>
                    </div>
                `);
            });

            // Remove photo input group
            $(document).on('click', '.remove-photo', function() {
                $(this).closest('.photo-input-group').remove();
            });

            let awardIndex = 0;

            $('#add-award').click(function() {
                $('#awards-container').append(`
                    <div class="award-item mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Award Title</label>
                                <input type="text" name="awards[${awardIndex}][title]" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label>Award Year</label>
                                <input type="number" name="awards[${awardIndex}][year]" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label>Award Description</label>
                                <textarea name="awards[${awardIndex}][description]" class="form-control editor"></textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label>Award Photo</label>
                                <input type="file" name="awards[${awardIndex}][photo]" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label>Photo Alt Text</label>
                                <input type="text" name="awards[${awardIndex}][photo_alt]" class="form-control" placeholder="Alt text for award photo">
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm mt-2 remove-award">Remove Award</button>
                    </div>
                `);
                awardIndex++;
            });

            $(document).on('click', '.remove-award', function() {
                $(this).closest('.award-item').remove();
            });

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
                        
                        // Store compressed image data
                        document.getElementById('mobile_photo_compressed').value = compressedDataUrl;
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
                        
                        // Store compressed image data
                        document.getElementById('mobile_logo_compressed').value = compressedDataUrl;
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
                        
                        // Store compressed image data
                        document.getElementById('mobile_banner_image_compressed').value = compressedDataUrl;
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
        });
    </script>
@endpush
