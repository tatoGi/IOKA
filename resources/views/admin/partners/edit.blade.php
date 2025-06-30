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
                                <img src="{{ Storage::url($partner->image) }}" alt="{{ $partner->alt ?? $partner->title }}"
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

                    <!-- Alt Text Field -->
                    <div class="mb-3">
                        <label for="alt" class="form-label">Image Alt Text</label>
                        <input type="text" id="alt" name="alt"
                            class="form-control @error('alt') is-invalid @enderror"
                            value="{{ old('alt', $partner->alt) }}"
                            placeholder="Enter descriptive text for the image">
                        @error('alt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="mobile_image" class="form-label">Mobile Photo</label>
                        <div class="mobile-image-upload" id="mobile-upload-blog_mobile_image">
                            <div class="mb-2">
                                <div class="d-flex align-items-center">
                                    <input type="file" class="form-control mobile-image-input @error('mobile_image') is-invalid @enderror" 
                                        id="input-blog_mobile_image" name="mobile_image" accept="image/*" capture="environment"
                                        data-field="blog_mobile_image"
                                        onchange="handleMobileImageSelect(this)">
                                    @error('mobile_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="compression-options mb-2 d-none">
                                <div class="card p-3">
                                    <div class="mb-2">
                                        <label class="form-label">Image Quality</label>
                                        <input type="range" class="form-range quality-slider" min="10" max="100" value="70" 
                                            id="quality-blog_mobile_image" data-field="blog_mobile_image">
                                        <div class="d-flex justify-content-between">
                                            <small>Lower (Smaller File)</small>
                                            <small class="quality-value">70%</small>
                                            <small>Higher (Better Quality)</small>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <label class="form-label">Max Width</label>
                                        <select class="form-select max-width" id="max-width-blog_mobile_image" data-field="blog_mobile_image" style="display: block !important; width: 100%;">
                                            <option value="800" selected>Small (800px)</option>
                                            <option value="1200">Medium (1200px)</option>
                                            <option value="1600">Large (1600px)</option>
                                            <option value="0">Original Size</option>
                                        </select>
                                    </div>
                                    
                                    <div class="image-preview-container mb-2 d-none">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="form-label mb-0">Preview</label>
                                            <div class="file-info small text-muted"></div>
                                        </div>
                                        <img src="" class="img-fluid img-thumbnail preview-image" style="max-height: 200px;">
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-secondary btn-sm cancel-compression" 
                                            onclick="cancelCompression('blog_mobile_image')">Cancel</button>
                                        <button type="button" class="btn btn-primary btn-sm apply-compression" 
                                            onclick="applyCompression('blog_mobile_image')">Apply & Upload</button>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="hidden" name="mobile_image_compressed" id="compressed-blog_mobile_image" class="compressed-image-data">
                        </div>
                        @if (isset($partner->mobile_image) && $partner->mobile_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $partner->mobile_image) }}" alt="{{ $partner->mobile_image_alt }}" class="img-thumbnail" width="200">
                                <button type="button" class="btn btn-danger mt-2" id="remove-mobile-image-btn">Remove Mobile Photo</button>
                            </div>
                        @endif
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
        // Handle mobile image delete button
        $('#remove-mobile-image-btn').on('click', function() {
            const partnerId = {{ $partner->id }};
            
            if (confirm('Are you sure you want to delete this mobile image?')) {
                // AJAX request to delete the mobile image
                $.ajax({
                    url: '/ioka_admin/partners/' + partnerId + '/delete-mobile-image',
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Remove the image and button from DOM
                            $(this).parent().remove();
                            // Show success message
                            alert(response.message);
                            // Reload the page
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Error deleting image';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage += ': ' + xhr.responseJSON.message;
                        } else {
                            errorMessage += ': ' + error;
                        }
                        alert(errorMessage);
                    }
                });
            }
        });
        
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
   
        // Object to store original files for compression
        const originalFiles = {};
        
        // Handle mobile image file selection
        function handleMobileImageSelect(input) {
            const inputId = input.id;
            const file = input.files[0];
            
            if (!file) return;
            
            // Parse the input ID to determine field identifier
            const parts = inputId.split('-');
            const fieldIdentifier = parts[1];
            console.log('Field detected:', fieldIdentifier);
            
            // Store original file for later use - use a unique key for each field
            originalFiles[fieldIdentifier] = file;
            console.log('Stored file for field:', fieldIdentifier, 'File name:', file.name);
            
            // Verify the hidden input exists
            const hiddenInput = document.getElementById('compressed-' + fieldIdentifier);
            if (!hiddenInput) {
                console.error('Hidden input not found for field:', fieldIdentifier);
            } else {
                console.log('Hidden input found for field:', fieldIdentifier, 'ID:', hiddenInput.id);
            }
            
            // Show compression options
            const container = document.getElementById('mobile-upload-' + fieldIdentifier);
            const options = container.querySelector('.compression-options');
            options.classList.remove('d-none');
            
            // Set up quality slider
            const qualitySlider = document.getElementById('quality-' + fieldIdentifier);
            const qualityValue = qualitySlider.parentElement.querySelector('.quality-value');
            
            qualitySlider.addEventListener('input', function() {
                qualityValue.textContent = this.value + '%';
                previewCompressedImage(fieldIdentifier);
            });
            
            // Set up max width select
            const widthSelect = document.getElementById('max-width-' + fieldIdentifier);
            if (widthSelect) {
                // Fix for select dropdown display issues
                widthSelect.style.display = 'block';
                widthSelect.style.width = '100%';
                
                widthSelect.addEventListener('click', function(e) {
                    // Prevent any default behavior that might be interfering
                    e.stopPropagation();
                    
                    // Force the dropdown to show if it's not showing
                    if (this.size <= 1) {
                        this.size = 4; // Show 4 options at once
                        setTimeout(() => {
                            // Reset after selection
                            document.addEventListener('click', function closeDropdown() {
                                widthSelect.size = 1;
                                document.removeEventListener('click', closeDropdown);
                            }, { once: true });
                        }, 0);
                    }
                });
                
                // Make sure options are visible
                const options = widthSelect.querySelectorAll('option');
                options.forEach(option => {
                    option.style.display = 'block';
                });
                
                widthSelect.addEventListener('change', function() {
                    previewCompressedImage(fieldIdentifier);
                });
            }
            
            // Generate initial preview
            previewCompressedImage(fieldIdentifier);
        }
        
        // Preview the compressed image with current settings
        function previewCompressedImage(fieldIdentifier) {
            const file = originalFiles[fieldIdentifier];
            if (!file) return;
            
            const container = document.getElementById('mobile-upload-' + fieldIdentifier);
            const qualitySlider = document.getElementById('quality-' + fieldIdentifier);
            const widthSelect = document.getElementById('max-width-' + fieldIdentifier);
            const previewContainer = container.querySelector('.image-preview-container');
            const previewImage = container.querySelector('.preview-image');
            const fileInfo = container.querySelector('.file-info');
            
            // Show preview container
            previewContainer.classList.remove('d-none');
            
            // Get settings
            const quality = parseInt(qualitySlider.value) / 100;
            const maxWidth = parseInt(widthSelect.value);
            
            // Create a FileReader to read the image
            const reader = new FileReader();
            reader.onload = function(e) {
                // Create an image element to get dimensions
                const img = new Image();
                img.onload = function() {
                    // Calculate new dimensions while maintaining aspect ratio
                    let newWidth = img.width;
                    let newHeight = img.height;
                    
                    if (maxWidth > 0 && img.width > maxWidth) {
                        newWidth = maxWidth;
                        newHeight = (img.height * maxWidth) / img.width;
                    }
                    
                    // Create canvas for compression
                    const canvas = document.createElement('canvas');
                    canvas.width = newWidth;
                    canvas.height = newHeight;
                    
                    // Draw and compress image
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, newWidth, newHeight);
                    
                    // Get compressed image as data URL
                    const compressedDataUrl = canvas.toDataURL('image/jpeg', quality);
                    
                    // Update preview
                    previewImage.src = compressedDataUrl;
                    
                    // Calculate and display file size information
                    const originalSizeKB = Math.round(file.size / 1024);
                    
                    // Estimate compressed size from data URL
                    const base64 = compressedDataUrl.split(',')[1];
                    const compressedSizeKB = Math.round((base64.length * 3/4) / 1024);
                    
                    const savedPercent = Math.round((1 - (compressedSizeKB / originalSizeKB)) * 100);
                    
                    fileInfo.textContent = `Original: ${originalSizeKB}KB → Compressed: ~${compressedSizeKB}KB (${savedPercent}% saved)`;
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
        
        // Apply compression and store in hidden field for form submission
        function applyCompression(fieldIdentifier) {
            const file = originalFiles[fieldIdentifier];
            if (!file) {
                console.error('No file found for field:', fieldIdentifier);
                return;
            }
            
            console.log('Applying compression for field:', fieldIdentifier);
            
            // Get the file input element
            const fileInput = document.getElementById('input-' + fieldIdentifier);
            if (!fileInput) {
                console.error('File input not found for field:', fieldIdentifier);
                return;
            }
            
            // Get all the necessary elements for this specific field
            const container = document.getElementById('mobile-upload-' + fieldIdentifier);
            const qualitySlider = document.getElementById('quality-' + fieldIdentifier);
            const widthSelect = document.getElementById('max-width-' + fieldIdentifier);
            const hiddenInput = document.getElementById('compressed-' + fieldIdentifier);
            const options = container.querySelector('.compression-options');
            
            // Log all the elements to make sure they're correctly identified
            console.log('Elements for field', fieldIdentifier, ':', {
                container: container ? 'Found' : 'Missing',
                qualitySlider: qualitySlider ? 'Found' : 'Missing',
                widthSelect: widthSelect ? 'Found' : 'Missing',
                hiddenInput: hiddenInput ? 'Found' : 'Missing',
                options: options ? 'Found' : 'Missing'
            });
            
            if (!container || !qualitySlider || !widthSelect || !hiddenInput || !fileInput || !options) {
                console.error('Missing elements for field:', fieldIdentifier);
                return;
            }
            
            // Get settings
            const quality = parseInt(qualitySlider.value) / 100;
            const maxWidth = parseInt(widthSelect.value);
            
            // Create a FileReader to read the image
            const reader = new FileReader();
            reader.onload = function(e) {
                // Create an image element to get dimensions
                const img = new Image();
                img.onload = function() {
                    // Calculate new dimensions while maintaining aspect ratio
                    let newWidth = img.width;
                    let newHeight = img.height;
                    
                    if (maxWidth > 0 && img.width > maxWidth) {
                        newWidth = maxWidth;
                        newHeight = (img.height * maxWidth) / img.width;
                    }
                    
                    // Create canvas for compression
                    const canvas = document.createElement('canvas');
                    canvas.width = newWidth;
                    canvas.height = newHeight;
                    
                    // Draw and compress image
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, newWidth, newHeight);
                    
                    // Get compressed image as data URL
                    const compressedDataUrl = canvas.toDataURL('image/jpeg', quality);
                    
                    // Upload to server for optimization
                    const formData = new FormData();
                    formData.append('image', compressedDataUrl);
                    formData.append('quality', Math.round(quality * 100));
                    formData.append('maxWidth', maxWidth);
                    
                    // Show loading state
                    options.innerHTML = '<div class="alert alert-info">Uploading and optimizing image...</div>';
                    
                    // Disable file input during upload
                    fileInput.disabled = true;
                    
                    // Send to server
                    fetch('{{ route("mobile.image.upload") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Server response success for field:', fieldIdentifier);
                            console.log('Image path from server:', data.path);
                            console.log('Image URL from server:', data.url);
                            
                            // Store the server-optimized image path in hidden input
                            hiddenInput.value = data.path;
                            console.log('Updated hidden input value for', fieldIdentifier, 'to:', hiddenInput.value);
                            
                            // Disable the file input to prevent double submission
                            fileInput.disabled = true;
                            
                            // Show success message with server-side optimization details
                            options.innerHTML = `
                                <div class="alert alert-success">
                                    <strong>Image optimized successfully!</strong><br>
                                    Data usage has been optimized for mobile networks.<br>
                                    <img src="${data.url}" class="img-fluid img-thumbnail mt-2" style="max-height: 200px;">
                                </div>
                            `;
                        } else {
                            console.error('Server error for field:', fieldIdentifier, data.message);
                            // Show error message
                            options.innerHTML = `<div class="alert alert-danger">Server error: ${data.message}</div>`;
                            // Re-enable file input
                            fileInput.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        // Show error message
                        options.innerHTML = `<div class="alert alert-danger">Network error: ${error.message}</div>`;
                        // Re-enable file input
                        fileInput.disabled = false;
                    });
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
        
        // Cancel compression and reset the file input
        function cancelCompression(fieldIdentifier) {
            console.log('Cancelling compression for field:', fieldIdentifier);
            
            // Get elements
            const fileInput = document.getElementById('input-' + fieldIdentifier);
            const container = document.getElementById('mobile-upload-' + fieldIdentifier);
            const options = container.querySelector('.compression-options');
            const hiddenInput = document.getElementById('compressed-' + fieldIdentifier);
            
            // Reset file input
            if (fileInput) {
                fileInput.value = '';
                fileInput.disabled = false;
            }
            
            // Hide options
            if (options) {
                options.classList.add('d-none');
            }
            
            // Clear hidden input
            if (hiddenInput) {
                hiddenInput.value = '';
            }
            
            // Remove from original files
            delete originalFiles[fieldIdentifier];
        }
    </script>
@endpush



