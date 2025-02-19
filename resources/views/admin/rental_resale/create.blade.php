@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Create Rental Resale Post</h1>
    <form action="{{ route('admin.postypes.rental_resale.store') }}" method="POST" enctype="multipart/form-data">
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
        <div class="mb-3">
            <label for="tags" class="form-label">Tags</label>
            <select class="form-control select2" id="tags" name="tags[]" multiple required>
                <option value="6">Resale</option>
                <option value="5">Rental</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Amount (in dollars)</label>
            <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
        </div>
        <div class="mb-3">
            <label for="amount_dirhams" class="form-label">Amount (in Dirhams)</label>
            <input type="number" step="0.01" class="form-control" id="amount_dirhams" name="amount_dirhams" readonly>
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
        <div class="mb-3">
            <label for="bathroom" class="form-label">Bathroom</label>
            <input type="number" class="form-control" id="bathroom" name="bathroom" required>
        </div>
        <div class="mb-3">
            <label for="bedroom" class="form-label">Bedroom</label>
            <input type="number" class="form-control" id="bedroom" name="bedroom" required>
        </div>
        <div class="mb-3">
            <label for="sq_ft" class="form-label">SQ Ft</label>
            <input type="number" step="0.1" class="form-control" id="sq_ft" name="sq_ft" required>
        </div>
        <div class="mb-3">
            <label for="garage" class="form-label">Garage</label>
            <input type="number" class="form-control" id="garage" name="garage" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label ">Description</label>
            <textarea class="form-control editor" id="description" name="description" required></textarea>
        </div>
        <div class="mb-3">
            <label for="details" class="form-label">Details</label>
            <div id="details-repeater">
                <div class="repeater-item">
                    <input type="text" class="form-control mb-2" name="details[0][title]" placeholder="Title" required>
                    <input type="text" class="form-control mb-2" name="details[0][info]" placeholder="Information" required>
                </div>
            </div>
            <button type="button" class="btn btn-primary" id="add-detail">Add Detail</button>
        </div>
        <div class="mb-3">
            <label for="amenities" class="form-label">Amenities</label>
            <div id="amenities-repeater">
                <div class="repeater-item">
                    <input type="text" class="form-control mb-2" name="amenities[0]" placeholder="Amenity" required>
                </div>
            </div>
            <button type="button" class="btn btn-primary" id="add-amenity">Add Amenity</button>
        </div>
        <div class="mb-3">
            <label for="agent_title" class="form-label">Agent Title</label>
            <input type="text" class="form-control" id="agent_title" name="agent_title" required>
        </div>
        <div class="mb-3">
            <label for="agent_status" class="form-label">Agent Status</label>
            <input type="text" class="form-control" id="agent_status" name="agent_status" required>
        </div>
        <div class="mb-3">
            <label for="agent_languages" class="form-label">Agent Languages</label>
            <input type="text" class="form-control" id="agent_languages" name="agent_languages" required>
        </div>
        <div class="mb-3">
            <label for="agent_call" class="form-label">Agent Call</label>
            <input type="text" class="form-control" id="agent_call" name="agent_call" required>
        </div>
        <div class="mb-3">
            <label for="agent_whatsapp" class="form-label">Agent WhatsApp</label>
            <input type="text" class="form-control" id="agent_whatsapp" name="agent_whatsapp" required>
        </div>
        <div class="mb-3">
            <label for="location_link" class="form-label">Location Link</label>
            <input type="text" class="form-control" id="location_link" name="location_link" required>
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
            <label for="addresses" class="form-label">Addresses</label>
            <div id="addresses-repeater">
                <div class="repeater-item">
                    <input type="text" class="form-control mb-2" name="addresses[0]" placeholder="Address" required>
                </div>
            </div>
            <button type="button" class="btn btn-primary" id="add-address">Add Address</button>
        </div>
        <div class="mb-3">
            <label for="gallery" class="form-label">Gallery</label>
            <input type="file" class="form-control" id="gallery" name="gallery_images[]" multiple required>
            <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#galleryModal">Manage Gallery</button>
        </div>

        <button type="submit" class="btn btn-success">Create</button>
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
                <input type="file" class="form-control mt-3" id="new-gallery-image" multiple>
                <button type="button" class="btn btn-primary mt-2" id="upload-new-image">Upload New Image</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('upload-new-image').addEventListener('click', function() {
        // Add logic to handle new image upload
    });

    function loadGalleryImages() {
        // Add logic to load gallery images dynamically
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadGalleryImages();
    });
</script>
@endsection
