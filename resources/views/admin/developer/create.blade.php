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
                            <input type="text" name="photo[][alt]" class="form-control mt-2" placeholder="Alt text for this photo">
                            <button type="button" class="btn btn-danger btn-sm mt-2 remove-photo">Remove</button>
                        </div>
                    </div>
                    @error('photo')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <button type="button" id="add-photo" class="btn btn-secondary">Add Another Photo</button>
                </div>

                <!-- Logo Field -->
                <div class="form-group mb-3">
                    <label for="logo">Logo <span class="text-danger">*</span></label>
                    <input type="file" name="logo" id="logo" class="form-control mt-2" required>
                    @error('logo')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Awards Section -->
                <div class="form-group mb-3">
                    <label for="awards">Awards</label>
                    <div id="awards-container">
                        <div class="award-input-group mb-3">
                            <div class="form-group">
                                <label for="award_title">Award Title</label>
                                <input type="text" name="awards[0][title]" class="form-control" value="{{ old('awards.0.title') }}">
                            </div>
                            <div class="form-group">
                                <label for="award_year">Award Year</label>
                                <input type="text" name="awards[0][year]" class="form-control" value="{{ old('awards.0.year') }}">
                            </div>
                            <div class="form-group">
                                <label for="award_description">Award Description</label>
                                <textarea name="awards[0][description]" class="form-control editor">{{ old('awards.0.description') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="award_photo">Award Photo</label>
                                <input type="file" name="awards[0][photo]" class="form-control">
                            </div>
                            <button type="button" class="btn btn-danger btn-sm remove-award">Remove</button>
                        </div>
                    </div>
                    @error('awards')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <button type="button" id="add-award" class="btn btn-secondary">Add Another Award</button>
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

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#rental_listings').select2();
            $('#offplan_listings').select2();
            $('.tags').select2({
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

            // Add more photo inputs
            $('#add-photo').click(function() {
                $('#photo-container').append(`
                    <div class="photo-input-group mb-3">
                        <input type="file" name="photo[][file]" class="form-control">
                        <input type="text" name="photo[][alt]" class="form-control mt-2" placeholder="Alt text for this photo">
                        <button type="button" class="btn btn-danger btn-sm mt-2 remove-photo">Remove</button>
                    </div>
                `);
            });

            // Remove photo input group
            $(document).on('click', '.remove-photo', function() {
                $(this).closest('.photo-input-group').remove();
            });

            // Add more award inputs
            let awardIndex = 1; // Start from 1 since the first award is already present

            $('#add-award').click(function() {
                $('#awards-container').append(`
                    <div class="award-input-group mb-3">
                        <div class="form-group">
                            <label for="award_title">Award Title</label>
                            <input type="text" name="awards[${awardIndex}][title]" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="award_year">Award Year</label>
                            <input type="text" name="awards[${awardIndex}][year]" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="award_description">Award Description</label>
                            <textarea name="awards[${awardIndex}][description]" class="form-control editor"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="award_photo">Award Photo</label>
                            <input type="file" name="awards[${awardIndex}][photo]" class="form-control">
                        </div>
                        <button type="button" class="btn btn-danger btn-sm remove-award">Remove</button>
                    </div>
                `);

                // Reinitialize TinyMCE for the new textarea
                tinymce.init({
                    selector: '.editor',
                    // Add other TinyMCE configuration options here
                });

                awardIndex++; // Increment the index for the next award
            });

            // Remove award input group
            $(document).on('click', '.remove-award', function() {
                $(this).closest('.award-input-group').remove();
            });
        });
    </script>
@endsection
