@extends('admin.layouts.app')

@section('content')
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">Edit Partner</h1>
            <a href="{{ url('ioka_admin/partners') }}" class="btn btn-secondary">
                Back to Partners
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Use PUT method to update the partner -->
                <form action="{{ route('admin.partners.update', $partner) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <!-- This simulates the PUT request for updating -->

                    <!-- Title Field -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Partner Title</label>
                        <input type="text" id="title" name="title"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title', $partner->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Image Field -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Partner Image</label>
                        <input type="file" id="image" name="image"
                            class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        @if ($partner->image)
                            <div class="mt-2">
                                <!-- Image Display with small view -->
                                <img src="{{ Storage::url($partner->image) }}" alt="{{ $partner->title }}"
                                    class="img-fluid mb-2 rounded-lg" style="max-height: 100px; object-fit: cover;">

                                <!-- Button to delete the current image -->
                                <button type="button" class="btn btn-danger btn-sm mt-2" id="deleteImageBtn"
                                    data-route="{{ route('admin.partners.delete-image', ['id' => $partner->id]) }}"
                                    data-partner-id="{{ $partner->id }}">
                                    Delete Image
                                </button>
                            </div>
                        @endif
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- URL Field -->
                    <div class="mb-3">
                        <label for="url" class="form-label">Partner URL</label>
                        <input type="url" id="url" name="url"
                            class="form-control @error('url') is-invalid @enderror" value="{{ old('url', $partner->url) }}"
                            required>
                        @error('url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit and Cancel Buttons -->
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Update Partner</button>
                        <a href="{{ url('ioka_admin/partners') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Deleting Image Confirmation -->
    <div class="modal fade" id="deleteImageModal" tabindex="-1" aria-labelledby="deleteImageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteImageModalLabel">Delete Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this image?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Add click handler using jQuery
        $('#deleteImageBtn').on('click', function() {
            const partnerId = $(this).data('partner-id');
            console.log('Partner ID:', partnerId);

            // Show the modal using jQuery
            $('#deleteImageModal').modal('show');

            // Handle confirm button click
            $('#confirmDeleteBtn').off('click').on('click', function() {
                console.log('Confirm delete clicked');

                // AJAX request to delete the image
                $.ajax({
                    url: '/ioka_admin/partners/' + partnerId + '/delete-image',
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Delete response:', response);
                        if (response.success) {
                            // Hide the modal
                            $('#deleteImageModal').modal('hide');

                            // Find and remove the image and delete button from the DOM
                            const imageElement = document.querySelector(
                                'img[alt="{{ $partner->title }}"]');
                            if (imageElement) {
                                imageElement.remove();
                            }

                            const deleteButton = document.getElementById('deleteImageBtn');
                            if (deleteButton) {
                                deleteButton.remove();
                            }

                            // Show success message
                            alert(response.message);
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Delete error:', xhr, status, error);
                        let errorMessage = 'Error deleting image';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage += ': ' + xhr.responseJSON.message;
                        } else {
                            errorMessage += ': ' + error;
                        }
                        alert(errorMessage);
                    }
                });
            });
        });
    });
</script>
@endpush



