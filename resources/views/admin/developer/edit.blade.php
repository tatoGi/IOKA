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
                    <div id="photo-container">
                        @php
                            $photos = json_decode($developer->photo, true) ?? [];
                            if (!is_array($photos)) {
                                $photos = []; // Ensure $photos is always an array
                            }
                        @endphp

                        @foreach ($photos as $photo)
                            <div class="photo-input-group mb-3">
                                <input type="file" name="photo[][file]" class="form-control">
                                <input type="text" name="photo[][alt]" class="form-control mt-2"
                                    value="{{ $photo['alt'] ?? '' }}" placeholder="Alt text for this photo">
                                <img src="{{ asset('storage/' . ($photo['file'] ?? '')) }}" class="mt-2" width="100">
                                <button type="button" class="btn btn-danger remove-photo" data-photo="{{ $photo['file'] }}"
                                    data-developer-id="{{ $developer->id }}">Remove</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-photo" class="btn btn-secondary">Add Another Photo</button>
                </div>

                <div class="form-group mb-3">
                    <label for="logo">Logo</label>
                    <input type="file" name="logo" id="logo" class="form-control">
                    @if (isset($developer) && $developer->logo)
                        <img src="{{ asset('storage/' . $developer->logo) }}" class="mt-2" width="100">
                    @endif
                </div>
                <!-- Awards Section -->
                <div class="form-group mb-3">
                    <label for="awards">Awards</label>
                    <div id="awards-container">
                        @foreach ($awards as $index => $award)
                            <div class="award-input-group mb-3">
                                <input type="hidden" name="awards[{{ $index }}][id]" value="{{ $award->id }}">
                                <div class="form-group">
                                    <label for="award_title">Award Title</label>
                                    <input type="text" name="awards[{{ $index }}][title]" class="form-control"
                                        value="{{ $award->award_title }}">
                                </div>
                                <div class="form-group">
                                    <label for="award_year">Award Year</label>
                                    <input type="text" name="awards[{{ $index }}][year]" class="form-control"
                                        value="{{ $award->award_year }}">
                                </div>
                                <div class="form-group">
                                    <label for="award_description">Award Description</label>
                                    <textarea name="awards[{{ $index }}][description]" class="form-control editor">{{ $award->award_description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="award_photo">Award Photo</label>
                                    <input type="file" name="awards[{{ $index }}][photo]" class="form-control">
                                    @if ($award->award_photo)
                                        <img src="{{ asset('storage/' . $award->award_photo) }}" class="mt-2"
                                            width="100">
                                    @endif
                                </div>
                                <!-- Add a Remove Award button -->
                                <button type="button" class="btn btn-danger remove-award"
                                    data-award-id="{{ $award->id }}">Remove Award</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-award" class="btn btn-secondary">Add Another Award</button>
                </div>

                <!-- Tags and Listings -->
                @php
                    $developerTags = json_decode($developer->tags, true) ?? [];
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
                                {{ in_array($listing->id, $rentalListingsArray) ? 'selected' : '' }}>
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
                                {{ $developer->offplanListings->contains($listing->id) ? 'selected' : '' }}>
                                {{ $listing->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
    $('#rental_listings, #offplan_listings, .tags').select2({
        tags: true,
        tokenSeparators: [','],
        placeholder: 'Type or select tags',
        allowClear: true,
        theme: 'default',
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
});

        $(document).ready(function() {
            $('#add-photo').click(function() {
                $('#photo-container').append(`
                    <div class="photo-input-group mb-3">
                        <input type="file" name="photo[][file]" class="form-control">
                        <input type="text" name="photo[][alt]" class="form-control mt-2" placeholder="Alt text for this photo">
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
                const index = $('#awards-container .award-input-group').length;
                $('#awards-container').append(`
                    <div class="award-input-group mb-3">
                        <input type="hidden" name="awards[${index}][id]" value="">
                        <div class="form-group">
                            <label for="award_title">Award Title</label>
                            <input type="text" name="awards[${index}][title]" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="award_year">Award Year</label>
                            <input type="text" name="awards[${index}][year]" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="award_description">Award Description</label>
                            <textarea name="awards[${index}][description]" class="form-control editor"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="award_photo">Award Photo</label>
                            <input type="file" name="awards[${index}][photo]" class="form-control">
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
            const awardGroup = $(this).closest('.award-input-group'); // The award input group to remove

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
    </script>
@endsection
