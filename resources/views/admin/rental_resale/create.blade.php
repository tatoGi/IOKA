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
                    <div class="form-group mb-3">
                        <label for="title">Slug<span class="text-danger">*</span></label>
                        <input type="text" name="slug" id="slug" class="form-control" required>
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
                                    <label for="agent_languages" class="form-label">Agent Languages<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="agent_languages" name="agent_languages" required>
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
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="agent_photo" class="form-label">Agent Photo<span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="agent_photo" name="agent_photo" multiple>
                                    <input type="text" class="form-control mt-2" name="alt_texts[agent_photo]" placeholder="Alt text for agent photo">
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
                            <label for="location_id" class="form-label">Location<span class="text-danger">*</span></label>
                            <select class="form-control select2" id="location_id" name="location_id[]" required>
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
                <div class="tab-pane" id="metadata-tab" role="tabpanel">
                    <x-metadata-form :model="new App\Models\RentalResale" />
                </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Handle gallery image file input change
            const galleryInput = document.getElementById('gallery');
            const galleryAltTexts = document.getElementById('gallery-alt-texts');

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
        });
    </script>
@endsection
