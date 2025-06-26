@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1>Create Blog Post</h1>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('blogposts.store') }}" method="POST" enctype="multipart/form-data">
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
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                    name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>




                            <div class="mb-3">
                                <label for="body" class="form-label">Body</label>
                                <textarea class="form-control editor @error('body') is-invalid @enderror" id="body" name="body" rows="10"
                                    required>{{ old('body') }}</textarea>
                                @error('body')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" id="date"
                                    name="date" value="{{ old('date') }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <div class="mobile-image-upload" id="mobile-upload-blog_image">
                                    <div class="mb-2">
                                        <div class="d-flex align-items-center">
                                            <input type="file" class="form-control mobile-image-input @error('image') is-invalid @enderror" 
                                                id="input-blog_image" name="image" accept="image/*"
                                                data-field="blog_image"
                                                onchange="handleMobileImageSelect(this)">
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="compression-options mb-2 d-none">
                                        <div class="card p-3">
                                            <div class="mb-2">
                                                <label class="form-label">Image Quality</label>
                                                <input type="range" class="form-range quality-slider" min="10" max="100" value="70" 
                                                    id="quality-blog_image" data-field="blog_image">
                                                <div class="d-flex justify-content-between">
                                                    <small>Lower (Smaller File)</small>
                                                    <small class="quality-value">70%</small>
                                                    <small>Higher (Better Quality)</small>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-2">
                                                <label class="form-label">Max Width</label>
                                                <select class="form-select max-width" id="max-width-blog_image" data-field="blog_image" style="display: block !important; width: 100%;">
                                                    <option value="800">Small (800px)</option>
                                                    <option value="1200" selected>Medium (1200px)</option>
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
                                                    onclick="cancelCompression('blog_image')">Cancel</button>
                                                <button type="button" class="btn btn-primary btn-sm apply-compression" 
                                                    onclick="applyCompression('blog_image')">Apply & Upload</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="image_compressed" id="compressed-blog_image" class="compressed-image-data">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="banner_image" class="form-label">Banner Image</label>
                                <div class="mobile-image-upload" id="mobile-upload-blog_banner_image">
                                    <div class="mb-2">
                                        <div class="d-flex align-items-center">
                                            <input type="file" class="form-control mobile-image-input @error('banner_image') is-invalid @enderror" 
                                                id="input-blog_banner_image" name="banner_image" accept="image/*"
                                                data-field="blog_banner_image"
                                                onchange="handleMobileImageSelect(this)">
                                            @error('banner_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="compression-options mb-2 d-none">
                                        <div class="card p-3">
                                            <div class="mb-2">
                                                <label class="form-label">Image Quality</label>
                                                <input type="range" class="form-range quality-slider" min="10" max="100" value="70" 
                                                    id="quality-blog_banner_image" data-field="blog_banner_image">
                                                <div class="d-flex justify-content-between">
                                                    <small>Lower (Smaller File)</small>
                                                    <small class="quality-value">70%</small>
                                                    <small>Higher (Better Quality)</small>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-2">
                                                <label class="form-label">Max Width</label>
                                                <select class="form-select max-width" id="max-width-blog_banner_image" data-field="blog_banner_image" style="display: block !important; width: 100%;">
                                                    <option value="800">Small (800px)</option>
                                                    <option value="1200" selected>Medium (1200px)</option>
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
                                                    onclick="cancelCompression('blog_banner_image')">Cancel</button>
                                                <button type="button" class="btn btn-primary btn-sm apply-compression" 
                                                    onclick="applyCompression('blog_banner_image')">Apply & Upload</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="banner_image_compressed" id="compressed-blog_banner_image" class="compressed-image-data">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="banner_title" class="form-label">Banner Title</label>
                                <input type="text" class="form-control @error('banner_title') is-invalid @enderror" id="banner_title" name="banner_title" value="{{ old('banner_title') }}" placeholder="Enter banner title">
                                @error('banner_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="banner_image_alt" class="form-label">Banner Image Alt Text</label>
                                <input type="text" class="form-control @error('banner_image_alt') is-invalid @enderror" id="banner_image_alt" name="banner_image_alt" value="{{ old('banner_image_alt') }}">
                                @error('banner_image_alt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="mobile_banner_image" class="form-label">Mobile Banner Photo</label>
                                <div class="mobile-image-upload" id="mobile-upload-blog_mobile_banner_image">
                                    <div class="mb-2">
                                        <div class="d-flex align-items-center">
                                            <input type="file" class="form-control mobile-image-input @error('mobile_banner_image') is-invalid @enderror" 
                                                id="input-blog_mobile_banner_image" name="mobile_banner_image" accept="image/*" capture="environment"
                                                data-field="blog_mobile_banner_image"
                                                onchange="handleMobileImageSelect(this)">
                                            @error('mobile_banner_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="compression-options mb-2 d-none">
                                        <div class="card p-3">
                                            <div class="mb-2">
                                                <label class="form-label">Image Quality</label>
                                                <input type="range" class="form-range quality-slider" min="10" max="100" value="70" 
                                                    id="quality-blog_mobile_banner_image" data-field="blog_mobile_banner_image">
                                                <div class="d-flex justify-content-between">
                                                    <small>Lower (Smaller File)</small>
                                                    <small class="quality-value">70%</small>
                                                    <small>Higher (Better Quality)</small>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-2">
                                                <label class="form-label">Max Width</label>
                                                <select class="form-select max-width" id="max-width-blog_mobile_banner_image" data-field="blog_mobile_banner_image" style="display: block !important; width: 100%;">
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
                                                    onclick="cancelCompression('blog_mobile_banner_image')">Cancel</button>
                                                <button type="button" class="btn btn-primary btn-sm apply-compression" 
                                                    onclick="applyCompression('blog_mobile_banner_image')">Apply & Upload</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="mobile_banner_image_compressed" id="compressed-blog_mobile_banner_image" class="compressed-image-data">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="mobile_banner_image_alt" class="form-label">Mobile Banner Photo Alt Text</label>
                                <input type="text" class="form-control @error('mobile_banner_image_alt') is-invalid @enderror" id="mobile_banner_image_alt" name="mobile_banner_image_alt" value="{{ old('mobile_banner_image_alt') }}">
                                @error('mobile_banner_image_alt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image_alt" class="form-label">Image Alt Text</label>
                                <input type="text" class="form-control @error('image_alt') is-invalid @enderror" id="image_alt" name="image_alt" value="{{ old('image_alt') }}">
                                @error('image_alt')
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
                            </div>

                            <div class="mb-3">
                                <label for="mobile_image_alt" class="form-label">Mobile Photo Alt Text</label>
                                <input type="text" class="form-control @error('mobile_image_alt') is-invalid @enderror" id="mobile_image_alt" name="mobile_image_alt" value="{{ old('mobile_image_alt') }}">
                                @error('mobile_image_alt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input show_on" id="show_on_main_page"
                                        name="show_on_main_page" value="1" {{ old('show_on_main_page') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_on_main_page">Show on Main Page</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="tags" class="form-label">Tags</label>
                                <select class="form-control tags" id="tags" name="tags[]" multiple="multiple">
                                    @if (old('tags'))
                                        @foreach (old('tags') as $tag)
                                            <option value="{{ $tag }}" selected>{{ $tag }}</option>
                                        @endforeach
                                    @endif
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->name }}"
                                            {{ in_array($tag->name, old('tags', [])) ? 'selected' : '' }}>
                                            {{ $tag->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Type to add new tags or select existing ones. Press Enter or
                                    comma to add.</small>
                            </div>
                        </div>

                        <!-- Meta Data Tab -->
                        @include('components.metadata-tab')
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Create Post</button>
                        <a href="{{ route('blogposts.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('storage/admin/assets/blogpost.js') }}"></script>
    
    <script>
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


