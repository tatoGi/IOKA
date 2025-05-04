@extends('admin.layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Edit Rental Resale Post</h1>
        <form action="{{ route('admin.postypes.rental_resale.update', $rentalResale->id) }}" method="POST"
            enctype="multipart/form-data" class="shadow p-4 rounded bg-light">
            @csrf
            @method('PUT')
            <input type="hidden" id="postId" value="{{ $rentalResale->id }}">
            <input type="hidden" name="alt_texts[gallery_images]" id="gallery-alt-texts-input" value="{{ $rentalResale->alt_texts }}">
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
                <input type="number" step="0.01" class="form-control amount" id="amount" name="amount"
                    value="{{ $rentalResale->amount->amount }}" required>
            </div>
            <div class="mb-3">
                <label for="amount_dirhams" class="form-label">Amount (in Dirhams)</label>
                <input type="number" step="0.01" class="form-control amount_dirhams" id="amount_dirhams" name="amount_dirhams"
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
                                    @foreach ((is_array($rentalResale->amenities) ? $rentalResale->amenities : json_decode($rentalResale->amenities, true)) as $index => $amenity)
                                        <div data-repeater-item class="repeater-item mb-2">
                                            <input type="text" class="form-control mb-2"
                                                name="amenities[{{ $index }}]" value="{{ is_array($amenity) ? implode(', ', $amenity) : $amenity }}"
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
                                    @foreach ((is_array($rentalResale->addresses) ? $rentalResale->addresses : json_decode($rentalResale->addresses, true)) as $index => $address)
                                    <div data-repeater-item class="repeater-item mb-2">
                                        <input type="text" class="form-control mb-2"
                                        name="addresses[{{ $index }}]" value="{{ is_array($address) ? implode(', ', $address) : $address }}"
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
                            <label for="agent_photo" class="form-label">Agent Photo</label>
                            @if ($rentalResale->agent_photo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $rentalResale->agent_photo) }}" alt="agent_photo"
                                        style="max-width: 200px;">
                                    <button type="button" class="btn btn-danger btn-sm" id="remove-qr-photo">Remove</button>
                                </div>
                            @endif
                            <input type="file" class="form-control" id="agent_photo" name="agent_photo">
                            <input type="text" class="form-control mt-2" name="alt_texts[agent_photo]"
                                   value="{{ json_decode($rentalResale->alt_texts, true)['agent_photo'] ?? '' }}"
                                   placeholder="Alt text for agent photo">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="languages" class="form-label">languages</label>
                            <div class="languages-repeater">
                                <div data-repeater-list="languages">
                                    @if(isset($rentalResale->languages))
                                    @foreach ((is_array($rentalResale->languages) ? $rentalResale->languages : json_decode($rentalResale->languages, true)) as $index => $language)
                                    <div data-repeater-item class="repeater-item mb-2">
                                        <input type="text" class="form-control mb-2" name="languages[{{ $index }}]" value="{{ is_array($language) ? implode(', ', $language) : $language }}" required>
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
                <select name="location_id[]" id="location_id" class="form-control select2" required>
                    <option value="">Select Location</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}"
                            {{ $selectedLocations[0] == $location->id ? 'selected' : '' }}>
                            {{ $location->title }}
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
                <div id="gallery-alt-texts" class="mt-2">
                    <!-- New gallery images alt text inputs will be added here dynamically -->
                </div>
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
                    <div class="container">
                        <div id="gallery-images" class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
                            @foreach (json_decode($rentalResale->gallery_images, true) as $index => $image)
                                <div class="col gallery-image-wrapper position-relative">
                                    <button type="button"
                                        class="btn btn-danger btn-sm remove-gallery-image"
                                        data-image="{{ $image }}"
                                        style="position: absolute; top: 10px; left: 10px; z-index: 2;">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <div class="card h-100">
                                        <img src="{{ asset('storage/' . $image) }}" alt="Gallery Image" class="card-img-top">
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <label class="form-label">Alt Text</label>
                                                @php
                                                    $altTexts = json_decode($rentalResale->alt_texts, true);
                                                    $galleryAltTexts = $altTexts['gallery_images'] ?? [];
                                                    $currentAltText = '';

                                                    if (is_array($galleryAltTexts)) {
                                                        if (isset($galleryAltTexts[$index])) {
                                                            $currentAltText = $galleryAltTexts[$index];
                                                        } elseif (isset($galleryAltTexts['gallery_images'][$index])) {
                                                            $currentAltText = $galleryAltTexts['gallery_images'][$index];
                                                        }
                                                    }
                                                @endphp
                                                <input type="text"
                                                       class="form-control gallery-alt-text"
                                                       name="alt_texts[gallery_images][{{ $index }}]"
                                                       value="{{ $currentAltText }}"
                                                       placeholder="Describe this image">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-3">
                        <div id="modal-gallery-alt-texts">
                            <!-- New image alt text inputs will be added here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save-gallery-changes">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const postId = document.getElementById('postId').value;
            const form = document.querySelector('form');
            const modal = document.getElementById('galleryModal');
            const saveChangesBtn = document.getElementById('save-gallery-changes');
            const galleryAltTextsInput = document.getElementById('gallery-alt-texts-input');

            // Handle save changes button click
            saveChangesBtn.addEventListener('click', function() {
                // Collect all alt texts from the modal
                const altTexts = {
                    gallery_images: {}
                };

                // Get existing alt texts
                let existingAltTexts = {};
                try {
                    existingAltTexts = JSON.parse(galleryAltTextsInput.value || '{}');
                } catch (e) {
                    console.error('Error parsing existing alt texts:', e);
                }

                // Collect alt texts from the modal
                document.querySelectorAll('.gallery-alt-text').forEach(input => {
                    const name = input.getAttribute('name');
                    const value = input.value;
                    if (name && value) {
                        // Extract the index from the name attribute
                        const match = name.match(/\[(\d+)\]$/);
                        if (match) {
                            const index = match[1];
                            altTexts.gallery_images[index] = value;
                        }
                    }
                });

                // Merge with existing alt texts
                const mergedAltTexts = {
                    ...existingAltTexts,
                    gallery_images: altTexts.gallery_images
                };

                // Update the hidden input value
                galleryAltTextsInput.value = JSON.stringify(mergedAltTexts);

                // Close the modal
                const modalInstance = bootstrap.Modal.getInstance(modal);
                modalInstance.hide();
            });
        });
    </script>
@endsection




