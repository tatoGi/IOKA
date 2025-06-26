@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>System Settings</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" id="settingsForm">
        @csrf
        @method('PUT')

        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#header">
                    Header
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#meta">
                    Meta Tags
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#footer">
                    Footer
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#social">
                    Social Links
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Header Tab -->
            <div class="tab-pane fade show active" id="header">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="mb-3">Logo Settings</h5>
                            <div class="mb-3">
                                <label class="form-label">Header Logo</label>
                                <div class="mb-2">
                                    @if(isset($settings['header']['logo']))
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ Storage::url($settings['header']['logo']) }}" alt="Current Logo" class="img-thumbnail" style="max-height: 100px">
                                            <form action="{{ route('admin.settings.delete-logo') }}" method="POST" class="position-absolute top-0 end-0 m-1">
                                                @csrf
                                                <input type="hidden" name="type" value="header">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                <div class="mobile-image-upload" id="mobile-upload-header_logo">
                                    <div class="mb-2">
                                        <div class="d-flex align-items-center">
                                            <input type="file" class="form-control mobile-image-input @error('header_logo') is-invalid @enderror" 
                                                id="input-header_logo" name="header_logo" accept="image/*,.svg"
                                                data-field="header_logo"
                                                onchange="handleMobileImageSelect(this)">
                                            @error('header_logo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="compression-options mb-2 d-none">
                                        <div class="card p-3">
                                            <div class="mb-2">
                                                <label class="form-label">Image Quality</label>
                                                <input type="range" class="form-range quality-slider" min="10" max="100" value="70" 
                                                    id="quality-header_logo" data-field="header_logo">
                                                <div class="d-flex justify-content-between">
                                                    <small>Lower (Smaller File)</small>
                                                    <small class="quality-value">70%</small>
                                                    <small>Higher (Better Quality)</small>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-2">
                                                <label class="form-label">Max Width</label>
                                                <select class="form-select max-width" id="max-width-header_logo" data-field="header_logo" style="display: block !important; width: 100%;">
                                                    <option value="200" selected>Logo Size (200px)</option>
                                                    <option value="400">Medium (400px)</option>
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
                                                    onclick="cancelCompression('header_logo')">Cancel</button>
                                                <button type="button" class="btn btn-primary btn-sm apply-compression" 
                                                    onclick="applyCompression('header_logo')">Apply & Upload</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="header_logo_compressed" id="compressed-header_logo" class="compressed-image-data">
                                    <small class="text-muted">Recommended size: 200x60px. Supported formats: JPEG, PNG, JPG, GIF, SVG</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meta Tags Tab -->
            <div class="tab-pane fade" id="meta">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="mb-3">Global Meta Tags</h5>
                            <div class="mb-3">
                                <label class="form-label">Meta Title</label>
                                <input type="text" class="form-control @error('meta.title') is-invalid @enderror"
                                       name="meta[title]"
                                       value="{{ old('meta.title', $settings['meta']['title'] ?? '') }}"
                                       placeholder="Default page title">
                                @error('meta.title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description</label>
                                <textarea class="form-control editor @error('meta.description') is-invalid @enderror"
                                          name="meta[description]"
                                          rows="3"
                                          placeholder="Default meta description">{{ old('meta.description', $settings['meta']['description'] ?? '') }}</textarea>
                                @error('meta.description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control @error('meta.keywords') is-invalid @enderror"
                                       name="meta[keywords]"
                                       value="{{ old('meta.keywords', $settings['meta']['keywords'] ?? '') }}"
                                       placeholder="keyword1, keyword2, keyword3">
                                @error('meta.keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Separate keywords with commas</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="mb-3">Open Graph Tags</h5>
                            <div class="mb-3">
                                <label class="form-label">OG Title</label>
                                <input type="text" class="form-control @error('meta.og_title') is-invalid @enderror"
                                       name="meta[og_title]"
                                       value="{{ old('meta.og_title', $settings['meta']['og_title'] ?? '') }}"
                                       placeholder="Open Graph title">
                                @error('meta.og_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">OG Description</label>
                                <textarea class="form-control editor @error('meta.og_description') is-invalid @enderror"
                                          name="meta[og_description]"
                                          rows="3"
                                          placeholder="Open Graph description">{{ old('meta.og_description', $settings['meta']['og_description'] ?? '') }}</textarea>
                                @error('meta.og_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">OG Image</label>
                                <div class="mb-2">
                                    @if(isset($settings['meta']['og_image']))
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ asset('storage/' . $settings['meta']['og_image']) }}" alt="OG Image" class="img-thumbnail" style="max-height: 100px">
                                            <form action="{{ route('admin.settings.delete-meta-image') }}" method="POST" class="position-absolute top-0 end-0 m-1">
                                                @csrf
                                                <input type="hidden" name="type" value="og">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" class="form-control @error('meta.og_image') is-invalid @enderror" name="meta[og_image]" accept="image/*">
                                @error('meta.og_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Recommended size: 1200x630px</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="mb-3">Twitter Card Tags</h5>
                            <div class="mb-3">
                                <label class="form-label">Twitter Card Type</label>
                                <select class="form-control @error('meta.twitter_card') is-invalid @enderror" name="meta[twitter_card]">
                                    <option value="summary" {{ (old('meta.twitter_card', $settings['meta']['twitter_card'] ?? '') == 'summary') ? 'selected' : '' }}>Summary</option>
                                    <option value="summary_large_image" {{ (old('meta.twitter_card', $settings['meta']['twitter_card'] ?? '') == 'summary_large_image') ? 'selected' : '' }}>Summary Large Image</option>
                                </select>
                                @error('meta.twitter_card')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Twitter Title</label>
                                <input type="text" class="form-control @error('meta.twitter_title') is-invalid @enderror"
                                       name="meta[twitter_title]"
                                       value="{{ old('meta.twitter_title', $settings['meta']['twitter_title'] ?? '') }}"
                                       placeholder="Twitter card title">
                                @error('meta.twitter_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Twitter Description</label>
                                <textarea class="form-control editor @error('meta.twitter_description') is-invalid @enderror"
                                          name="meta[twitter_description]"
                                          rows="3"
                                          placeholder="Twitter card description">{{ old('meta.twitter_description', $settings['meta']['twitter_description'] ?? '') }}</textarea>
                                @error('meta.twitter_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Twitter Image</label>
                                <div class="mb-2">
                                    @if(isset($settings['meta']['twitter_image']))
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ asset('storage/' . $settings['meta']['twitter_image']) }}" alt="Twitter Image" class="img-thumbnail" style="max-height: 100px">
                                            <form action="{{ route('admin.settings.delete-meta-image') }}" method="POST" class="position-absolute top-0 end-0 m-1">
                                                @csrf
                                                <input type="hidden" name="type" value="twitter">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" class="form-control @error('meta.twitter_image') is-invalid @enderror" name="meta[twitter_image]" accept="image/*">
                                @error('meta.twitter_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Recommended size: 1200x600px</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Tab -->
            <div class="tab-pane fade" id="footer">
                <div class="card">
                    <div class="card-body">
                        <!-- General Footer Settings -->
                        <div class="mb-4">
                            <h5 class="mb-3">General Settings</h5>
                            <div class="mb-3">
                                <label class="form-label">Footer Logo</label>
                                <div class="mb-2">
                                    @if(isset($settings['footer']['logo']))
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ asset($settings['footer']['logo']) }}" alt="Current Footer Logo" class="img-thumbnail" style="max-height: 100px">
                                            <form action="{{ route('admin.settings.delete-logo') }}" method="POST" class="position-absolute top-0 end-0 m-1">
                                                @csrf
                                                <input type="hidden" name="type" value="footer">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" class="form-control @error('footer_logo') is-invalid @enderror" name="footer_logo" accept="image/*,.svg">
                                @error('footer_logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Recommended size: 200x60px. Supported formats: JPEG, PNG, JPG, GIF, SVG</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control editor @error('footer.description') is-invalid @enderror" name="footer[description]">{{ old('footer.description', $settings['footer']['description'] ?? '') }}</textarea>
                                @error('footer.description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Copyright Text</label>
                                <input type="text" class="form-control @error('footer.copyright') is-invalid @enderror" name="footer[copyright]" value="{{ old('footer.copyright', $settings['footer']['copyright'] ?? '') }}">
                                @error('footer.copyright')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="mb-4 border-bottom pb-3">
                            <h5 class="mb-3">Contact Information</h5>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control @error('footer.contact.address') is-invalid @enderror" name="footer[contact][address]" value="{{ old('footer.contact.address', $settings['footer']['contact']['address'] ?? '') }}">
                                @error('footer.contact.address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control @error('footer.contact.phone') is-invalid @enderror" name="footer[contact][phone]" value="{{ old('footer.contact.phone', $settings['footer']['contact']['phone'] ?? '') }}">
                                @error('footer.contact.phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control @error('footer.contact.email') is-invalid @enderror" name="footer[contact][email]" value="{{ old('footer.contact.email', $settings['footer']['contact']['email'] ?? '') }}">
                                @error('footer.contact.email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Working Hours</label>
                                <input type="text" class="form-control @error('footer.contact.working_hours') is-invalid @enderror" name="footer[contact][working_hours]" value="{{ old('footer.contact.working_hours', $settings['footer']['contact']['working_hours'] ?? '') }}">
                                @error('footer.contact.working_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Newsletter Settings -->
                        <div class="mb-4 border-bottom pb-3">
                            <h5 class="mb-3">Newsletter</h5>
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control @error('footer.newsletter.title') is-invalid @enderror" name="footer[newsletter][title]" value="{{ old('footer.newsletter.title', $settings['footer']['newsletter']['title'] ?? '') }}">
                                @error('footer.newsletter.title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control editor @error('footer.newsletter.description') is-invalid @enderror" name="footer[newsletter][description]">{{ old('footer.newsletter.description', $settings['footer']['newsletter']['description'] ?? '') }}</textarea>
                                @error('footer.newsletter.description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Placeholder Text</label>
                                <input type="text" class="form-control @error('footer.newsletter.placeholder') is-invalid @enderror" name="footer[newsletter][placeholder]" value="{{ old('footer.newsletter.placeholder', $settings['footer']['newsletter']['placeholder'] ?? '') }}">
                                @error('footer.newsletter.placeholder')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Button Text</label>
                                <input type="text" class="form-control @error('footer.newsletter.button_text') is-invalid @enderror" name="footer[newsletter][button_text]" value="{{ old('footer.newsletter.button_text', $settings['footer']['newsletter']['button_text'] ?? '') }}">
                                @error('footer.newsletter.button_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Social Links Tab -->
            <div class="tab-pane fade" id="social">
                <div class="card">
                    <div class="card-body">
                        @foreach($settings['social'] ?? [] as $platform => $url)
                        <div class="mb-3">
                            <label class="form-label">{{ ucfirst($platform) }} URL</label>
                            <input type="url" class="form-control @error("social.$platform") is-invalid @enderror"
                                   name="social[{{ $platform }}]"
                                   value="{{ old("social.$platform", $url) }}"
                                   placeholder="https://">
                            @error("social.$platform")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3" id="saveSettings">Save Settings</button>
    </form>
</div>

<script>
document.getElementById('saveSettings').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('settingsForm').submit();
});

// Mobile image upload functionality
let originalFiles = {};

// Handle mobile image file selection
function handleMobileImageSelect(input) {
    const inputId = input.id;
    const file = input.files[0];
    
    if (!file) return;
    
    // Parse the input ID to determine field identifier
    const parts = inputId.split('-');
    let fieldIdentifier;
    
    if (parts.length >= 2) {
        fieldIdentifier = parts[1];
        console.log('Field detected:', fieldIdentifier);
    } else {
        fieldIdentifier = inputId;
        console.log('Using input ID as field identifier:', fieldIdentifier);
    }
    
    // Store original file for later use
    originalFiles[fieldIdentifier] = file;
    console.log('Stored file for field:', fieldIdentifier, 'File name:', file.name);
    
    // Show compression options
    const container = document.getElementById('mobile-upload-' + fieldIdentifier);
    const options = container.querySelector('.compression-options');
    options.classList.remove('d-none');
    
    // Set up quality slider event listener
    const qualitySlider = document.getElementById('quality-' + fieldIdentifier);
    const qualityValue = qualitySlider.closest('.mb-2').querySelector('.quality-value');
    
    qualitySlider.addEventListener('input', function() {
        qualityValue.textContent = this.value + '%';
        previewCompressedImage(fieldIdentifier);
    });
    
    // Set up max width select event listener
    const maxWidthSelect = document.getElementById('max-width-' + fieldIdentifier);
    maxWidthSelect.addEventListener('change', function() {
        previewCompressedImage(fieldIdentifier);
    });
    
    // Preview the image
    previewCompressedImage(fieldIdentifier);
}

// Preview compressed image with current settings
function previewCompressedImage(fieldIdentifier) {
    const file = originalFiles[fieldIdentifier];
    if (!file) return;
    
    const container = document.getElementById('mobile-upload-' + fieldIdentifier);
    const qualitySlider = document.getElementById('quality-' + fieldIdentifier);
    const widthSelect = document.getElementById('max-width-' + fieldIdentifier);
    const previewContainer = container.querySelector('.image-preview-container');
    const previewImage = container.querySelector('.preview-image');
    const fileInfo = container.querySelector('.file-info');
    
    const quality = parseInt(qualitySlider.value) / 100;
    const maxWidth = parseInt(widthSelect.value);
    
    // Create a FileReader to read the image file
    const reader = new FileReader();
    reader.onload = function(e) {
        // Create an image element to get dimensions
        const img = new Image();
        img.onload = function() {
            // Show preview container
            previewContainer.classList.remove('d-none');
            
            // Calculate dimensions
            let width = img.width;
            let height = img.height;
            
            if (maxWidth > 0 && width > maxWidth) {
                const ratio = maxWidth / width;
                width = maxWidth;
                height = Math.round(height * ratio);
            }
            
            // Create canvas for preview
            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);
            
            // Update preview image
            previewImage.src = canvas.toDataURL('image/jpeg', quality);
            
            // Estimate file size
            const dataUrl = canvas.toDataURL('image/jpeg', quality);
            const estimatedSize = Math.round((dataUrl.length - 'data:image/jpeg;base64,'.length) * 0.75 / 1024);
            
            // Update file info
            fileInfo.textContent = `${width}x${height}px, ~${estimatedSize}KB`;
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

// Cancel compression and reset the input
function cancelCompression(fieldIdentifier) {
    const container = document.getElementById('mobile-upload-' + fieldIdentifier);
    const fileInput = document.getElementById('input-' + fieldIdentifier);
    const options = container.querySelector('.compression-options');
    const previewContainer = container.querySelector('.image-preview-container');
    
    // Reset file input
    fileInput.value = '';
    
    // Hide options and preview
    options.classList.add('d-none');
    previewContainer.classList.add('d-none');
    
    // Remove stored file
    delete originalFiles[fieldIdentifier];
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
    
    const container = document.getElementById('mobile-upload-' + fieldIdentifier);
    const qualitySlider = document.getElementById('quality-' + fieldIdentifier);
    const widthSelect = document.getElementById('max-width-' + fieldIdentifier);
    const hiddenInput = document.getElementById('compressed-' + fieldIdentifier);
    const options = container.querySelector('.compression-options');
    
    if (!container || !qualitySlider || !widthSelect || !hiddenInput || !fileInput || !options) {
        console.error('Required elements not found for field:', fieldIdentifier);
        return;
    }
    
    const quality = parseInt(qualitySlider.value) / 100;
    const maxWidth = parseInt(widthSelect.value);
    
    console.log('Compression settings for', fieldIdentifier, '- Quality:', quality, 'Max Width:', maxWidth);
    
    // Create a FileReader to read the image file
    const reader = new FileReader();
    reader.onload = function(e) {
        // Create an image element to get dimensions
        const img = new Image();
        img.onload = function() {
            // Calculate dimensions
            let width = img.width;
            let height = img.height;
            
            if (maxWidth > 0 && width > maxWidth) {
                const ratio = maxWidth / width;
                width = maxWidth;
                height = Math.round(height * ratio);
            }
            
            // Create canvas for compression
            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);
            
            // Get compressed image as base64
            const compressedBase64 = canvas.toDataURL('image/jpeg', quality).split(',')[1];
            
            // Disable the file input while uploading
            fileInput.disabled = true;
            
            // Show loading message
            options.innerHTML = '<div class="alert alert-info">Uploading and optimizing image...</div>';
            
            // Send to server for further optimization
            fetch('{{ route("mobile.image.upload") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    image: compressedBase64,
                    quality: quality * 100,
                    max_width: maxWidth
                })
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

// Initialize all mobile image inputs on page load
document.addEventListener('DOMContentLoaded', function() {
    const mobileImageInputs = document.querySelectorAll('.mobile-image-input');
    console.log('Found', mobileImageInputs.length, 'mobile image inputs');
    
    mobileImageInputs.forEach(input => {
        console.log('Initializing mobile image input:', input.id);
    });
});
</script>
@endsection
