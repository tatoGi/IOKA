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
    'uploadRoute' => route('mobile.image.upload'),
    'fieldIdentifier' => '',
    'multiple' => false
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

<div id="mobile-upload-{{ $fieldId }}" class="mb-3">
    <label for="{{ $inputId }}" class="form-label">{{ $label }}
        @if($required) <span class="text-danger">*</span> @endif
    </label>

    <div class="mobile-image-upload" id="{{ $fieldId }}" data-field-id="{{ $fieldIdentifier }}">
        <div class="mb-2">
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary"
                        onclick="document.getElementById('{{ $inputId }}').click()">
                    <i class="fas fa-folder-open me-1"></i> Choose from Gallery
                </button>

                <input type="file"
                       class="form-control d-none mobile-image-input"
                       id="{{ $inputId }}"
                       name="{{ $name }}"
                       accept="image/*"
                       capture="environment"
                       data-field="{{ $fieldIdentifier }}"
                       @if($multiple) multiple @endif
                       data-multiple-upload="{{ $multiple ? 'true' : 'false' }}"
                       onchange="handleMobileImageSelect(this)">

                @error($name)
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Current Image Preview -->
        @if($currentImage)
            @php
                // Ensure we have a proper URL
                $imageUrl = $currentImage;

                // If it's a relative path, convert to storage URL
                if (!Str::startsWith($imageUrl, ['http://', 'https://', '/storage/'])) {
                    $imageUrl = Storage::url($imageUrl);
                }

                // Get the relative path for storage check - handle both full URLs and relative paths
                $relativePath = $currentImage;

                // If it's a full URL, extract the path
                if (Str::startsWith($currentImage, ['http://', 'https://', '/storage/'])) {
                    $urlParts = parse_url($currentImage);
                    $relativePath = ltrim($urlParts['path'], '/');
                    $relativePath = str_replace('storage/', '', $relativePath);
                }

                // Check if the file exists in storage
                $fileExists = Storage::disk('public')->exists($relativePath);

                // For debugging
                $storage = Storage::disk('public');
                $fullPath = $storage->path($relativePath);
                $fileExists = file_exists($fullPath);

                $debugInfo = [
                    'original_path' => $currentImage,
                    'generated_url' => $imageUrl,
                    'relative_path' => $relativePath,
                    'storage_disk' => 'public',
                    'file_exists' => $fileExists ? 'true' : 'false',
                    'storage_path' => $storage->path(''),
                    'full_path' => $fileExists ? $fullPath : 'not_found',
                    'file_permissions' => $fileExists ? substr(sprintf('%o', fileperms($fullPath)), -4) : 'n/a',
                    'directory_permissions' => is_dir(dirname($fullPath)) ?
                        substr(sprintf('%o', fileperms(dirname($fullPath))), -4) : 'n/a',
                    'is_readable' => $fileExists ? (is_readable($fullPath) ? 'yes' : 'no') : 'n/a',
                    'is_writable' => $fileExists ? (is_writable($fullPath) ? 'yes' : 'no') : 'n/a'
                ];

                // If file doesn't exist, try to find it in the sections directory
                if (!$fileExists) {
                    $filename = basename($relativePath);
                    $files = $storage->files('sections');
                    $debugInfo['files_in_sections'] = [];

                    foreach ($files as $file) {
                        $debugInfo['files_in_sections'][] = $file;
                        if (basename($file) === $filename) {
                            $relativePath = $file;
                            $fileExists = true;
                            $imageUrl = $storage->url($file);
                            $debugInfo['found_as'] = $file;
                            break;
                        }
                    }

                    if (!$fileExists) {
                        // Try case-insensitive search as last resort
                        $filenameLower = strtolower($filename);
                        foreach ($files as $file) {
                            if (strtolower(basename($file)) === $filenameLower) {
                                $relativePath = $file;
                                $fileExists = true;
                                $imageUrl = $storage->url($file);
                                $debugInfo['found_as'] = $file;
                                $debugInfo['case_insensitive_match'] = true;
                                break;
                            }
                        }
                    }
                }
            @endphp

            <div class="current-image mb-3">
                <p class="small text-muted mb-1">Current Image:</p>

                @if($fileExists)
                    <div class="position-relative">
                        @php
                            // Ensure we have a proper URL
                            $displayUrl = $imageUrl;
                            if (!Str::startsWith($displayUrl, ['http://', 'https://', '/storage/'])) {
                                $displayUrl = Storage::url($displayUrl);
                            }
                            // Ensure it starts with a slash
                            $displayUrl = Str::startsWith($displayUrl, '/') ? $displayUrl : '/' . ltrim($displayUrl, '/');
                            // Ensure it doesn't have double slashes
                            $displayUrl = str_replace('//', '/', $displayUrl);
                        @endphp
                        <img src="{{ $displayUrl }}"
                             class="img-thumbnail"
                             style="max-height: 150px; display: block;"
                             alt="Current {{ $label }}"
                             onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling?.classList.remove('d-none');">
                        <div class="alert alert-warning d-none" style="margin-top: 10px;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Image could not be loaded. The file may have been moved or deleted.
                            @if(app()->environment('local'))
                                <div class="mt-2 small">Tried to load from: {{ $displayUrl }}</div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        The image file was not found in storage. Please re-upload the image.
                        @if(app()->environment('local'))
                            <div class="mt-2 small text-muted">
                                <strong>Debug Info:</strong>
                                <pre class="mb-0">{{ json_encode($debugInfo, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        <!-- Compression Options Container -->
        <div id="compression-options-container-{{ $fieldIdentifier }}" class="mb-3 d-none">
            <!-- Individual file options will be added here by JavaScript -->

            <!-- File info for the selected file -->
            <div class="file-info mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">
                        <span id="file-name-{{ $fieldIdentifier }}" class="text-truncate">No file selected</span>
                        <span id="file-status-{{ $fieldIdentifier }}" class="badge bg-success ms-2 d-none">Uploaded</span>
                    </div>
                    <div class="text-muted small">
                        <span id="file-size-{{ $fieldIdentifier }}">-</span>
                    </div>
                </div>

                <!-- Compression Options (initially hidden) -->
                <div id="compression-options-{{ $fieldIdentifier }}" class="d-none">
                    <div class="mb-2">
                        <label for="{{ $qualityId }}" class="form-label small mb-1">
                            Quality: <span id="quality-value-{{ $fieldIdentifier }}">65</span>%
                        </label>
                        <input type="range"
                               class="form-range"
                               id="{{ $qualityId }}"
                               min="10"
                               max="100"
                               step="5"
                               value="65"
                               oninput="document.getElementById('quality-value-{{ $fieldIdentifier }}').textContent = this.value;">
                    </div>

                    <div class="mb-2">
                        <label for="{{ $maxWidthId }}" class="form-label small mb-1">
                            Max Width: <span id="max-width-value-{{ $fieldIdentifier }}">800</span>px
                        </label>
                        <input type="range"
                               class="form-range"
                               id="{{ $maxWidthId }}"
                               min="320"
                               max="1920"
                               step="10"
                               value="800"
                               oninput="document.getElementById('max-width-value-{{ $fieldIdentifier }}').textContent = this.value;">
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="button"
                            class="btn btn-sm btn-primary flex-grow-1"
                            onclick="applyCompression('{{ $fieldIdentifier }}')">
                        <i class="fas fa-upload me-1"></i> Upload
                    </button>
                    <button type="button"
                            class="btn btn-sm btn-outline-secondary"
                            onclick="cancelCompression('{{ $fieldIdentifier }}')">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                </div>
            </div>

            <!-- Preview container for multiple files -->
            <div id="{{ $previewContainerId }}" class="d-flex flex-wrap gap-2 mb-3">
                <!-- Preview items will be added here by JavaScript -->
            </div>

            <!-- Hidden input to store the compressed image paths -->
            <input type="hidden"
                   id="{{ $compressedId }}"
                   name="{{ $name }}"
                   value="{{ $currentImage }}"
                   data-field="{{ $fieldIdentifier }}">

            <!-- Hidden input for the upload route -->
            <input type="hidden"
                   id="upload-route-{{ $fieldIdentifier }}"
                   value="{{ $uploadRoute }}">
        </div>

        <!-- Hidden inputs container for form submission -->
        <div id="compressed-files-{{ $fieldIdentifier }}" class="compressed-files-container">
            @if($value && !is_array($value))
                <input type="hidden" name="{{ $name }}_data[]" value="{{ $value }}">
                <input type="hidden" name="{{ $name }}_name[]" value="{{ basename($value) }}">
            @elseif(is_array($value))
                @foreach($value as $index => $val)
                    <input type="hidden" name="{{ $name }}_data[]" value="{{ $val }}">
                    <input type="hidden" name="{{ $name }}_name[]" value="{{ basename($val) }}">
                @endforeach
            @endif
        </div>

        <div id="max-width-container-{{ $fieldIdentifier }}" class="mb-3 d-none">
            <label for="{{ $maxWidthId }}" class="form-label">Max Width</label>
            <select class="form-select max-width"
                    id="{{ $maxWidthId }}"
                    data-field="{{ $fieldIdentifier }}"
                    style="width: 100%;">
                <option value="800" selected>Small (800px)</option>
                <option value="1200">Medium (1200px)</option>
                <option value="1600">Large (1600px)</option>
                <option value="0">Original Size</option>
            </select>
        </div>

        <div id="{{ $previewContainerId }}" class="image-preview-container mb-3 d-none">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label mb-0">Preview</label>
                <div id="{{ $fileInfoId }}" class="small text-muted"></div>
            </div>
            <img id="{{ $previewImageId }}" src="#" class="img-fluid img-thumbnail preview-image" style="max-height: 200px;">
        </div>

        <div id="action-buttons-{{ $fieldIdentifier }}" class="d-flex justify-content-between mb-3 d-none">
            <button type="button" class="btn btn-outline-secondary btn-sm"
                    onclick="cancelCompression('{{ $fieldIdentifier }}')">
                <i class="fas fa-times me-1"></i> Cancel
            </button>
            <button type="button" class="btn btn-primary btn-sm"
                    onclick="applyCompression('{{ $fieldIdentifier }}')">
                <i class="fas fa-check me-1"></i> Apply & Upload
            </button>
        </div>

        <!-- Hidden input to store the compressed image data -->
        <input type="hidden"
               id="compressed-{{ $fieldIdentifier }}"
               name="{{ $name }}_compressed"
               class="compressed-image-data"
               value="{{ $value ? $value : '' }}">

        <!-- Hidden input for upload route -->
        <input type="hidden"
               id="upload-route-{{ $fieldIdentifier }}"
               value="{{ $uploadRoute }}">

        <input type="hidden"
               id="field-identifier-{{ $fieldId }}"
               value="{{ $fieldIdentifier }}">

        <!-- Hidden input for delete route -->
        <input type="hidden"
               id="delete-route-{{ $fieldIdentifier }}"
               value="{{ route('mobile.image.delete') }}">

        <!-- Hidden input for CSRF token -->
        <input type="hidden"
               id="csrf-token-{{ $fieldIdentifier }}"
               value="{{ csrf_token() }}">
    </div>

</div>

@push('styles')
    @once
        <link href="{{ asset('css/mobile-upload.css') }}" rel="stylesheet">
    @endonce
@endpush

@push('scripts')
    @once
        <script src="{{ asset('js/mobile-upload.js') }}"></script>
    @endonce
@endpush
