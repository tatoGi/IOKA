@extends('admin.layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Create Rental Resale Post</h1>
        <form action="{{ route('admin.postypes.rental_resale.store') }}" method="POST" enctype="multipart/form-data" class="shadow p-4 rounded bg-light">
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
                <input type="number" step="0.01" class="form-control amount" id="amount" name="amount" required>
            </div>
            <div class="mb-3">
                <label for="amount_dirhams" class="form-label">Amount (in Dirhams)</label>
                <input type="number" step="0.01" class="form-control amount_dirhams" id="amount_dirhams" name="amount_dirhams" readonly>
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
                <label for="gallery" class="form-label">Gallery</label>
                <input type="file" class="form-control" id="gallery" name="gallery_images[]" multiple required>
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
                    <input type="file" class="form-control mt-3" id="new-gallery-image" multiple>
                    <button type="button" class="btn btn-primary mt-2" id="upload-new-image">Upload New Image</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('upload-new-image').addEventListener('click', function() {
            const input = document.getElementById('new-gallery-image');
            const files = input.files;
            const galleryImages = document.getElementById('gallery-images');

            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgWrapper = document.createElement('div');
                    imgWrapper.classList.add('gallery-image-wrapper');
                    imgWrapper.innerHTML = `
                        <img src="${e.target.result}" alt="Gallery Image">
                        <button type="button" class="remove-gallery-image">&times;</button>
                    `;
                    galleryImages.appendChild(imgWrapper);
                };
                reader.readAsDataURL(files[i]);
            }
        });

        function loadGalleryImages() {
            const galleryImages = document.getElementById('gallery-images');
            // Logic to display uploaded images
            const uploadedImages = JSON.parse('{{ json_encode($uploadedImages ?? []) }}');
            uploadedImages.forEach(src => {
                const imgWrapper = document.createElement('div');
                imgWrapper.classList.add('gallery-image-wrapper');
                imgWrapper.innerHTML = `
                    <img src="${src}" alt="Gallery Image">
                    <button type="button" class="remove-gallery-image">&times;</button>
                `;
                galleryImages.appendChild(imgWrapper);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadGalleryImages();
        });

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-gallery-image')) {
                event.target.closest('.gallery-image-wrapper').remove();
            }
        });

        document.getElementById('add-detail').addEventListener('click', function() {
            const index = document.querySelectorAll('#details-repeater .repeater-item').length;
            const newItem = document.createElement('div');
            newItem.classList.add('repeater-item', 'mb-2');
            newItem.innerHTML = `
                <input type="text" class="form-control mb-2" name="details[${index}][title]" placeholder="Title" required>
                <input type="text" class="form-control mb-2" name="details[${index}][info]" placeholder="Information" required>
                <button type="button" class="btn btn-danger remove-detail">
                    <i class="fas fa-trash-alt"></i>
                </button>
            `;
            document.getElementById('details-repeater').appendChild(newItem);
        });

        document.getElementById('add-amenity').addEventListener('click', function() {
            const index = document.querySelectorAll('#amenities-repeater .repeater-item').length;
            const newItem = document.createElement('div');
            newItem.classList.add('repeater-item', 'mb-2');
            newItem.innerHTML = `
                <input type="text" class="form-control mb-2" name="amenities[${index}]" placeholder="Amenity" required>
                <button type="button" class="btn btn-danger remove-amenity">
                    <i class="fas fa-trash-alt"></i>
                </button>
            `;
            document.getElementById('amenities-repeater').appendChild(newItem);
        });

        document.getElementById('add-address').addEventListener('click', function() {
            const index = document.querySelectorAll('#addresses-repeater .repeater-item').length;
            const newItem = document.createElement('div');
            newItem.classList.add('repeater-item', 'mb-2');
            newItem.innerHTML = `
                <input type="text" class="form-control mb-2" name="addresses[${index}]" placeholder="Address" required>
                <button type="button" class="btn btn-danger remove-address">
                    <i class="fas fa-trash-alt"></i>
                </button>
            `;
            document.getElementById('addresses-repeater').appendChild(newItem);
        });

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-detail') || event.target.closest('.remove-detail')) {
                const item = event.target.closest('.repeater-item');
                if (item) item.remove();
            }
            if (event.target.classList.contains('remove-amenity') || event.target.closest('.remove-amenity')) {
                const item = event.target.closest('.repeater-item');
                if (item) item.remove();
            }
            if (event.target.classList.contains('remove-address') || event.target.closest('.remove-address')) {
                const item = event.target.closest('.repeater-item');
                if (item) item.remove();
            }
        });
    </script>
@endsection
