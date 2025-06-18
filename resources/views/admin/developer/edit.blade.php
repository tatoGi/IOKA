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

                        <!-- Meta Data Tab -->
                        <div class="tab-pane" id="metadata-tab" role="tabpanel">
                            <div class="mb-3">
                                <label for="metadata[meta_title]" class="form-label">Meta Title</label>
                                <input type="text" class="form-control @error('metadata.meta_title') is-invalid @enderror"
                                    id="metadata[meta_title]" name="metadata[meta_title]"
                                    value="{{ old('metadata.meta_title', $developer->metadata?->meta_title) }}">
                                @error('metadata.meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="metadata[meta_description]" class="form-label">Meta Description</label>
                                <textarea class="form-control @error('metadata.meta_description') is-invalid @enderror"
                                    id="metadata[meta_description]" name="metadata[meta_description]" rows="3"
                                    >{{ old('metadata.meta_description', $developer->metadata?->meta_description) }}</textarea>
                                @error('metadata.meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="metadata[meta_keywords]" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control @error('metadata.meta_keywords') is-invalid @enderror"
                                    id="metadata[meta_keywords]" name="metadata[meta_keywords]"
                                    value="{{ old('metadata.meta_keywords', $developer->metadata?->meta_keywords) }}">
                                @error('metadata.meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <h4 class="mt-4">Open Graph</h4>
                            <div class="mb-3">
                                <label for="metadata[og_title]" class="form-label">OG Title</label>
                                <input type="text" class="form-control @error('metadata.og_title') is-invalid @enderror"
                                    id="metadata[og_title]" name="metadata[og_title]"
                                    value="{{ old('metadata.og_title', $developer->metadata?->og_title) }}">
                                @error('metadata.og_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="metadata[og_description]" class="form-label">OG Description</label>
                                <textarea class="form-control @error('metadata.og_description') is-invalid @enderror"
                                    id="metadata[og_description]" name="metadata[og_description]" rows="3"
                                    >{{ old('metadata.og_description', $developer->metadata?->og_description) }}</textarea>
                                @error('metadata.og_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="og_image" class="form-label">OG Image</label>
                                <input type="file" class="form-control @error('metadata.og_image') is-invalid @enderror"
                                    id="og_image" name="og_image">
                                @error('metadata.og_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($developer->metadata?->og_image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $developer->metadata->og_image) }}" class="img-thumbnail" width="200">
                                        <button type="button" class="btn btn-danger mt-2" id="remove-og-image-btn">Remove OG Image</button>
                                    </div>
                                @endif
                            </div>

                            <h4 class="mt-4">Twitter Card</h4>
                            <div class="mb-3">
                                <label for="metadata[twitter_card]" class="form-label">Twitter Card Type</label>
                                <select class="form-control @error('metadata.twitter_card') is-invalid @enderror"
                                    id="metadata[twitter_card]" name="metadata[twitter_card]">
                                    <option value="summary" {{ old('metadata.twitter_card', $developer->metadata?->twitter_card) == 'summary' ? 'selected' : '' }}>Summary</option>
                                    <option value="summary_large_image" {{ old('metadata.twitter_card', $developer->metadata?->twitter_card) == 'summary_large_image' ? 'selected' : '' }}>Summary Large Image</option>
                                </select>
                                @error('metadata.twitter_card')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="metadata[twitter_title]" class="form-label">Twitter Title</label>
                                <input type="text" class="form-control @error('metadata.twitter_title') is-invalid @enderror"
                                    id="metadata[twitter_title]" name="metadata[twitter_title]"
                                    value="{{ old('metadata.twitter_title', $developer->metadata?->twitter_title) }}">
                                @error('metadata.twitter_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="metadata[twitter_description]" class="form-label">Twitter Description</label>
                                <textarea class="form-control @error('metadata.twitter_description') is-invalid @enderror"
                                    id="metadata[twitter_description]" name="metadata[twitter_description]" rows="3"
                                    >{{ old('metadata.twitter_description', $developer->metadata?->twitter_description) }}</textarea>
                                @error('metadata.twitter_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="twitter_image" class="form-label">Twitter Image</label>
                                <input type="file" class="form-control @error('metadata.twitter_image') is-invalid @enderror"
                                    id="twitter_image" name="twitter_image">
                                @error('metadata.twitter_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($developer->metadata?->twitter_image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $developer->metadata->twitter_image) }}" class="img-thumbnail" width="200">
                                        <button type="button" class="btn btn-danger mt-2" id="remove-twitter-image-btn">Remove Twitter Image</button>
                                    </div>
                                @endif
                            </div>
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
        document.getElementById('remove-og-image-btn')?.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove the OG image?')) {
                fetch('{{ route('admin.developer.delete-og-image', ['developer' => $developer]) }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('OG image removed successfully.');
                        location.reload();
                    } else {
                        alert('Failed to remove OG image.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });

        document.getElementById('remove-twitter-image-btn')?.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove the Twitter image?')) {
                fetch('{{ route('admin.developer.delete-twitter-image', ['developer' => $developer]) }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ type: 'twitter_image' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Failed to remove Twitter image.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    </script>
@endpush
