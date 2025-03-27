@extends('admin.layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Edit Rental Resale Post</h1>
        <form action="{{ route('admin.postypes.rental_resale.update', $rentalResale->id) }}" method="POST"
            enctype="multipart/form-data" class="shadow p-4 rounded bg-light">
            @csrf
            @method('PUT')
            <input type="hidden" id="postId" value="{{ $rentalResale->id }}">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="mb-3">
                <label for="tags" class="form-label">Tags <span class="text-danger">*</span></label>
                <select class="form-control select2" id="tags" name="tags[]" multiple required>
                    <option value="6" {{ in_array(6, (array) $rentalResale->tags) ? 'selected' : '' }}>Resale</option>
                    <option value="5" {{ in_array(5, (array) $rentalResale->tags) ? 'selected' : '' }}>Rental</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Amount (in dollars)</label>
                <input type="number" step="0.01" class="form-control" id="amount" name="amount"
                    value="{{ $rentalResale->amount->amount }}" required>
            </div>
            <div class="mb-3">
                <label for="amount_dirhams" class="form-label">Amount (in Dirhams)</label>
                <input type="number" step="0.01" class="form-control" id="amount_dirhams" name="amount_dirhams"
                    value="{{ $rentalResale->amount->amount_dirhams }}" readonly>
            </div>
            <div class="mb-3">
                <label for="property_type" class="form-label">Property Type<span class="text-danger">*</span></label>
                <select class="form-control select2" id="property_type" name="property_type" required>
                    <option value="Villa" {{ $rentalResale->property_type == 'Villa' ? 'selected' : '' }}>Villa</option>
                    <option value="Townhouse" {{ $rentalResale->property_type == 'Townhouse' ? 'selected' : '' }}>Townhouse
                    </option>
                    <option value="Apartment" {{ $rentalResale->property_type == 'Apartment' ? 'selected' : '' }}>Apartment
                    </option>
                    <option value="Land" {{ $rentalResale->property_type == 'Land' ? 'selected' : '' }}>Land</option>
                    <option value="Full Building" {{ $rentalResale->property_type == 'Full Building' ? 'selected' : '' }}>
                        Full Building</option>
                    <option value="Commercial" {{ $rentalResale->property_type == 'Commercial' ? 'selected' : '' }}>
                        Commercial</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Title<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $rentalResale->title }}"
                    required>
            </div>
            <div class="mb-3">
                <label for="subtitle" class="form-label">Subtitle<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{ $rentalResale->subtitle }}"
                    required>
            </div>
            <div class="form-group mb-3">
                <label for="slug">Slug<span class="text-danger">*</span></label>
                <input type="text" name="slug" id="slug" class="form-control" value="{{ $rentalResale->slug }}"
                    required>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="bathroom" class="form-label">Bathroom<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="bathroom" name="bathroom"
                                value="{{ $rentalResale->bathroom }}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="bedroom" class="form-label">Bedroom<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="bedroom" name="bedroom"
                                value="{{ $rentalResale->bedroom }}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="sq_ft" class="form-label">SQ Ft<span class="text-danger">*</span></label>
                            <input type="number" step="0.1" class="form-control" id="sq_ft" name="sq_ft"
                                value="{{ $rentalResale->sq_ft }}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="garage" class="form-label">Garage<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="garage" name="garage"
                                value="{{ $rentalResale->garage }}" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description<span class="text-danger">*</span></label>
                <textarea class="form-control editor" id="description" name="description" required>{{ $rentalResale->description }}</textarea>
            </div>

            <div class="container">
                <div class="row">
                    <!-- Details Repeater -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="details" class="form-label">Details<span class="text-danger">*</span></label>

                            <div class="details-repeater">

                                <div data-repeater-list="details">
                                    @if(isset($rentalResale->details))
                                    @foreach ((array) $rentalResale->details as $index => $detail)
                                    <div data-repeater-item class="repeater-item mb-2">
                                        <input type="text" class="form-control mb-2"
                                        name="details[{{ $index }}][title]" value="{{ $detail['title'] }}"
                                        placeholder="Title" required>
                                    <input type="text" class="form-control mb-2"
                                        name="details[{{ $index }}][info]" value="{{ $detail['info'] }}"
                                        placeholder="Information" required>
                                    <button type="button" class="btn btn-danger" data-repeater-delete>
                                        <i class="fas fa-trash-alt"></i> Remove
                                    </button>
                                    </div>
                                    @endforeach
                                    @else
                                    <div data-repeater-list="details">
                                        <div data-repeater-item class="repeater-item mb-2">
                                            <input type="text" class="form-control mb-2" name="title" placeholder="Title" required>
                                            <input type="text" class="form-control mb-2" name="info" placeholder="Information" required>
                                            <button type="button" class="btn btn-danger" data-repeater-delete>
                                                <i class="fas fa-trash-alt"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                    @endif
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
                                    @if(isset($rentalResale->amenities))
                                    @foreach ($rentalResale->amenities as $index => $amenity)
                                        <div data-repeater-item class="repeater-item mb-2">
                                            <input type="text" class="form-control mb-2"
                                                name="amenities[{{ $index }}]" value="{{ implode(', ', $amenity) }}"
                                                placeholder="Amenity" required>
                                            <button type="button" class="btn btn-danger" data-repeater-delete>
                                                <i class="fas fa-trash-alt"></i> Remove
                                            </button>
                                        </div>
                                    @endforeach
                                    @else
                                    <div data-repeater-list="amenities">
                                        <div data-repeater-item class="repeater-item mb-2">
                                            <input type="text" class="form-control mb-2" name="amenity" placeholder="Amenity" required>
                                            <button type="button" class="btn btn-danger" data-repeater-delete>
                                                <i class="fas fa-trash-alt"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                    @endif
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
                                    @if(isset($rentalResale->addresses))
                                    @foreach ($rentalResale->addresses as $index => $address)
                                    <div data-repeater-item class="repeater-item mb-2">
                                        <input type="text" class="form-control mb-2"
                                        name="addresses[{{ $index }}]" value="{{ implode(', ', $address) }}"
                                        placeholder="Address" required>
                                        <button type="button" class="btn btn-danger" data-repeater-delete>
                                            <i class="fas fa-trash-alt"></i> Remove
                                        </button>
                                    </div>
                                    @endforeach
                                    @else
                                    <div data-repeater-list="addresses">
                                        <div data-repeater-item class="repeater-item mb-2">
                                            <input type="text" class="form-control mb-2" name="address" placeholder="Address" required>
                                            <button type="button" class="btn btn-danger" data-repeater-delete>
                                                <i class="fas fa-trash-alt"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                    @endif
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
                            <input type="text" class="form-control" id="agent_title" name="agent_title"
                                value="{{ $rentalResale->agent_title }}" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="agent_status" class="form-label">Agent Status<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="agent_status" name="agent_status"
                                value="{{ $rentalResale->agent_status }}" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="agent_languages" class="form-label">Agent Languages<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="agent_languages" name="agent_languages"
                                value="{{ $rentalResale->agent_languages }}" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="agent_call" class="form-label">Agent Call<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="agent_call" name="agent_call"
                                value="{{ $rentalResale->agent_call }}" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="agent_whatsapp" class="form-label">Agent WhatsApp<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="agent_whatsapp" name="agent_whatsapp"
                                value="{{ $rentalResale->agent_whatsapp }}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="qr_photo" class="form-label">Agent Photo</label>
                            @if ($rentalResale->agent_photo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $rentalResale->agent_photo) }}" alt="agent_photo"
                                        style="max-width: 200px;">
                                    <button type="button" class="btn btn-danger btn-sm" id="remove-qr-photo">Remove</button>
                                </div>
                            @endif
                            <input type="file" class="form-control" id="agent_photo" name="agent_photo">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="languages" class="form-label">languages</label>
                            <div class="languages-repeater">
                                <div data-repeater-list="languages">
                                    @if(isset($rentalResale->languages ))
                                    @foreach ($rentalResale->languages  as $index => $language)
                                    <div data-repeater-item class="repeater-item mb-2">
                                        <input type="text" class="form-control mb-2" name="languages[{{ $index }}]" value="{{ implode(', ', $language) }}" required>
                                        <button type="button" class="btn btn-danger" data-repeater-delete>
                                            <i class="fas fa-trash-alt"></i> Remove
                                        </button>
                                    </div>
                                    @endforeach
                                    @else
                                    <div class="languages-repeater">
                                        <div data-repeater-list="languages">
                                            <div data-repeater-item class="repeater-item mb-2">
                                                <input type="text" class="form-control mb-2" name="languages" placeholder="languages" required>
                                                <button type="button" class="btn btn-danger" data-repeater-delete>
                                                    <i class="fas fa-trash-alt"></i> Remove
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                    @endif
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
                <input type="text" class="form-control" id="location_link" name="location_link"
                    value="{{ $rentalResale->location_link }}" required>
            </div>
            <div class="mb-3">
                <label for="location_id" class="form-label">Location<span class="text-danger">*</span></label>
                <select class="form-control select2" id="location_id" name="location_id" required>
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}"
                            {{ $rentalResale->location_id == $location->id ? 'selected' : '' }}>{{ $location->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="qr_photo" class="form-label">QR Photo<span class="text-danger">*</span></label>
                @if ($rentalResale->qr_photo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $rentalResale->qr_photo) }}" alt="QR Photo"
                            style="max-width: 200px;">
                        <button type="button" class="btn btn-danger btn-sm" id="remove-qr-photo">Remove</button>
                    </div>
                @endif
                <input type="file" class="form-control" id="qr_photo" name="qr_photo">
            </div>
            <div class="mb-3">
                <label for="reference" class="form-label">Reference<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="reference" name="reference"
                    value="{{ $rentalResale->reference }}" required>
            </div>
            <div class="mb-3">
                <label for="dld_permit_number" class="form-label">DLD Permit Number<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="dld_permit_number" name="dld_permit_number"
                    value="{{ $rentalResale->dld_permit_number }}" required>
            </div>
            <div class="mb-3">
                <label for="top" class="form-label">Mark as Top Listing<span class="text-danger">*</span></label>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="top" name="top" value="1"
                        {{ $rentalResale->top ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_top">Check this box to mark the property as a top
                        listing</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="gallery" class="form-label">Gallery</label>
                <input type="file" class="form-control" id="gallery" name="gallery_images[]" multiple>
                <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal"
                    data-bs-target="#galleryModal">Manage Gallery</button>
            </div>

            <button type="submit" class="btn btn-success w-100">Update</button>
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
                        @foreach (json_decode($rentalResale->gallery_images, true) as $image)
                            <div class="gallery-image-wrapper"
                                style="display: inline-block; position: relative; margin-right: 10px;">
                                <img src="{{ asset('storage/' . $image) }}" alt="Gallery Image" class="img-thumbnail"
                                    style="max-width: 100px;">
                                <button type="button" class="btn btn-danger btn-sm remove-gallery-image"
                                    data-image="{{ $image }}"
                                    style="position: absolute; top: 0; right: 0;">Remove</button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <script>

                    document.addEventListener('DOMContentLoaded', function() {
                                    // Initialize Details Repeater
                                    $('.details-repeater').repeater({
                                initEmpty: false, // Ensure one field is present initially
                                defaultValues: {
                                    'title': '',
                                    'info': ''
                                },
                                show: function () {
                                    console.log("New detail field added.");
                                    $(this).slideDown(); // Animate adding a new field
                                },
                                hide: function (deleteElement) {
                                    console.log("Detail field removed.");
                                    $(this).slideUp(deleteElement); // Animate removal of field
                                }
                            });

                        // Initialize Amenities Repeater
                        $('.amenities-repeater').repeater({
                            initEmpty: false, // Ensure one field is present initially
                            defaultValues: {
                                'amenity': ''
                            },
                            show: function () {
                                $(this).slideDown(); // Animate adding a new field
                            },
                            hide: function (deleteElement) {
                                $(this).slideUp(deleteElement); // Animate removal of field
                            }
                        });
                        $('.languages-repeater').repeater({
                            initEmpty: false, // Ensure one field is present initially
                            defaultValues: {
                                'languages': ''
                            },
                            show: function () {
                                $(this).slideDown(); // Animate adding a new field
                            },
                            hide: function (deleteElement) {
                                $(this).slideUp(deleteElement); // Animate removal of field
                            }
                        });
                        // Initialize Addresses Repeater
                        $('.addresses-repeater').repeater({
                            initEmpty: false, // Ensure one field is present initially
                            defaultValues: {
                                'address': ''
                            },
                            show: function () {
                                $(this).slideDown(); // Animate adding a new field
                            },
                            hide: function (deleteElement) {
                                $(this).slideUp(deleteElement); // Animate removal of field
                            }
                        });

                        const postId = {{ $rentalResale->id }};

                        // Remove QR Photo
                        const removeQrPhotoButton = document.getElementById('remove-qr-photo');
                        if (removeQrPhotoButton) {
                            removeQrPhotoButton.addEventListener('click', function() {
                                fetch(`/ioka_admin/postypes/rental_resale/${postId}/remove-qr-photo`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            location.reload();
                                        }
                                    })
                                    .catch(error => console.error('Error:', error));
                            });
                        }

                        // Remove Gallery Image
                        document.querySelectorAll('.remove-gallery-image').forEach(button => {
                            button.addEventListener('click', function() {
                                const image = this.dataset.image;
                                fetch(`/ioka_admin/postypes/rental_resale/${postId}/remove-gallery-image`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            image
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            this.closest('.gallery-image-wrapper').remove();
                                        }
                                    })
                                    .catch(error => console.error('Error:', error));
                            });
                        });

                        // Upload New Gallery Image
                        const uploadNewImageButton = document.getElementById('upload-new-image');
                        if (uploadNewImageButton) {
                            uploadNewImageButton.addEventListener('click', function() {
                                const files = document.getElementById('new-gallery-image').files;
                                const formData = new FormData();
                                for (let i = 0; i < files.length; i++) {
                                    formData.append('gallery_images[]', files[i]);
                                }
                                fetch(`/ioka_admin/postypes/rental_resale/${postId}/upload-gallery-images`, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: formData
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            loadGalleryImages();
                                        }
                                    })
                                    .catch(error => console.error('Error:', error));
                            });
                        }

                        // Load Gallery Images
                        function loadGalleryImages() {
                            fetch(`/ioka_admin/postypes/rental_resale/${postId}/gallery-images`, {
                                    method: 'GET',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    const galleryImagesContainer = document.getElementById('gallery-images');
                                    galleryImagesContainer.innerHTML = '';
                                    data.images.forEach(image => {
                                        const imageWrapper = document.createElement('div');
                                        imageWrapper.classList.add('gallery-image-wrapper');
                                        imageWrapper.style.position = 'relative';

                                        const img = document.createElement('img');
                                        img.src = `/storage/${image}`;
                                        img.alt = 'Gallery Image';

                                        const removeButton = document.createElement('button');
                                        removeButton.classList.add('btn', 'btn-danger', 'btn-sm',
                                            'remove-gallery-image');
                                        removeButton.dataset.image = image;
                                        removeButton.textContent = 'Remove';
                                        removeButton.addEventListener('click', function() {
                                            fetch(`/ioka_admin/postypes/rental_resale/${postId}/remove-gallery-image`, {
                                                    method: 'DELETE',
                                                    headers: {
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                        'Content-Type': 'application/json'
                                                    },
                                                    body: JSON.stringify({
                                                        image
                                                    })
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data.success) {
                                                        imageWrapper.remove();
                                                    }
                                                })
                                                .catch(error => console.error('Error:', error));
                                        });

                                        imageWrapper.appendChild(img);
                                        imageWrapper.appendChild(removeButton);
                                        galleryImagesContainer.appendChild(imageWrapper);
                                    });
                                })
                                .catch(error => console.error('Error:', error));
                        }

                        // Load gallery images when modal is shown
                        const galleryModal = document.getElementById('galleryModal');
                        if (galleryModal) {
                            galleryModal.addEventListener('shown.bs.modal', function() {
                                loadGalleryImages();
                            });
                        }

                    });
                </script>

            @endsection




