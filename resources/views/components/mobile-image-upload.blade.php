@php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
@endphp

@props([
    'name' => 'mobile_banner_image',
    'label' => 'Mobile Banner Photo',
    'altName' => 'mobile_banner_image_alt',
    'altLabel' => 'Mobile Banner Photo Alt Text',
    'value' => null,
    'altValue' => null,
    'required' => false,
    'altRequired' => false,
    'fieldId' => null,
    'fieldIdentifier' => '',
    'multiple' => false,
    'uploadUrl' => null
])

@php
    // Generate a unique identifier if not provided
    if (empty($fieldIdentifier)) {
        $fieldIdentifier = 'field-' . uniqid();
    }
@endphp

@php
    // Generate a unique field identifier for this instance
    $fieldId = $fieldId ?? 'mobile-upload-' . str_replace(['[', ']', ' '], '-', $name) . '-' . uniqid();
    $inputId = 'input-' . $fieldIdentifier;
    $qualityId = 'quality-' . $fieldIdentifier;
    $maxWidthId = 'max-width-' . $fieldIdentifier;
    $compressedId = 'compressed-' . $fieldIdentifier;
    $previewContainerId = 'preview-container-' . $fieldIdentifier;
    $previewImageId = 'preview-image-' . $fieldIdentifier;
    $fileInfoId = 'file-info-' . $fieldIdentifier;
    $altFieldId = $altName . '-' . $fieldIdentifier;
    $hasError = $errors->has($name) ? 'is-invalid' : '';
    $hasAltError = $errors->has($altName) ? 'is-invalid' : '';
    $currentImage = old($name, $value);
    $currentAlt = old($altName, $altValue);

@endphp

<div class="mb-3">
    <label for="input-{{ $fieldIdentifier }}" class="form-label">{{ $label }}</label>
    @php
    // Set default upload URL if not provided
    $uploadUrl = $uploadUrl ?? (Route::has('mobile.image.upload') ? route('mobile.image.upload') : '/mobile-image-upload');
    // Ensure the upload URL is properly encoded
    $uploadUrl = e($uploadUrl);
@endphp

<div class="mobile-image-upload" 
     id="mobile-upload-{{ $fieldIdentifier }}"
     data-upload-url="{{ $uploadUrl }}">
        <div class="upload-container" data-upload-url="{{ $uploadUrl }}">
            <div class="mb-2">
                <div class="d-flex align-items-center">
                    <input type="file" class="form-control mobile-image-input @error($name) is-invalid @enderror" 
                        id="input-{{ $fieldIdentifier }}" 
                        name="{{ $name }}" 
                        accept="image/*" 
                        capture="environment"
                        data-field="{{ $fieldIdentifier }}"
                        data-preview-container="preview-container-{{ $fieldIdentifier }}"
                        data-preview-image="preview-image-{{ $fieldIdentifier }}"
                        data-file-info="file-info-{{ $fieldIdentifier }}"
                        onchange="handleMobileImageSelect(this)">
                    @error($name)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="compression-options mb-2 d-none">
                <div class="card p-3">
                    <div class="mb-2">
                        <label class="form-label">Image Quality</label>
                        <input type="range" class="form-range quality-slider" min="10" max="100" value="70" 
                            id="quality-{{ $fieldIdentifier }}" data-field="{{ $fieldIdentifier }}">
                        <div class="d-flex justify-content-between">
                            <small>Lower (Smaller File)</small>
                            <small class="quality-value">70%</small>
                            <small>Higher (Better Quality)</small>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <label class="form-label">Max Width</label>
                        <select class="form-select max-width" id="max-width-{{ $fieldIdentifier }}" data-field="{{ $fieldIdentifier }}" style="display: block !important; width: 100%;">
                            <option value="800" selected>Small (800px)</option>
                            <option value="1200">Medium (1200px)</option>
                            <option value="1600">Large (1600px)</option>
                            <option value="0">Original Size</option>
                        </select>
                    </div>
                    
                    <div class="image-preview-container mb-2 {{ $currentImage ? '' : 'd-none' }}" id="preview-container-{{ $fieldIdentifier }}">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label mb-0">Preview</label>
                            <div class="file-info small text-muted" id="{{ $fileInfoId }}">
                                @if($currentImage)
                                    Existing image
                                @endif
                            </div>
                        </div>
                        @if($currentImage)
                            <img src="{{ Storage::url($currentImage) }}" class="img-fluid img-thumbnail preview-image" id="{{ $previewImageId }}" style="max-height: 200px;">
                        @else
                            <img src="" class="img-fluid img-thumbnail preview-image d-none" id="{{ $previewImageId }}" style="max-height: 200px;">
                        @endif
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary btn-sm cancel-compression" 
                            data-field="{{ $fieldIdentifier }}">Cancel</button>
                        <button type="button" class="btn btn-primary btn-sm apply-compression" 
                            data-field="{{ $fieldIdentifier }}">Apply & Upload</button>
                    </div>
                </div>
            </div>
            
            <input type="hidden" name="{{ $name }}_compressed" id="{{ $compressedId }}" class="compressed-image-data">
        </div>
    </div>
</div>

@push('styles')
    @once
        <link href="{{ asset('css/mobile-upload.css') }}" rel="stylesheet">
    @endonce
@endpush
