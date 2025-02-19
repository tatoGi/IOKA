@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Edit Rental Resale</h1>
    <form action="{{ route('admin.postypes.rental_resale.update', $rentalResale->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $rentalResale->title }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control editor" required>{{ $rentalResale->description }}</textarea>
        </div>

        <div class="form-group">
            <label for="tags">Tags</label>
            <select class="form-control select2" id="tags" name="tags[]" multiple required>
                <option value="6" {{ in_array(6, (array) $rentalResale->tags) ? 'selected' : '' }}>Resale</option>
                <option value="5" {{ in_array(5, (array) $rentalResale->tags) ? 'selected' : '' }}>Rental</option>
            </select>
        </div>
        <div class="form-group">
            <label for="amount">Amount (in dollars)</label>
            <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ $rentalResale->amount->amount }}" required>
        </div>
        <div class="form-group">
            <label for="amount_dirhams">Amount (in Dirhams)</label>
            <input type="number" step="0.01" class="form-control" id="amount_dirhams" name="amount_dirhams" value="{{ $rentalResale->amount->amount_dirhams }}" readonly>
        </div>
        <div class="form-group">
            <label for="property_type">Property Type</label>
            <select class="form-control select2" id="property_type" name="property_type" required>
                <option value="Villa" {{ $rentalResale->property_type == 'Villa' ? 'selected' : '' }}>Villa</option>
                <option value="Townhouse" {{ $rentalResale->property_type == 'Townhouse' ? 'selected' : '' }}>Townhouse</option>
                <option value="Apartment" {{ $rentalResale->property_type == 'Apartment' ? 'selected' : '' }}>Apartment</option>
                <option value="Land" {{ $rentalResale->property_type == 'Land' ? 'selected' : '' }}>Land</option>
                <option value="Full Building" {{ $rentalResale->property_type == 'Full Building' ? 'selected' : '' }}>Full Building</option>
                <option value="Commercial" {{ $rentalResale->property_type == 'Commercial' ? 'selected' : '' }}>Commercial</option>
            </select>
        </div>
        <div class="form-group">
            <label for="bathroom">Bathroom</label>
            <input type="number" class="form-control" id="bathroom" name="bathroom" value="{{ $rentalResale->bathroom }}" required>
        </div>
        <div class="form-group">
            <label for="bedroom">Bedroom</label>
            <input type="number" class="form-control" id="bedroom" name="bedroom" value="{{ $rentalResale->bedroom }}" required>
        </div>
        <div class="form-group">
            <label for="sq_ft">SQ Ft</label>
            <input type="number" step="0.1" class="form-control" id="sq_ft" name="sq_ft" value="{{ $rentalResale->sq_ft }}" required>
        </div>
        <div class="form-group">
            <label for="garage">Garage</label>
            <input type="number" class="form-control" id="garage" name="garage" value="{{ $rentalResale->garage }}" required>
        </div>
        <div class="form-group">
            <label for="agent_title">Agent Title</label>
            <input type="text" class="form-control" id="agent_title" name="agent_title" value="{{ $rentalResale->agent_title }}" required>
        </div>
        <div class="form-group">
            <label for="agent_status">Agent Status</label>
            <input type="text" class="form-control" id="agent_status" name="agent_status" value="{{ $rentalResale->agent_status }}" required>
        </div>
        <div class="form-group">
            <label for="agent_languages">Agent Languages</label>
            <input type="text" class="form-control" id="agent_languages" name="agent_languages" value="{{ $rentalResale->agent_languages }}" required>
        </div>
        <div class="form-group">
            <label for="agent_call">Agent Call</label>
            <input type="text" class="form-control" id="agent_call" name="agent_call" value="{{ $rentalResale->agent_call }}" required>
        </div>
        <div class="form-group">
            <label for="agent_whatsapp">Agent WhatsApp</label>
            <input type="text" class="form-control" id="agent_whatsapp" name="agent_whatsapp" value="{{ $rentalResale->agent_whatsapp }}" required>
        </div>
        <div class="form-group">
            <label for="location_link">Location Link</label>
            <input type="text" class="form-control" id="location_link" name="location_link" value="{{ $rentalResale->location_link }}" required>
        </div>
        <div class="form-group">
            <label for="qr_photo">QR Photo</label>
            @if($rentalResale->qr_photo)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $rentalResale->qr_photo) }}" alt="QR Photo" style="max-width: 200px;">
                    <button type="button" class="btn btn-danger btn-sm" id="remove-qr-photo">Remove</button>
                </div>
            @endif
            <input type="file" class="form-control" id="qr_photo" name="qr_photo">
        </div>
        <div class="form-group">
            <label for="reference">Reference</label>
            <input type="text" class="form-control" id="reference" name="reference" value="{{ $rentalResale->reference }}" required>
        </div>
        <div class="form-group">
            <label for="dld_permit_number">DLD Permit Number</label>
            <input type="text" class="form-control" id="dld_permit_number" name="dld_permit_number" value="{{ $rentalResale->dld_permit_number }}" required>
        </div>
        <div class="form-group">
            <label for="addresses">Addresses</label>
            <div id="addresses-repeater">
                @foreach((array) $rentalResale->addresses as $index => $address)
                    <div class="repeater-item">
                        <input type="text" class="form-control mb-2" name="addresses[{{ $index }}]" value="{{ $address }}" required>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-primary" id="add-address">Add Address</button>
        </div>
        <div class="form-group mb-3">
            <label for="gallery">Gallery</label>
            <input type="file" class="form-control" id="gallery" name="gallery_images[]" multiple>
            <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#galleryModal">Manage Gallery</button>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
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
                    @if(isset($galleryImages) && is_array($galleryImages))
                        @foreach($galleryImages as $image)
                            <div class="gallery-image-wrapper" style="display: inline-block; position: relative; margin-right: 10px;">
                                <img src="{{ asset('storage/' . $image) }}" alt="Gallery Image">
                                <button type="button" class="btn btn-danger btn-sm remove-gallery-image" data-image="{{ $image }}" style="position: absolute; top: 0; right: 0;">Remove</button>
                            </div>
                        @endforeach
                    @endif
                </div>
                <input type="file" class="form-control mt-3" id="new-gallery-image" multiple>
                <button type="button" class="btn btn-primary mt-2" id="upload-new-image">Upload New Image</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('remove-qr-photo').addEventListener('click', function() {
        const postId = {{ $rentalResale->id }};
        fetch(`/ioka_admin/postypes/rental_resale/${postId}/remove-qr-photo`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  location.reload();
              }
          });
    });

    document.querySelectorAll('.remove-gallery-image').forEach(button => {
        button.addEventListener('click', function() {
            const image = this.getAttribute('data-image');
            const postId = {{ $rentalResale->id }};
            fetch(`/ioka_admin/postypes/rental_resale/${postId}/remove-gallery-image`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ image })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      this.closest('.gallery-image-wrapper').remove();
                  }
              });
        });
    });

    document.getElementById('upload-new-image').addEventListener('click', function() {
        const files = document.getElementById('new-gallery-image').files;
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('gallery_images[]', files[i]);
        }
        const postId = {{ $rentalResale->id }};
        fetch(`/ioka_admin/postypes/rental_resale/${postId}/upload-gallery-images`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  loadGalleryImages();
              }
          });
    });

    function loadGalleryImages() {
        const postId = {{ $rentalResale->id }};
        fetch(`/ioka_admin/postypes/rental_resale/${postId}/gallery-images`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(response => response.json())
          .then(data => {
              const galleryImagesContainer = document.getElementById('gallery-images');
              galleryImagesContainer.innerHTML = '';
              data.images.forEach(image => {
                  const imageWrapper = document.createElement('div');
                  imageWrapper.classList.add('gallery-image-wrapper');
                  imageWrapper.style.display = 'inline-block';
                  imageWrapper.style.position = 'relative';
                  imageWrapper.style.marginRight = '10px';

                  const img = document.createElement('img');
                  img.src = `/storage/${image}`;
                  img.alt = 'Gallery Image';

                  const removeButton = document.createElement('button');
                  removeButton.type = 'button';
                  removeButton.classList.add('btn', 'btn-danger', 'btn-sm', 'remove-gallery-image');
                  removeButton.dataset.image = image;
                  removeButton.style.position = 'absolute';
                  removeButton.style.top = '0';
                  removeButton.style.right = '0';
                  removeButton.textContent = 'Remove';

                  removeButton.addEventListener('click', function() {
                      fetch(`/ioka_admin/postypes/rental_resale/${postId}/remove-gallery-image`, {
                          method: 'DELETE',
                          headers: {
                              'X-CSRF-TOKEN': '{{ csrf_token() }}',
                              'Content-Type': 'application/json'
                          },
                          body: JSON.stringify({ image })
                      }).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                imageWrapper.remove();
                            }
                        });
                  });

                  imageWrapper.appendChild(img);
                  imageWrapper.appendChild(removeButton);
                  galleryImagesContainer.appendChild(imageWrapper);
              });
          });
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadGalleryImages();
    });

    // Add this event listener to load gallery images when the modal is shown
    document.getElementById('galleryModal').addEventListener('shown.bs.modal', function () {
        loadGalleryImages();
    });
</script>
@endsection
