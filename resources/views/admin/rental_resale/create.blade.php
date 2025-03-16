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
            <div class="mb-3">
                <label for="tags" class="form-label">Tags</label>
                <select class="form-control select2" id="tags" name="tags[]" multiple required>
                    <option value="6">Resale</option>
                    <option value="5">Rental</option>
                </select>
            </div>
            <div class="container">
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
            </div>
            <div class="mb-3">
                <label for="property_type" class="form-label">Property Type</label>
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
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group mb-3">
                <label for="title">Slug</label>
                <input type="text" name="slug" id="slug" class="form-control" required>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="bathroom" class="form-label">Bathroom</label>
                            <input type="number" class="form-control" id="bathroom" name="bathroom" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="bedroom" class="form-label">Bedroom</label>
                            <input type="number" class="form-control" id="bedroom" name="bedroom" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="sq_ft" class="form-label">SQ Ft</label>
                            <input type="number" step="0.1" class="form-control" id="sq_ft" name="sq_ft" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="garage" class="form-label">Garage</label>
                            <input type="number" class="form-control" id="garage" name="garage" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control editor" id="description" name="description" required></textarea>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="details" class="form-label">Details</label>
                            <div id="details-repeater" class="repeater">
                                <div class="repeater-item mb-2">
                                    <input type="text" class="form-control mb-2" name="details[0][title]" placeholder="Title" required>
                                    <input type="text" class="form-control mb-2" name="details[0][info]" placeholder="Information" required>
                                    <button type="button" class="btn btn-danger remove-detail">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary mt-2" id="add-detail">Add Detail</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="amenities" class="form-label">Amenities</label>
                            <div id="amenities-repeater" class="repeater">
                                <div class="repeater-item mb-2">
                                    <input type="text" class="form-control mb-2" name="amenities[0]" placeholder="Amenity" required>
                                    <button type="button" class="btn btn-danger remove-amenity">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary mt-2" id="add-amenity">Add Amenity</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="addresses" class="form-label">Addresses</label>
                            <div id="addresses-repeater" class="repeater">
                                <div class="repeater-item mb-2">
                                    <input type="text" class="form-control mb-2" name="addresses[0]" placeholder="Address" required>
                                    <button type="button" class="btn btn-danger remove-address">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary mt-2" id="add-address">Add Address</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="agent_title" class="form-label">Agent Title</label>
                            <input type="text" class="form-control" id="agent_title" name="agent_title" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="agent_status" class="form-label">Agent Status</label>
                            <input type="text" class="form-control" id="agent_status" name="agent_status" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="agent_languages" class="form-label">Agent Languages</label>
                            <input type="text" class="form-control" id="agent_languages" name="agent_languages" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="agent_call" class="form-label">Agent Call</label>
                            <input type="text" class="form-control" id="agent_call" name="agent_call" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="agent_whatsapp" class="form-label">Agent WhatsApp</label>
                            <input type="text" class="form-control" id="agent_whatsapp" name="agent_whatsapp" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="location_link" class="form-label">Location Link</label>
                <input type="text" class="form-control" id="location_link" name="location_link" required>
            </div>
            <div class="mb-3">
                <label for="location_id" class="form-label">Location</label>
                <select class="form-control select2" id="location_id" name="location_id" required>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}">{{ $location->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="qr_photo" class="form-label">QR Photo</label>
                <input type="file" class="form-control" id="qr_photo" name="qr_photo" required>
            </div>
            <div class="mb-3">
                <label for="reference" class="form-label">Reference</label>
                <input type="text" class="form-control" id="reference" name="reference" required>
            </div>
            <div class="mb-3">
                <label for="dld_permit_number" class="form-label">DLD Permit Number</label>
                <input type="text" class="form-control" id="dld_permit_number" name="dld_permit_number" required>
            </div>
            <div class="mb-3">
                <label for="is_top" class="form-label">Mark as Top Listing</label>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="is_top" name="top" value="1">
                    <label class="form-check-label" for="is_top">Check this box to mark the property as a top listing</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="gallery" class="form-label">Gallery</label>
                <input type="file" class="form-control" id="gallery" name="gallery_images[]" multiple>
                <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#galleryModal">Manage Gallery</button>
            </div>
            <div class="mb-3">
                <label for="gallery" class="form-label">Gallery</label>
                <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#galleryModal">Manage Gallery</button>
            </div>

            <button type="submit" class="btn btn-success w-100">Create</button>
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
                </div>
            </div>
        </div>

    </div>

    <!-- Hidden element to pass uploadedImages variable -->
    <div id="uploadedImages" style="display: none;">{{ json_encode($uploadedImages ?? []) }}</div>

    <script src="{{ asset('storage/admin/assets/rental_resale.js') }}"></script>
@endsection
