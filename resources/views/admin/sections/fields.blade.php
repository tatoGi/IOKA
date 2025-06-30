@php
    // Get the section configuration from the page type
    $page = \App\Models\Page::find($pageId);
    $pageType = collect(Config::get('PageTypes'))->firstWhere('id', $page->type_id);
    $sectionConfig = $pageType['sections'][$sectionKey] ?? null;
   // Debugging line to check the section configuration
    // Helper function to safely get value
    function getSafeValue($value)
    {
        // Debugging line to check the value
        if (is_array($value)) {
            return json_encode($value);
        }
        return $value;
    }

@endphp

<!-- CSS and JS fixes for select dropdown options display -->
<link rel="stylesheet" href="{{ asset('css/form-select-fix.css') }}">
<script src="{{ asset('js/form-select-fix.js') }}"></script>

@if ($sectionConfig && isset($sectionConfig['fields']))
    @include('admin.components.form-errors')
    @foreach ($sectionConfig['fields'] as $fieldKey => $field)
        <div class="mb-3">
            <label for="{{ $fieldKey }}" class="form-label">{{ $field['label'] }}</label>

            @switch($field['type'])
                @case('text')
                    <input type="text" class="form-control @error($fieldKey) is-invalid @enderror" id="{{ $fieldKey }}"
                        name="fields[{{ $fieldKey }}]"
                        value="{{ old("fields.$fieldKey", $additionalFields[$fieldKey] ?? ($field['default'] ?? '')) }}"
                        {{ isset($field['required']) && $field['required'] ? 'required' : '' }}
                        placeholder="{{ $field['placeholder'] ?? '' }}">
                @break

                @case('textarea')
                    <textarea class="editor form-control @error($fieldKey) is-invalid @enderror" id="{{ $fieldKey }}"
                        name="fields[{{ $fieldKey }}]" {{ isset($field['required']) && $field['required'] ? 'required' : '' }}>{{ getSafeValue($additionalFields[$fieldKey] ?? ($field['default'] ?? '')) }}</textarea>
                @break

                @case('description')

                    <textarea class="editor form-control @error($fieldKey) is-invalid @enderror" id="{{ $fieldKey }}"
                        name="fields[{{ $fieldKey }}]" {{ isset($field['required']) && $field['required'] ? 'required' : '' }}>{{ getSafeValue($additionalFields[$fieldKey] ?? ($field['default'] ?? '')) }}</textarea>
                @break

                @case('image')
                    @if (isset($additionalFields[$fieldKey]))
                        <div class="mb-2">
                            <img src="{{ Storage::url($additionalFields[$fieldKey]) }}" alt="Current Image"
                                class="img-thumbnail" style="max-height: 200px;">
                            <button type="button" class="btn btn-danger btn-sm delete-image" data-field="{{ $fieldKey }}">
                                Delete Image
                            </button>
                        </div>
                        <input type="hidden" name="old_{{ $fieldKey }}" value="{{ $additionalFields[$fieldKey] }}">
                    @endif

                    <div class="mobile-image-upload" id="mobile-upload-{{ $fieldKey }}">
                        <div class="mb-2">
                            <div class="d-flex align-items-center">
                                <input type="file" class="form-control mobile-image-input @error($fieldKey) is-invalid @enderror"
                                    id="input-{{ $fieldKey }}" accept="image/*"
                                    {{ isset($field['required']) && $field['required'] ? 'required' : '' }}
                                    data-field="{{ $fieldKey }}"
                                    onchange="handleMobileImageSelect(this)">
                            </div>
                        </div>

                        <div class="compression-options mb-2 d-none">
                            <div class="card p-3">
                                <div class="mb-2">
                                    <label class="form-label">Image Quality</label>
                                    <input type="range" class="form-range quality-slider" min="10" max="100" value="70"
                                        id="quality-{{ $fieldKey }}" data-field="{{ $fieldKey }}">
                                    <div class="d-flex justify-content-between">
                                        <small>Lower (Smaller File)</small>
                                        <small class="quality-value">70%</small>
                                        <small>Higher (Better Quality)</small>
                                    </div>
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
                                        onclick="cancelCompression('{{ $fieldKey }}')">Cancel</button>
                                    <button type="button" class="btn btn-primary btn-sm apply-compression"
                                        onclick="applyCompression('{{ $fieldKey }}')">Apply & Upload</button>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="fields[{{ $fieldKey }}]" id="compressed-{{ $fieldKey }}" class="compressed-image-data">
                    </div>
                @break

                @case('photo')
                    @if (isset($additionalFields[$fieldKey]))
                        <div class="mb-2">
                            <img src="{{ Storage::url($additionalFields[$fieldKey]) }}" alt="Current Image"
                                class="img-thumbnail" style="max-height: 200px;">
                            <button type="button" class="btn btn-danger btn-sm delete-image" data-field="{{ $fieldKey }}">
                                Delete Image
                            </button>
                        </div>
                        <input type="hidden" name="old_{{ $fieldKey }}" value="{{ $additionalFields[$fieldKey] }}">
                    @endif

                    <div class="mobile-image-upload" id="mobile-upload-{{ $fieldKey }}">
                        <div class="mb-2">
                            <div class="d-flex align-items-center">
                                <input type="file" class="form-control mobile-image-input @error($fieldKey) is-invalid @enderror"
                                    id="input-{{ $fieldKey }}" accept="image/*"
                                    {{ isset($field['required']) && $field['required'] ? 'required' : '' }}
                                    data-field="{{ $fieldKey }}"
                                    onchange="handleMobileImageSelect(this)">
                            </div>
                        </div>

                        <div class="compression-options mb-2 d-none">
                            <div class="card p-3">
                                <div class="mb-2">
                                    <label class="form-label">Image Quality</label>
                                    <input type="range" class="form-range quality-slider" min="10" max="100" value="70"
                                        id="quality-{{ $fieldKey }}" data-field="{{ $fieldKey }}">
                                    <div class="d-flex justify-content-between">
                                        <small>Lower (Smaller File)</small>
                                        <small class="quality-value">70%</small>
                                        <small>Higher (Better Quality)</small>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Max Width</label>
                                    <select class="form-select max-width" id="max-width-{{ $fieldKey }}" data-field="{{ $fieldKey }}" style="display: block !important; width: 100%;">
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
                                        onclick="cancelCompression('{{ $fieldKey }}')">Cancel</button>
                                    <button type="button" class="btn btn-primary btn-sm apply-compression"
                                        onclick="applyCompression('{{ $fieldKey }}')">Apply & Upload</button>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="fields[{{ $fieldKey }}]" id="compressed-{{ $fieldKey }}" class="compressed-image-data">
                    </div>
                @break

                @case('mobile_image')
                    @if (isset($additionalFields[$fieldKey]) && !empty($additionalFields[$fieldKey]))
                        <div class="mb-2">
                            <img src="{{ Storage::url($additionalFields[$fieldKey]) }}" alt="Current Image"
                                class="img-thumbnail" style="max-height: 200px;">
                            <button type="button" class="btn btn-danger btn-sm delete-image" data-field="{{ $fieldKey }}">
                                Delete Image
                            </button>
                        </div>
                        <input type="hidden" name="old_{{ $fieldKey }}" value="{{ $additionalFields[$fieldKey] }}">
                    @endif

                    <div class="mobile-image-upload" id="mobile-upload-{{ $fieldKey }}">
                        <div class="mb-2">
                            <div class="d-flex align-items-center">
                                <input type="file" class="form-control mobile-image-input @error($fieldKey) is-invalid @enderror"
                                    id="input-{{ $fieldKey }}" accept="image/*"
                                    {{ isset($field['required']) && $field['required'] ? 'required' : '' }}
                                    data-field="{{ $fieldKey }}"
                                    onchange="handleMobileImageSelect(this)">
                            </div>
                        </div>

                        <div class="compression-options mb-2 d-none">
                            <div class="card p-3">
                                <div class="mb-2">
                                    <label class="form-label">Image Quality</label>
                                    <input type="range" class="form-range quality-slider" min="10" max="100" value="70"
                                        id="quality-{{ $fieldKey }}" data-field="{{ $fieldKey }}">
                                    <div class="d-flex justify-content-between">
                                        <small>Lower (Smaller File)</small>
                                        <small class="quality-value">70%</small>
                                        <small>Higher (Better Quality)</small>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Max Width</label>
                                    <select class="form-select max-width" id="max-width-{{ $fieldKey }}" data-field="{{ $fieldKey }}" style="display: block !important; width: 100%;">
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
                                        onclick="cancelCompression('{{ $fieldKey }}')">Cancel</button>
                                    <button type="button" class="btn btn-primary btn-sm apply-compression"
                                        onclick="applyCompression('{{ $fieldKey }}')">Apply & Upload</button>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="fields[{{ $fieldKey }}]" id="compressed-{{ $fieldKey }}" class="compressed-image-data">
                    </div>
                @break

                @case('repeater')
                    <div class="repeater-container" data-field="{{ $fieldKey }}" data-min-items="{{ $field['min_items'] ?? '0' }}">
                        <div class="repeater-items">

                            @if (isset($additionalFields[$fieldKey]) && is_array($additionalFields[$fieldKey]))
                                @foreach ($additionalFields[$fieldKey] as $index => $item)
                                    <div class="repeater-item card mb-3" data-index="{{ $index }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-end mb-2">
                                                <button type="button" class="btn btn-danger btn-sm remove-repeater-item" onclick="removeRepeaterItem(this)">
                                                    <i class="fas fa-trash"></i> Remove
                                                </button>
                                            </div>
                                            @foreach ($field['fields'] as $repeaterFieldKey => $repeaterField)
                                                <div class="mb-3">
                                                    <label class="form-label">{{ $repeaterField['label'] }}</label>
                                                    @switch($repeaterField['type'])
                                                        @case('text')
                                                            <input type="text" class="form-control"
                                                                name="fields[{{ $fieldKey }}][{{ $index }}][{{ $repeaterFieldKey }}]"
                                                                value="{{ getSafeValue($item[$repeaterFieldKey] ?? '') }}"
                                                                {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                                placeholder="{{ $repeaterField['placeholder'] ?? '' }}">
                                                        @break

                                                        @case('email')
                                                            <input type="email" class="form-control"
                                                                name="fields[{{ $fieldKey }}][{{ $index }}][{{ $repeaterFieldKey }}]"
                                                                value="{{ getSafeValue($item[$repeaterFieldKey] ?? '') }}"
                                                                {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                                placeholder="{{ $repeaterField['placeholder'] ?? '' }}">
                                                        @break

                                                        @case('textarea')

                                                            <textarea class="editor form-control"
                                                                name="fields[{{ $fieldKey }}][{{ $index }}][{{ $repeaterFieldKey }}]"
                                                                {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                                placeholder="{{ $repeaterField['placeholder'] ?? '' }}">{{ getSafeValue($item[$repeaterFieldKey] ?? '') }}</textarea>
                                                        @break

                                                        @case('description')

                                                            <textarea class="editor form-control"
                                                                name="fields[{{ $fieldKey }}][{{ $index }}][{{ $repeaterFieldKey }}]"
                                                                {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                                placeholder="{{ $repeaterField['placeholder'] ?? '' }}">{{ getSafeValue($item[$repeaterFieldKey] ?? '') }}</textarea>
                                                        @break

                                                        @case('image')
                                                            @if (isset($item[$repeaterFieldKey]))
                                                                <div class="mb-2">
                                                                    <img src="{{ Storage::url($item[$repeaterFieldKey]) }}"
                                                                        alt="Current Image" class="img-thumbnail"
                                                                        style="max-height: 200px;">
                                                                    <button type="button" class="btn btn-danger btn-sm delete-image" data-field="{{ $repeaterFieldKey }}" data-index="{{ $index }}" data-repeater-field="{{ $fieldKey }}">
                                                                        Delete Image
                                                                    </button>
                                                                </div>
                                                                <input type="hidden"
                                                                    name="old_{{ $repeaterFieldKey }}_{{ $index }}"
                                                                    value="{{ $item[$repeaterFieldKey] }}">
                                                            @endif
                                                            <input type="file" class="form-control"
                                                                name="fields[{{ $fieldKey }}][{{ $index }}][{{ $repeaterFieldKey }}]"
                                                                accept="image/*">
                                                        @break

                                                        @case('photo')
                                                            @if (isset($item[$repeaterFieldKey]))
                                                                <div class="mb-2">
                                                                    <img src="{{ Storage::url($item[$repeaterFieldKey]) }}"
                                                                        alt="Current Image" class="img-thumbnail"
                                                                        style="max-height: 200px;">
                                                                    <button type="button" class="btn btn-danger btn-sm delete-image" data-field="{{ $repeaterFieldKey }}" data-index="{{ $index }}" data-repeater-field="{{ $fieldKey }}">
                                                                        Delete Image
                                                                    </button>
                                                                </div>
                                                                <input type="hidden"
                                                                    name="old_{{ $repeaterFieldKey }}_{{ $index }}"
                                                                    value="{{ $item[$repeaterFieldKey] }}">
                                                            @endif
                                                            <input type="file" class="form-control"
                                                                name="fields[{{ $fieldKey }}][{{ $index }}][{{ $repeaterFieldKey }}]"
                                                                accept="image/*">
                                                        @break

                                                        @case('mobile_image')
                                                            @if (isset($item[$repeaterFieldKey]))
                                                                <div class="mb-2">
                                                                    <img src="{{ Storage::url($item[$repeaterFieldKey]) }}"
                                                                        alt="Current Image" class="img-thumbnail"
                                                                        style="max-height: 200px;">
                                                                    <button type="button" class="btn btn-danger btn-sm delete-image" data-field="{{ $repeaterFieldKey }}" data-index="{{ $index }}" data-repeater-field="{{ $fieldKey }}">
                                                                        Delete Image
                                                                    </button>
                                                                </div>
                                                                <input type="hidden"
                                                                    name="old_{{ $repeaterFieldKey }}_{{ $index }}"
                                                                    value="{{ $item[$repeaterFieldKey] }}">
                                                            @endif
                                                            <div class="mobile-image-upload" id="mobile-upload-{{ $fieldKey }}-{{ $index }}-{{ $repeaterFieldKey }}">
                                                                <div class="mb-2">
                                                                    <div class="d-flex align-items-center">
                                                                        <input type="file" class="form-control mobile-image-input"
                                                                            id="input-{{ $fieldKey }}-{{ $index }}-{{ $repeaterFieldKey }}" accept="image/*"
                                                                            {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                                            data-field="{{ $fieldKey }}-{{ $index }}-{{ $repeaterFieldKey }}"
                                                                            data-repeater="true"
                                                                            data-repeater-index="{{ $index }}"
                                                                            data-repeater-key="{{ $repeaterFieldKey }}"
                                                                            onchange="handleMobileImageSelect(this)">
                                                                    </div>
                                                                </div>

                                                                <div class="compression-options mb-2 d-none">
                                                                    <div class="card p-3">
                                                                        <div class="mb-2">
                                                                            <label class="form-label">Image Quality</label>
                                                                            <input type="range" class="form-range quality-slider" min="10" max="100" value="70"
                                                                                id="quality-{{ $fieldKey }}-{{ $index }}-{{ $repeaterFieldKey }}" data-field="{{ $fieldKey }}-{{ $index }}-{{ $repeaterFieldKey }}">
                                                                            <div class="d-flex justify-content-between">
                                                                                <small>Lower (Smaller File)</small>
                                                                                <small class="quality-value">70%</small>
                                                                                <small>Higher (Better Quality)</small>
                                                                            </div>
                                                                        </div>

                                                                        <div class="mb-2">
                                                                            <label class="form-label">Max Width</label>
                                                                            <select class="form-select max-width" id="max-width-{{ $fieldKey }}-{{ $index }}-{{ $repeaterFieldKey }}" data-field="{{ $fieldKey }}-{{ $index }}-{{ $repeaterFieldKey }}" style="display: block !important; width: 100%;">
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
                                                                                onclick="cancelCompression('{{ $fieldKey }}-{{ $index }}-{{ $repeaterFieldKey }}')">Cancel</button>
                                                                            <button type="button" class="btn btn-primary btn-sm apply-compression"
                                                                                onclick="applyCompression('{{ $fieldKey }}-{{ $index }}-{{ $repeaterFieldKey }}')">Apply & Upload</button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="fields[{{ $fieldKey }}][{{ $index }}][{{ $repeaterFieldKey }}]" id="compressed-{{ $fieldKey }}-{{ $index }}-{{ $repeaterFieldKey }}" class="compressed-image-data" data-repeater="true" data-repeater-index="{{ $index }}" data-repeater-key="{{ $repeaterFieldKey }}">
                                                            </div>
                                                        @break

                                                        @case('select')
                                                            <select class="form-control select2"
                                                                name="fields[{{ $fieldKey }}][{{ $index }}][{{ $repeaterFieldKey }}]{{ isset($repeaterField['multiple']) && $repeaterField['multiple'] ? '[]' : '' }}"
                                                                {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                                {{ isset($repeaterField['multiple']) && $repeaterField['multiple'] ? 'multiple' : '' }}
                                                                data-placeholder="{{ $repeaterField['placeholder'] ?? (isset($repeaterField['multiple']) ? 'Select options...' : 'Select an option...') }}">
                                                                @if(isset($repeaterField['placeholder']))
                                                                    <option value=""></option>
                                                                @endif
                                                                @foreach($repeaterField['options'] as $value => $label)
                                                                    <option value="{{ $value }}"
                                                                        {{ (isset($item[$repeaterFieldKey]) && (
                                                                            (is_array($item[$repeaterFieldKey]) && in_array($value, $item[$repeaterFieldKey])) ||
                                                                            (!is_array($item[$repeaterFieldKey]) && $item[$repeaterFieldKey] == $value)
                                                                        )) ? 'selected' : '' }}>
                                                                        {{ $label }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @break
                                                        @default
                                                            <input type="{{ $repeaterField['type'] }}" class="form-control"
                                                                name="fields[{{ $fieldKey }}][{{ $index }}][{{ $repeaterFieldKey }}]"
                                                                value="{{ getSafeValue($item[$repeaterFieldKey] ?? '') }}"
                                                                {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}>
                                                    @endswitch
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <template class="repeater-template">
                            <div class="repeater-item card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-end mb-2">
                                        <button type="button" class="btn btn-danger btn-sm remove-repeater-item" onclick="removeRepeaterItem(this)">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </div>
                                    @foreach ($field['fields'] as $repeaterFieldKey => $repeaterField)
                                        <div class="mb-3">
                                            <label class="form-label">{{ $repeaterField['label'] }}</label>
                                            @switch($repeaterField['type'])
                                                @case('text')
                                                    <input type="text" class="form-control"
                                                        name="fields[{{ $fieldKey }}][__INDEX__][{{ $repeaterFieldKey }}]"
                                                        {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                        placeholder="{{ $repeaterField['placeholder'] ?? '' }}">
                                                @break

                                                @case('email')
                                                    <input type="email" class="form-control"
                                                        name="fields[{{ $fieldKey }}][__INDEX__][{{ $repeaterFieldKey }}]"
                                                        value="{{ getSafeValue($item[$repeaterFieldKey] ?? '') }}"
                                                        {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                        placeholder="{{ $repeaterField['placeholder'] ?? '' }}">
                                                @break

                                                @case('textarea')

                                                    <textarea class="editor form-control" name="fields[{{ $fieldKey }}][__INDEX__][{{ $repeaterFieldKey }}]"
                                                        {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                        placeholder="{{ $repeaterField['placeholder'] ?? '' }}"></textarea>
                                                @break

                                                @case('description')

                                                    <textarea class="editor form-control" name="fields[{{ $fieldKey }}][__INDEX__][{{ $repeaterFieldKey }}]"
                                                        {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                        placeholder="{{ $repeaterField['placeholder'] ?? '' }}"></textarea>
                                                @break

                                                @case('image')
                                                    <input type="file" class="form-control"
                                                        name="fields[{{ $fieldKey }}][__INDEX__][{{ $repeaterFieldKey }}]"
                                                        accept="image/*">
                                                @break

                                                @case('photo')
                                                    <input type="file" class="form-control"
                                                        name="fields[{{ $fieldKey }}][__INDEX__][{{ $repeaterFieldKey }}]"
                                                        accept="image/*">
                                                @break

                                                @case('mobile_image')
                                                    <div class="mobile-image-upload" id="mobile-upload-{{ $fieldKey }}-__INDEX__-{{ $repeaterFieldKey }}">
                                                        <div class="mb-2">
                                                            <div class="d-flex align-items-center">
                                                                <input type="file" class="form-control mobile-image-input"
                                                                    id="input-{{ $fieldKey }}-__INDEX__-{{ $repeaterFieldKey }}" accept="image/*"
                                                                    {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                                    data-field="{{ $fieldKey }}-__INDEX__-{{ $repeaterFieldKey }}"
                                                                    onchange="handleMobileImageSelect(this)">
                                                            </div>
                                                        </div>

                                                        <div class="compression-options mb-2 d-none">
                                                            <div class="card p-3">
                                                                <div class="mb-2">
                                                                    <label class="form-label">Image Quality</label>
                                                                    <input type="range" class="form-range quality-slider" min="10" max="100" value="70"
                                                                        id="quality-{{ $fieldKey }}-__INDEX__-{{ $repeaterFieldKey }}" data-field="{{ $fieldKey }}-__INDEX__-{{ $repeaterFieldKey }}">
                                                                    <div class="d-flex justify-content-between">
                                                                        <small>Lower (Smaller File)</small>
                                                                        <small class="quality-value">70%</small>
                                                                        <small>Higher (Better Quality)</small>
                                                                    </div>
                                                                </div>

                                                                <div class="mb-2">
                                                                    <label class="form-label">Max Width</label>
                                                                    <select class="form-select max-width" id="max-width-{{ $fieldKey }}-__INDEX__-{{ $repeaterFieldKey }}" data-field="{{ $fieldKey }}-__INDEX__-{{ $repeaterFieldKey }}">
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
                                                                        data-field-key="{{ $fieldKey }}" data-field-repeater-key="{{ $repeaterFieldKey }}">
                                                                        Cancel</button>
                                                                    <button type="button" class="btn btn-primary btn-sm apply-compression"
                                                                        data-field-key="{{ $fieldKey }}" data-field-repeater-key="{{ $repeaterFieldKey }}">
                                                                        Apply & Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <input type="hidden" name="fields[{{ $fieldKey }}][__INDEX__][{{ $repeaterFieldKey }}]" id="compressed-{{ $fieldKey }}-__INDEX__-{{ $repeaterFieldKey }}" class="compressed-image-data">
                                                    </div>
                                                @break

                                                @case('select')
                                                    <select class="form-control select2"
                                                            name="fields[{{ $fieldKey }}][__INDEX__][{{ $repeaterFieldKey }}]{{ isset($repeaterField['multiple']) && $repeaterField['multiple'] ? '[]' : '' }}"
                                                            {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                            {{ isset($repeaterField['multiple']) && $repeaterField['multiple'] ? 'multiple' : '' }}
                                                            data-placeholder="{{ $repeaterField['placeholder'] ?? (isset($repeaterField['multiple']) ? 'Select options...' : 'Select an option...') }}">
                                                        @if(isset($repeaterField['placeholder']))
                                                            <option value=""></option>
                                                        @endif
                                                        @foreach($repeaterField['options'] as $value => $label)
                                                            <option value="{{ $value }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                @break

                                                @default
                                                    <input type="{{ $repeaterField['type'] }}" class="form-control"
                                                        name="fields[{{ $fieldKey }}][__INDEX__][{{ $repeaterFieldKey }}]"
                                                        {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}>
                                            @endswitch
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </template>

                        <button type="button" class="btn btn-primary add-repeater-item mt-2">
                            Add {{ $field['label'] }}
                        </button>

                        <!-- Hidden input to track current state -->
                        <input type="hidden" name="repeater_state_{{ $fieldKey }}" value="{{ isset($additionalFields[$fieldKey]) ? count($additionalFields[$fieldKey]) : 0 }}" class="repeater-state-tracker">

                        <!-- Hidden input to track if field is intentionally empty -->
                        <input type="hidden" name="fields[{{ $fieldKey }}]" value="" class="repeater-empty-tracker">
                    </div>
                @break

                @case('group')
                    <div class="card">
                        <div class="card-body">
                            @foreach ($field['fields'] as $groupFieldKey => $groupField)
                                <div class="mb-3">
                                    <label class="form-label">{{ $groupField['label'] }}</label>
                                    @switch($groupField['type'])
                                        @case('textarea')
                                            <textarea class="editor form-control" name="fields[{{ $fieldKey }}][{{ $groupFieldKey }}]"
                                                {{ isset($groupField['required']) && $groupField['required'] ? 'required' : '' }}>{{ getSafeValue($additionalFields[$fieldKey][$groupFieldKey] ?? ($groupField['value'] ?? '')) }}</textarea>
                                        @break

                                        @default
                                            <input type="{{ $groupField['type'] }}" class="form-control"
                                                name="fields[{{ $fieldKey }}][{{ $groupFieldKey }}]"
                                                value="{{ getSafeValue($additionalFields[$fieldKey][$groupFieldKey] ?? ($groupField['value'] ?? '')) }}"
                                                {{ isset($groupField['required']) && $groupField['required'] ? 'required' : '' }}>
                                    @endswitch
                                </div>
                            @endforeach
                        </div>
                    </div>
                @break

                @case('tabs')
                    <div class="tabs-container">
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach ($field['tabs'] as $tabKey => $tab)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link @if ($loop->first) active @endif"
                                        id="tab-{{ $tabKey }}" data-bs-toggle="tab"
                                        data-bs-target="#content-{{ $tabKey }}" type="button" role="tab">
                                        {{ $tab['label'] }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content mt-3">
                            @foreach ($field['tabs'] as $tabKey => $tab)
                                <div class="tab-pane fade @if ($loop->first) show active @endif"
                                    id="content-{{ $tabKey }}" role="tabpanel">
                                    @foreach ($tab['fields'] as $tabFieldKey => $tabField)
                                        <div class="mb-3">
                                            <label class="form-label">{{ $tabField['label'] }}</label>
                                            @switch($tabField['type'])
                                                @case('image')
                                                    @if (isset($additionalFields[$fieldKey][$tabKey][$tabFieldKey]))
                                                        <div class="mb-2">
                                                            <img src="{{ Storage::url($additionalFields[$fieldKey][$tabKey][$tabFieldKey]) }}"
                                                                alt="Current Image" class="img-thumbnail" style="max-height: 200px;">
                                                            <button type="button" class="btn btn-danger btn-sm delete-image" data-field="{{ $tabFieldKey }}" data-tab="{{ $tabKey }}" data-tabs-field="{{ $fieldKey }}">
                                                                Delete Image
                                                            </button>
                                                        </div>
                                                        <input type="hidden" name="old_{{ $tabKey }}_{{ $tabFieldKey }}"
                                                            value="{{ $additionalFields[$fieldKey][$tabKey][$tabFieldKey] }}">
                                                    @endif
                                                    <input type="file" class="form-control"
                                                        name="fields[{{ $fieldKey }}][{{ $tabKey }}][{{ $tabFieldKey }}]"
                                                        accept="image/*">
                                                @break
                                                @case('mobile_image')
                                                @if (isset($additionalFields[$fieldKey][$tabKey][$tabFieldKey]) && !empty($additionalFields[$fieldKey][$tabKey][$tabFieldKey]))
                                                    <div class="mb-2">
                                                        <img src="{{ Storage::url($additionalFields[$fieldKey][$tabKey][$tabFieldKey]) }}" alt="Current Image"
                                                            class="img-thumbnail" style="max-height: 200px;">
                                                        <button type="button" class="btn btn-danger btn-sm delete-image" data-field="{{ $tabFieldKey }}" data-tab="{{ $tabKey }}" data-tabs-field="{{ $fieldKey }}">
                                                            Delete Image
                                                        </button>
                                                    </div>
                                                    <input type="hidden" name="old_{{ $fieldKey }}_{{ $tabKey }}_{{ $tabFieldKey }}" value="{{ $additionalFields[$fieldKey][$tabKey][$tabFieldKey] }}">
                                                @endif

                                                <div class="mobile-image-upload" id="mobile-upload-{{ $fieldKey }}_{{ $tabKey }}_{{ $tabFieldKey }}">
                                                    <div class="mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <input type="file" class="form-control mobile-image-input @error($fieldKey) is-invalid @enderror"
                                                                id="input-{{ $fieldKey }}_{{ $tabKey }}_{{ $tabFieldKey }}" accept="image/*"
                                                                {{ isset($tabField['required']) && $tabField['required'] ? 'required' : '' }}
                                                                data-field="{{ $fieldKey }}_{{ $tabKey }}_{{ $tabFieldKey }}"
                                                                onchange="handleMobileImageSelect(this)">
                                                        </div>
                                                    </div>

                                                    <div class="compression-options mb-2 d-none">
                                                        <div class="card p-3">
                                                            <div class="mb-2">
                                                                <label class="form-label">Image Quality</label>
                                                                <input type="range" class="form-range quality-slider" min="10" max="100" value="70"
                                                                    id="quality-{{ $fieldKey }}_{{ $tabKey }}_{{ $tabFieldKey }}" data-field="{{ $fieldKey }}_{{ $tabKey }}_{{ $tabFieldKey }}">
                                                                <div class="d-flex justify-content-between">
                                                                    <small>Lower (Smaller File)</small>
                                                                    <small class="quality-value">70%</small>
                                                                    <small>Higher (Better Quality)</small>
                                                                </div>
                                                            </div>

                                                            <div class="mb-2">
                                                                <label class="form-label">Max Width</label>
                                                                <select class="form-select max-width" id="max-width-{{ $fieldKey }}_{{ $tabKey }}_{{ $tabFieldKey }}" data-field="{{ $fieldKey }}_{{ $tabKey }}_{{ $tabFieldKey }}" style="display: block !important; width: 100%;">
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
                                                                    onclick="cancelCompression('{{ $fieldKey }}_{{ $tabKey }}_{{ $tabFieldKey }}')">Cancel</button>
                                                                <button type="button" class="btn btn-primary btn-sm apply-compression"
                                                                    onclick="applyCompression('{{ $fieldKey }}_{{ $tabKey }}_{{ $tabFieldKey }}')">Apply & Upload</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="fields[{{ $fieldKey }}][{{ $tabKey }}][{{ $tabFieldKey }}]" id="compressed-{{ $fieldKey }}_{{ $tabKey }}_{{ $tabFieldKey }}" class="compressed-image-data">
                                                </div>
                                            @break
                                                @case('photo')
                                                    @if (isset($additionalFields[$fieldKey][$tabKey][$tabFieldKey]))
                                                        <div class="mb-2">
                                                            <img src="{{ Storage::url($additionalFields[$fieldKey][$tabKey][$tabFieldKey]) }}"
                                                                alt="Current Image" class="img-thumbnail" style="max-height: 200px;">
                                                            <button type="button" class="btn btn-danger btn-sm delete-image" data-field="{{ $tabFieldKey }}" data-tab="{{ $tabKey }}" data-tabs-field="{{ $fieldKey }}">
                                                                Delete Image
                                                            </button>
                                                        </div>
                                                        <input type="hidden" name="old_{{ $tabKey }}_{{ $tabFieldKey }}"
                                                            value="{{ $additionalFields[$fieldKey][$tabKey][$tabFieldKey] }}">
                                                    @endif
                                                    <input type="file" class="form-control"
                                                        name="fields[{{ $fieldKey }}][{{ $tabKey }}][{{ $tabFieldKey }}]"
                                                        accept="image/*">
                                                @break

                                                @case('repeater')

                                                    <div class="repeater-container" data-field="{{ $tabFieldKey }}">
                                                        <div class="repeater-items">
                                                            @if (isset($additionalFields[$fieldKey][$tabKey][$tabFieldKey]) &&
                                                                    is_array($additionalFields[$fieldKey][$tabKey][$tabFieldKey]))
                                                                @foreach ($additionalFields[$fieldKey][$tabKey][$tabFieldKey] as $index => $item)
                                                                    <div class="repeater-item card mb-3">
                                                                        <div class="card-body">
                                                                            <div class="d-flex justify-content-end mb-2">
                                                                                <button type="button"
                                                                                    class="btn btn-danger btn-sm remove-repeater-item" onclick="removeRepeaterItem(this)">
                                                                                    <i class="fas fa-trash"></i> Remove
                                                                                </button>
                                                                            </div>
                                                                            @foreach ($tabField['fields'] as $repeaterFieldKey => $repeaterField)
                                                                                <div class="mb-3">
                                                                                    <label
                                                                                        class="form-label">{{ $repeaterField['label'] }}</label>
                                                                                    @switch($repeaterField['type'])
                                                                                        @case('text')
                                                                                            <input type="text" class="form-control"
                                                                                                name="fields[{{ $fieldKey }}][{{ $tabKey }}][{{ $tabFieldKey }}][{{ $index }}][{{ $repeaterFieldKey }}]"
                                                                                                value="{{ getSafeValue($item[$repeaterFieldKey] ?? '') }}"
                                                                                                {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                                                                placeholder="{{ $repeaterField['placeholder'] ?? '' }}">
                                                                                        @break

                                                                                        @case('email')
                                                                                            <input type="email" class="form-control"
                                                                                                name="fields[{{ $fieldKey }}][{{ $tabKey }}][{{ $tabFieldKey }}][{{ $index }}][{{ $repeaterFieldKey }}]"
                                                                                                value="{{ getSafeValue($item[$repeaterFieldKey] ?? '') }}"
                                                                                                {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                                                                placeholder="{{ $repeaterField['placeholder'] ?? '' }}">
                                                                                        @break

                                                                                        @case('textarea')
                                                                                            <textarea class="editor form-control" name="fields[{{ $fieldKey }}][{{ $tabKey }}][{{ $tabFieldKey }}][{{ $index }}][{{ $repeaterFieldKey }}]"
                                                                                                {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                                                                placeholder="{{ $repeaterField['placeholder'] ?? '' }}"></textarea>
                                                                                        @break

                                                                                        @case('description')
                                                                                            <textarea class="editor form-control" name="fields[{{ $fieldKey }}][{{ $tabKey }}][{{ $tabFieldKey }}][{{ $index }}][{{ $repeaterFieldKey }}]"
                                                                                                {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                                                                placeholder="{{ $repeaterField['placeholder'] ?? '' }}"></textarea>
                                                                                        @break

                                                                                        @case('image')
                                                                                            @if (isset($item[$repeaterFieldKey]))
                                                                                                <div class="mb-2">
                                                                                                    <img src="{{ Storage::url($item[$repeaterFieldKey]) }}"
                                                                                                        alt="Current Image"
                                                                                                        class="img-thumbnail"
                                                                                                        style="max-height: 200px;">
                                                                                                    <button type="button" class="btn btn-danger btn-sm delete-image" data-field="{{ $repeaterFieldKey }}" data-index="{{ $index }}" data-repeater-field="{{ $fieldKey }}">
                                                                                                        Delete Image
                                                                                                    </button>
                                                                                                </div>
                                                                                                <input type="hidden"
                                                                                                    name="old_{{ $repeaterFieldKey }}_{{ $index }}"
                                                                                                    value="{{ $item[$repeaterFieldKey] }}">
                                                                                            @endif
                                                                                            <input type="file" class="form-control"
                                                                                                name="fields[{{ $fieldKey }}][{{ $tabKey }}][{{ $tabFieldKey }}][{{ $index }}][{{ $repeaterFieldKey }}]"
                                                                                                accept="image/*">
                                                                                        @break

                                                                                        @case('select')
                                                                                            <select class="form-control select2"
                                                                                                    name="fields[{{ $fieldKey }}][{{ $tabKey }}][{{ $tabFieldKey }}][{{ $index }}][{{ $repeaterFieldKey }}]{{ isset($repeaterField['multiple']) && $repeaterField['multiple'] ? '[]' : '' }}"
                                                                                                    {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                                                                    {{ isset($repeaterField['multiple']) && $repeaterField['multiple'] ? 'multiple' : '' }}
                                                                                                    data-placeholder="{{ $repeaterField['placeholder'] ?? (isset($repeaterField['multiple']) ? 'Select options...' : 'Select an option...') }}">
                                                                                                @if(isset($repeaterField['placeholder']))
                                                                                                    <option value=""></option>
                                                                                                @endif
                                                                                                @foreach($repeaterField['options'] as $value => $label)
                                                                                                    <option value="{{ $value }}"
                                                                                                        {{ (isset($item[$repeaterFieldKey]) && (
                                                                                                            (is_array($item[$repeaterFieldKey]) && in_array($value, $item[$repeaterFieldKey])) ||
                                                                                                            (!is_array($item[$repeaterFieldKey]) && $item[$repeaterFieldKey] == $value)
                                                                                                        )) ? 'selected' : '' }}>
                                                                                                        {{ $label }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        @break

                                                                                        @default
                                                                                            <input type="{{ $repeaterField['type'] }}"
                                                                                                class="form-control"
                                                                                                name="fields[{{ $fieldKey }}][{{ $tabKey }}][{{ $tabFieldKey }}][{{ $index }}][{{ $repeaterFieldKey }}]"
                                                                                                value="{{ getSafeValue($item[$repeaterFieldKey] ?? '') }}"
                                                                                                {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}>
                                                                                    @endswitch
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>

                                                        <template class="repeater-template">
                                                            <div class="repeater-item card mb-3">
                                                                <div class="card-body">
                                                                    <div class="d-flex justify-content-end mb-2">
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-sm remove-repeater-item" onclick="removeRepeaterItem(this)">
                                                                            <i class="fas fa-trash"></i> Remove
                                                                        </button>
                                                                    </div>
                                                                    @foreach ($tabField['fields'] as $repeaterFieldKey => $repeaterField)
                                                                        <div class="mb-3">
                                                                            <label
                                                                                class="form-label">{{ $repeaterField['label'] }}</label>
                                                                            @switch($repeaterField['type'])
                                                                                @case('text')
                                                                                    <input type="text" class="form-control"
                                                                                        name="fields[{{ $fieldKey }}][{{ $tabKey }}][{{ $tabFieldKey }}][__INDEX__][{{ $repeaterFieldKey }}]"
                                                                                        {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                                                        placeholder="{{ $repeaterField['placeholder'] ?? '' }}">
                                                                                @break

                                                                                @case('email')
                                                                                    <input type="email" class="form-control"
                                                                                        name="fields[{{ $fieldKey }}][{{ $tabKey }}][{{ $tabFieldKey }}][__INDEX__][{{ $repeaterFieldKey }}]"
                                                                                        value="{{ getSafeValue($item[$repeaterFieldKey] ?? '') }}"
                                                                                        {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                                                        placeholder="{{ $repeaterField['placeholder'] ?? '' }}">
                                                                                @break

                                                                                @case('textarea')
                                                                                    <textarea class="editor form-control" name="fields[{{ $fieldKey }}][{{ $tabKey }}][{{ $tabFieldKey }}][__INDEX__][{{ $repeaterFieldKey }}]"
                                                                                        {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                                                        placeholder="{{ $repeaterField['placeholder'] ?? '' }}"></textarea>
                                                                                @break

                                                                                @case('image')
                                                                                    <input type="file" class="form-control"
                                                                                        name="fields[{{ $fieldKey }}][{{ $tabKey }}][{{ $tabFieldKey }}][__INDEX__][{{ $repeaterFieldKey }}]"
                                                                                        accept="image/*">
                                                                                @break

                                                                                @case('photo')
                                                                                    <input type="file" class="form-control"
                                                                                        name="fields[{{ $fieldKey }}][{{ $tabKey }}][{{ $tabFieldKey }}][__INDEX__][{{ $repeaterFieldKey }}]"
                                                                                        accept="image/*">
                                                                                @break

                                                                                @case('mobile_image')
                                                                                    <div class="mobile-image-upload" id="mobile-upload-{{ $fieldKey }}-__INDEX__-{{ $repeaterFieldKey }}">
                                                                                        <div class="mb-2">
                                                                                            <div class="d-flex align-items-center">
                                                                                                <input type="file" class="form-control mobile-image-input"
                                                                                                    id="input-{{ $fieldKey }}-__INDEX__-{{ $repeaterFieldKey }}" accept="image/*"
                                                                                                    {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                                                                    data-field="{{ $fieldKey }}-__INDEX__-{{ $repeaterFieldKey }}"
                                                                                                    onchange="handleMobileImageSelect(this)">
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="compression-options mb-2 d-none">
                                                                                            <div class="card p-3">
                                                                                                <div class="mb-2">
                                                                                                    <label class="form-label">Image Quality</label>
                                                                                                    <input type="range" class="form-range quality-slider" min="10" max="100" value="70"
                                                                                                        id="quality-{{ $fieldKey }}-__INDEX__-{{ $repeaterFieldKey }}" data-field="{{ $fieldKey }}-__INDEX__-{{ $repeaterFieldKey }}">
                                                                                                    <div class="d-flex justify-content-between">
                                                                                                        <small>Lower (Smaller File)</small>
                                                                                                        <small class="quality-value">70%</small>
                                                                                                        <small>Higher (Better Quality)</small>
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="mb-2">
                                                                                                    <label class="form-label">Max Width</label>
                                                                                                    <select class="form-select max-width" id="max-width-{{ $fieldKey }}-__INDEX__-{{ $repeaterFieldKey }}" data-field="{{ $fieldKey }}-__INDEX__-{{ $repeaterFieldKey }}">
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
                                                                                                        onclick="cancelCompression('{{ $fieldKey }}-__INDEX__-{{ $repeaterFieldKey }}')">Cancel</button>
                                                                                                    <button type="button" class="btn btn-primary btn-sm apply-compression"
                                                                                                        onclick="applyCompression('{{ $fieldKey }}-__INDEX__-{{ $repeaterFieldKey }}')">Apply & Upload</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <input type="hidden" name="fields[{{ $fieldKey }}][__INDEX__][{{ $repeaterFieldKey }}]" id="compressed-{{ $fieldKey }}-__INDEX__-{{ $repeaterFieldKey }}" class="compressed-image-data">
                                                                                    </div>
                                                                                @break

                                                                                @case('select')
                                                                                    <select class="form-control select2"
                                                                                            name="fields[{{ $fieldKey }}][__INDEX__][{{ $repeaterFieldKey }}]{{ isset($repeaterField['multiple']) && $repeaterField['multiple'] ? '[]' : '' }}"
                                                                                            {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}
                                                                                            {{ isset($repeaterField['multiple']) && $repeaterField['multiple'] ? 'multiple' : '' }}
                                                                                            data-placeholder="{{ $repeaterField['placeholder'] ?? (isset($repeaterField['multiple']) ? 'Select options...' : 'Select an option...') }}">
                                                                                        @if(isset($repeaterField['placeholder']))
                                                                                            <option value=""></option>
                                                                                        @endif
                                                                                        @foreach($repeaterField['options'] as $value => $label)
                                                                                            <option value="{{ $value }}">{{ $label }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                @break

                                                                                @default
                                                                                    <input type="{{ $repeaterField['type'] }}"
                                                                                        class="form-control"
                                                                                        name="fields[{{ $fieldKey }}][__INDEX__][{{ $repeaterFieldKey }}]"
                                                                                        {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}>
                                                                            @endswitch
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </template>

                                                        <button type="button" class="btn btn-primary add-repeater-item mt-2">
                                                            Add {{ $tabField['label'] }}
                                                        </button>
                                                    </div>
                                                @break

                                                @default
                                                    <input type="{{ $tabField['type'] }}" class="form-control"
                                                        name="fields[{{ $fieldKey }}][{{ $tabKey }}][{{ $tabFieldKey }}]"
                                                        value="{{ getSafeValue($additionalFields[$fieldKey][$tabKey][$tabFieldKey] ?? ($tabField['default'] ?? '')) }}"
                                                        {{ isset($tabField['required']) && $tabField['required'] ? 'required' : '' }}>
                                            @endswitch
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                @break

                @case('select')
                    @if(isset($field['multiple']) && $field['multiple'])
                        <select class="form-control select2-tagging"
                                name="fields[{{ $fieldKey }}][]"
                                multiple
                                {{ isset($field['required']) && $field['required'] ? 'required' : '' }}
                                data-placeholder="{{ $field['placeholder'] ?? 'Enter keywords...' }}">
                            @if(isset($additionalFields[$fieldKey]) && is_array($additionalFields[$fieldKey]))
                                @foreach($additionalFields[$fieldKey] as $value)
                                    <option value="{{ $value }}" selected>{{ $value }}</option>
                                @endforeach
                            @endif
                        </select>
                    @else
                        <select class="form-control select2"
                                name="fields[{{ $fieldKey }}]"
                                {{ isset($field['required']) && $field['required'] ? 'required' : '' }}
                                data-placeholder="{{ $field['placeholder'] ?? 'Select an option...' }}">
                            @if(isset($field['placeholder']))
                                <option value=""></option>
                            @endif
                            @foreach($field['options'] as $value => $label)
                                <option value="{{ $value }}"
                                    {{ (isset($additionalFields[$fieldKey]) && $additionalFields[$fieldKey] == $value) || (isset($field['default']) && $field['default'] == $value) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                @break

                @default
                    <input type="text" class="form-control @error($fieldKey) is-invalid @enderror"
                        id="{{ $fieldKey }}" name="fields[{{ $fieldKey }}]"
                        value="{{ getSafeValue($additionalFields[$fieldKey] ?? ($field['default'] ?? '')) }}"
                        {{ isset($field['required']) && $field['required'] ? 'required' : '' }}>
            @endswitch

            @error($fieldKey)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @endforeach
@else
    <div class="alert alert-warning">
        No fields configuration found for this section.
    </div>
@endif

<script>
    // Simplified and improved repeater functionality
    document.addEventListener('DOMContentLoaded', function() {
        initializeRepeaterHandlers();
        initializeSelect2();
    });

    function initializeSelect2() {
        if (typeof jQuery !== 'undefined') {
            // First, destroy any existing select2 instances to prevent duplicates
            jQuery('select.select2-hidden-accessible').select2('destroy');

            // Then initialize only non-max-width selects
            jQuery('select').each(function() {
                // Skip max-width selects entirely
                if (jQuery(this).hasClass('max-width')) {
                    return;
                }

                const $select = jQuery(this);
                const isMultiple = $select.attr('multiple') !== undefined;

                $select.select2({
                    placeholder: isMultiple ? "Select options..." : "Select an option...",
                    allowClear: true,
                    width: '100%'
                });
            });
        }
    }

    function initializeRepeaterHandlers() {
        // Prevent multiple initializations which can lead to duplicate event listeners
        if (window.repeaterHandlersInitialized) {
            console.log('Repeater handlers already initialized, skipping...');
            return;
        }
        window.repeaterHandlersInitialized = true;
        console.log('Initializing repeater handlers...');

        // Handle add repeater item
        document.addEventListener('click', function(event) {
            const addButton = event.target.closest('.add-repeater-item');
            if (!addButton) return;

            event.preventDefault();
            console.log('Add button clicked');

            const container = addButton.closest('.repeater-container');
            if (!container) return;

            const template = container.querySelector('.repeater-template');
            const itemsContainer = container.querySelector('.repeater-items');
            if (!template || !itemsContainer) return;

            const itemCount = itemsContainer.querySelectorAll('.repeater-item').length;
            console.log('Current item count:', itemCount);

            const newItem = document.importNode(template.content, true);

            // Replace __INDEX__ placeholders with actual index
            newItem.querySelectorAll('[name*="__INDEX__"]').forEach(input => {
                if (input.name) {
                    input.name = input.name.replace(/__INDEX__/g, itemCount);
                }
            });

            const repeaterItem = newItem.querySelector('.repeater-item');
            if (repeaterItem) {
                repeaterItem.setAttribute('data-index', itemCount);
                itemsContainer.appendChild(newItem);
                console.log('New item added with index:', itemCount);

                // Reinitialize select2 for new elements
                if (typeof jQuery !== 'undefined') {
                    jQuery(repeaterItem).find('select').each(function() {
                        if (!jQuery(this).hasClass('select2-hidden-accessible') && !jQuery(this).hasClass('max-width')) {
                            jQuery(this).select2({
                                placeholder: "Select an option...",
                                allowClear: true,
                                width: '100%'
                            });
                        }
                    });
                }

                // Initialize mobile image uploads for new repeater items
                const mobileImageInputs = repeaterItem.querySelectorAll('.mobile-image-input');
                console.log('Found', mobileImageInputs.length, 'mobile image inputs in new repeater item');

                mobileImageInputs.forEach(input => {
                    // Remove existing event listeners by cloning
                    const newInput = input.cloneNode(true);
                    input.parentNode.replaceChild(newInput, input);

                    // Replace __INDEX__ placeholder in IDs and data attributes
                    const itemIndex = repeaterItem.getAttribute('data-index');
                    newInput.id = newInput.id.replace('__INDEX__', itemIndex);
                    newInput.setAttribute('data-field', newInput.getAttribute('data-field').replace('__INDEX__', itemIndex));

                    // Attach new event listener
                    newInput.addEventListener('change', function() {
                        handleMobileImageSelect(this);
                    });

                    console.log('Initialized mobile image input:', newInput.id);

                    // Find and update IDs and attributes for related elements
                    const uploadContainer = newInput.closest('.mobile-image-upload');
                    if (uploadContainer) {
                        uploadContainer.id = uploadContainer.id.replace('__INDEX__', itemIndex);

                        // Update quality slider
                        const qualitySlider = uploadContainer.querySelector('.quality-slider');
                        if (qualitySlider) {
                            qualitySlider.id = qualitySlider.id.replace('__INDEX__', itemIndex);
                            qualitySlider.setAttribute('data-field', qualitySlider.getAttribute('data-field').replace('__INDEX__', itemIndex));
                        }

                        // Update max width select
                        const maxWidthSelect = uploadContainer.querySelector('.max-width');
                        if (maxWidthSelect) {
                            maxWidthSelect.id = maxWidthSelect.id.replace('__INDEX__', itemIndex);
                            maxWidthSelect.setAttribute('data-field', maxWidthSelect.getAttribute('data-field').replace('__INDEX__', itemIndex));
                        }

                        // Update compressed hidden input
                        const hiddenInput = uploadContainer.querySelector('.compressed-image-data');
                        if (hiddenInput) {
                            hiddenInput.id = hiddenInput.id.replace('__INDEX__', itemIndex);
                            hiddenInput.name = hiddenInput.name.replace('__INDEX__', itemIndex);
                        }
                    }
                });

                // Attach event handlers to compression buttons
                const cancelButtons = repeaterItem.querySelectorAll('.cancel-compression');
                const applyButtons = repeaterItem.querySelectorAll('.apply-compression');

                cancelButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const fieldKey = this.getAttribute('data-field-key');
                        const repeaterKey = this.getAttribute('data-field-repeater-key');
                        const itemIndex = repeaterItem.getAttribute('data-index');
                        cancelCompression(`${fieldKey}-${itemIndex}-${repeaterKey}`);
                    });
                });

                applyButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const fieldKey = this.getAttribute('data-field-key');
                        const repeaterKey = this.getAttribute('data-field-repeater-key');
                        const itemIndex = repeaterItem.getAttribute('data-index');
                        applyCompression(`${fieldKey}-${itemIndex}-${repeaterKey}`);
                    });
                });

                reindexRepeaterItems(container);
            }
        });

        // Handle remove repeater item
        document.addEventListener('click', function(event) {
            const removeButton = event.target.closest('.remove-repeater-item');
            if (!removeButton) return;

            event.preventDefault();
            event.stopPropagation();
            console.log('Remove button clicked');

            const item = removeButton.closest('.repeater-item');
            if (!item) {
                console.error('Could not find repeater item');
                return;
            }

            const container = item.closest('.repeater-container');
            if (!container) {
                console.error('Could not find repeater container');
                return;
            }

            const fieldKey = container.getAttribute('data-field');
            if (!fieldKey) {
                console.error('Could not find field key');
                return;
            }

            console.log('Removing item from field:', fieldKey);

            const currentItems = container.querySelectorAll('.repeater-item');
            console.log('Current items count:', currentItems.length);

            // If this is the last item, allow removal but show a confirmation
            if (currentItems.length <= 1) {
                console.log('Last item - removing completely');
                // You can add a confirmation dialog here if needed
                // if (!confirm('This will remove the last item. Are you sure?')) {
                //     return;
                // }
            }

            // Add visual feedback before removal
            item.style.transition = 'opacity 0.2s, transform 0.2s';
            item.style.opacity = '0';
            item.style.transform = 'translateX(-20px)';

            // Remove the item after animation
            setTimeout(() => {
                item.remove();
                console.log('Item removed, reindexing...');
                reindexRepeaterItems(container);
            }, 200);
        });

        // Alternative event handling for remove buttons (backup)
        document.addEventListener('mousedown', function(event) {
            const removeButton = event.target.closest('.remove-repeater-item');
            if (!removeButton) return;

            event.preventDefault();
            event.stopPropagation();
            console.log('Remove button clicked (mousedown)');

            const item = removeButton.closest('.repeater-item');
            if (!item) return;

            const container = item.closest('.repeater-container');
            if (!container) return;

            const fieldKey = container.getAttribute('data-field');
            if (!fieldKey) return;

            const currentItems = container.querySelectorAll('.repeater-item');

            if (currentItems.length <= 1) {
                console.log('Last item - removing completely (mousedown)');
                // You can add a confirmation dialog here if needed
                // if (!confirm('This will remove the last item. Are you sure?')) {
                //     return;
                // }
            }

            item.style.transition = 'opacity 0.2s, transform 0.2s';
            item.style.opacity = '0';
            item.style.transform = 'translateX(-20px)';

            setTimeout(() => {
                item.remove();
                reindexRepeaterItems(container);
            }, 200);
        });

        // Initialize existing containers
        const containers = document.querySelectorAll('.repeater-container');
        console.log('Found', containers.length, 'repeater containers');
        containers.forEach(container => {
            reindexRepeaterItems(container);
        });
    }

    function reindexRepeaterItems(container) {
        if (!container) return;

        const fieldKey = container.getAttribute('data-field');
        if (!fieldKey) return;

        const items = Array.from(container.querySelectorAll('.repeater-item'));
        const stateTracker = container.querySelector('.repeater-state-tracker');
        const emptyTracker = container.querySelector('.repeater-empty-tracker');

        // Remove any existing delete markers for this field
        container.querySelectorAll(`input[name^="deleted_${fieldKey}"]`).forEach(el => el.remove());

        items.forEach((item, newIndex) => {
            // Update the data-index attribute
            item.setAttribute('data-index', newIndex);

            // Update all form elements within this item
            item.querySelectorAll('input, select, textarea').forEach(input => {
                if (!input.name) return;

                // Handle different name patterns
                const namePatterns = [
                    // Pattern for main repeater fields: fields[fieldKey][index][subField]
                    new RegExp(`fields\\[${fieldKey}\\]\\[\\d+\\]`),
                    // Pattern for tabs repeater fields: fields[tabsField][tab][fieldKey][index][subField]
                    new RegExp(`fields\\[([^\\]]+)\\]\\[([^\\]]+)\\]\\[${fieldKey}\\]\\[\\d+\\]`),
                    // Pattern for old_* hidden fields
                    new RegExp(`old_([^_]+)_\\d+`),
                ];

                let updated = false;

                // Try each pattern
                namePatterns.forEach((pattern, patternIndex) => {
                    if (pattern.test(input.name)) {
                        if (patternIndex === 0) {
                            // Main repeater pattern
                            input.name = input.name.replace(
                                new RegExp(`\\[${fieldKey}\\]\\[\\d+\\]`),
                                `[${fieldKey}][${newIndex}]`
                            );
                        } else if (patternIndex === 1) {
                            // Tabs repeater pattern
                            const match = input.name.match(pattern);
                            if (match) {
                                const tabsField = match[1];
                                const tab = match[2];
                                input.name = input.name.replace(
                                    new RegExp(`\\[${tabsField}\\]\\[${tab}\\]\\[${fieldKey}\\]\\[\\d+\\]`),
                                    `[${tabsField}][${tab}][${fieldKey}][${newIndex}]`
                                );
                            }
                        } else if (patternIndex === 2) {
                            // Old_* pattern
                            const match = input.name.match(pattern);
                            if (match) {
                                const fieldName = match[1];
                                input.name = input.name.replace(
                                    new RegExp(`old_${fieldName}_\\d+`),
                                    `old_${fieldName}_${newIndex}`
                                );
                            }
                        }
                        updated = true;
                    }
                });

                // Update data-index attributes
                if (input.hasAttribute('data-index')) {
                    input.setAttribute('data-index', newIndex);
                }
            });
        });

        // Update state tracker if it exists
        if (stateTracker) {
            stateTracker.value = items.length;
        }

        // Update empty tracker - if no items, ensure the hidden input is present
        if (emptyTracker) {
            if (items.length === 0) {
                // If no items, ensure the hidden input is present to signal empty field
                emptyTracker.name = `fields[${fieldKey}]`;
                emptyTracker.value = '';
            } else {
                // If there are items, remove the hidden input so actual data is sent
                emptyTracker.remove();
            }
        }

        return items.length;
    }

    // Handle image deletion
    document.addEventListener('click', function(event) {
        const deleteButton = event.target.closest('.delete-image');
        if (!deleteButton) return;

        event.preventDefault();

        const fieldKey = deleteButton.getAttribute('data-field');
        const index = deleteButton.getAttribute('data-index');
        const repeaterField = deleteButton.getAttribute('data-repeater-field');
        const tab = deleteButton.getAttribute('data-tab');
        const tabsField = deleteButton.getAttribute('data-tabs-field');
        const pageId = '{{ $pageId }}';
        const sectionKey = '{{ $sectionKey }}';

        const url = `{{ route('admin.sections.delete_image', ['pageId' => '__PAGE_ID__', 'sectionKey' => '__SECTION_KEY__']) }}`
            .replace('__PAGE_ID__', pageId)
            .replace('__SECTION_KEY__', sectionKey);

        const requestBody = { field_key: fieldKey };
        if (index !== null) requestBody.index = index;
        if (repeaterField !== null) requestBody.repeater_field = repeaterField;
        if (tab !== null && tabsField !== null) {
            requestBody.tab = tab;
            requestBody.tabs_field = tabsField;
        }

        // Show confirmation dialog
        if (!confirm('Are you sure you want to delete this image? Click OK and then refresh the page after deletion completes.')) {
            return;
        }

        fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(requestBody)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Instead of removing just the container, insert a placeholder or message
                let hiddenInputName = '';
                if (index !== null && repeaterField !== null) {
                    // For repeater fields, we need to create a hidden field that preserves null explicitly
                    hiddenInputName = `fields[${repeaterField}][${index}][${fieldKey}]`;

                    // Create or update hidden input to store null value explicitly
                    let hiddenInput = document.querySelector(`input[name="${hiddenInputName}"]`);
                    if (!hiddenInput) {
                        hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = hiddenInputName;
                        const container = deleteButton.closest('.mb-3');
                        if (container) {
                            container.appendChild(hiddenInput);
                        }
                    }
                    // Set to explicit null by making it empty
                    hiddenInput.value = '';
                } else if (tab !== null && tabsField !== null) {
                    hiddenInputName = `old_${tab}_${fieldKey}`;
                    const hiddenInput = document.querySelector(`input[name="${hiddenInputName}"]`);
                    if (hiddenInput) {
                        hiddenInput.value = '';
                    }
                } else {
                    hiddenInputName = `old_${fieldKey}`;
                    const hiddenInput = document.querySelector(`input[name="${hiddenInputName}"]`);
                    if (hiddenInput) {
                        hiddenInput.value = '';
                    }
                }

                // Remove the image container
                const imageContainer = deleteButton.closest('.mb-2');
                if (imageContainer) {
                    imageContainer.remove();
                }

                // Show success message


            } else {
                console.error('Delete image failed:', data.message || 'Unknown error');
                alert('Failed to delete image: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error deleting image:', error);
            alert('Error deleting image. Please try again.');
        });
    });

    // Global reinitialize function
    window.reinitializeHandlers = function() {
        initializeSelect2();
        initializeRepeaterHandlers();
    };

    // Inline onclick handler for remove buttons
    window.removeRepeaterItem = function(button) {
        console.log('Inline remove function called');

        const item = button.closest('.repeater-item');
        if (!item) {
            console.error('Could not find repeater item');
            return false;
        }

        const container = item.closest('.repeater-container');
        if (!container) {
            console.error('Could not find repeater container');
            return false;
        }

        const fieldKey = container.getAttribute('data-field');
        if (!fieldKey) {
            console.error('Could not find field key');
            return false;
        }

        console.log('Removing item from field:', fieldKey);

        const currentItems = container.querySelectorAll('.repeater-item');
        console.log('Current items count:', currentItems.length);

        // If this is the last item, allow removal but show a confirmation
        if (currentItems.length <= 1) {
            console.log('Last item - removing completely');
            // You can add a confirmation dialog here if needed
            // if (!confirm('This will remove the last item. Are you sure?')) {
            //     return;
            // }
        }

        // Add visual feedback before removal
        item.style.transition = 'opacity 0.2s, transform 0.2s';
        item.style.opacity = '0';
        item.style.transform = 'translateX(-20px)';

        // Remove the item after animation
        setTimeout(() => {
            item.remove();
            console.log('Item removed, reindexing...');
            reindexRepeaterItems(container);
        }, 200);

        return false; // Prevent default form submission
    };

    // Test function for debugging
    window.testRepeaterRemove = function() {
        console.log('Testing repeater remove functionality...');
        const containers = document.querySelectorAll('.repeater-container');
        console.log('Found containers:', containers.length);

        containers.forEach((container, index) => {
            const fieldKey = container.getAttribute('data-field');
            const items = container.querySelectorAll('.repeater-item');
            const removeButtons = container.querySelectorAll('.remove-repeater-item');

            console.log(`Container ${index + 1}:`, {
                fieldKey: fieldKey,
                itemsCount: items.length,
                removeButtonsCount: removeButtons.length
            });

            // Test clicking the first remove button
            if (removeButtons.length > 0) {
                console.log(`Testing remove button for container ${index + 1}`);
                removeButtons[0].click();
            }
        });
    };

    // Auto-initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing...');
            initializeRepeaterHandlers();
            // Add slight delay to ensure DOM is fully processed
            setTimeout(function() {
                initializeSelect2();
            }, 100);
        });
    } else {
        console.log('DOM already loaded, initializing immediately...');
        initializeRepeaterHandlers();
        // Add slight delay to ensure DOM is fully processed
        setTimeout(function() {
            initializeSelect2();
        }, 100);
    }
</script>

{{-- Fix for mobile image upload select dropdowns --}}
<style>
    /* Fix for select dropdowns in mobile image upload */
    .form-select.max-width {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        width: 100% !important;
        height: auto !important;
        padding: .375rem .75rem !important;
        font-size: 1rem !important;
        font-weight: 400 !important;
        line-height: 1.5 !important;
        appearance: auto !important;
        -webkit-appearance: menulist !important;
        -moz-appearance: menulist !important;
    }
    .form-select.max-width option {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    /* Hide any select2 containers that might be created for max-width selects */
    .select2-container--default[aria-hidden="true"] + .form-select.max-width {
        display: block !important;
        visibility: visible !important;
    }
</style>

{{-- Mobile Image Upload Compression Script --}}
<script>
    // Global object to store original files
    const originalFiles = {};

    // Initialize all mobile image fields when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing mobile image fields');

        // Initialize all mobile image inputs
        const mobileImageInputs = document.querySelectorAll('.mobile-image-input');
        console.log('Found', mobileImageInputs.length, 'mobile image inputs');

        mobileImageInputs.forEach(input => {
            // Make sure each input has a unique ID and data-field attribute
            const fieldIdentifier = input.getAttribute('data-field');
            console.log('Initializing mobile image field:', fieldIdentifier);

            // Make sure the corresponding elements exist
            const container = document.getElementById('mobile-upload-' + fieldIdentifier);
            const hiddenInput = document.getElementById('compressed-' + fieldIdentifier);

            if (container && hiddenInput) {
                console.log('Field elements found for:', fieldIdentifier);
            } else {
                console.error('Missing elements for field:', fieldIdentifier);
            }
        });
    });

    // Handle mobile image file selection
    function handleMobileImageSelect(input) {
        const inputId = input.id;
        const file = input.files[0];

        if (!file) return;

        // Parse the input ID to determine if it's a repeater field or standalone field
        // Format: input-fieldKey or input-fieldKey-index-repeaterFieldKey
        const parts = inputId.split('-');
        let fieldIdentifier;

        if (parts.length >= 4) {
            // This is a repeater field
            // fieldKey-index-repeaterFieldKey
            fieldIdentifier = parts.slice(1).join('-');
            console.log('Repeater field detected:', fieldIdentifier);
        } else {
            // This is a standalone field
            fieldIdentifier = parts[1];
            console.log('Standalone field detected:', fieldIdentifier);
        }

        // Store original file for later use - use a unique key for each field
        // This ensures each slider item has its own file storage
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

        // Update quality display when slider changes
        const qualitySlider = document.getElementById('quality-' + fieldIdentifier);
        const qualityValue = qualitySlider.parentElement.querySelector('.quality-value');

        // Remove existing event listeners to prevent duplicates
        const newQualitySlider = qualitySlider.cloneNode(true);
        qualitySlider.parentNode.replaceChild(newQualitySlider, qualitySlider);

        // Add event listener to the new element
        newQualitySlider.addEventListener('input', function() {
            qualityValue.textContent = this.value + '%';
            previewCompressedImage(fieldIdentifier);
        });

        // Update preview when width changes
        const widthSelect = document.getElementById('max-width-' + fieldIdentifier);

        // Ensure select is properly initialized and visible
        if (widthSelect) {
            // Clone the select element to remove any existing event listeners
            const newWidthSelect = widthSelect.cloneNode(true);
            widthSelect.parentNode.replaceChild(newWidthSelect, widthSelect);

            // Force show the select element
            newWidthSelect.style.display = 'block';
            newWidthSelect.style.visibility = 'visible';
            newWidthSelect.style.opacity = '1';
            newWidthSelect.style.position = 'static';

            // Make sure there's only one select element showing
            const parentDiv = newWidthSelect.closest('.mb-2');
            if (parentDiv) {
                // Remove any duplicate selects that might have been created
                const extraSelects = parentDiv.querySelectorAll('.max-width:not(#max-width-' + fieldIdentifier + ')');
                extraSelects.forEach(el => el.remove());
            }

            // Simple change event listener without custom dropdown behavior
            newWidthSelect.addEventListener('change', function() {
                console.log('Width changed to:', this.value);
                previewCompressedImage(fieldIdentifier);
            });
        } else {
            console.error('Width select not found for field:', fieldIdentifier);
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

        // Check if this is a repeater field
        const isRepeater = fileInput.getAttribute('data-repeater') === 'true';
        const repeaterIndex = isRepeater ? fileInput.getAttribute('data-repeater-index') : null;
        const repeaterKey = isRepeater ? fileInput.getAttribute('data-repeater-key') : null;

        if (isRepeater) {
            console.log('Processing repeater field:', fieldIdentifier, 'Index:', repeaterIndex, 'Key:', repeaterKey);
        }

        // Get all the necessary elements for this specific field
        const container = document.getElementById('mobile-upload-' + fieldIdentifier);
        const qualitySlider = document.getElementById('quality-' + fieldIdentifier);
        const widthSelect = document.getElementById('max-width-' + fieldIdentifier);
        const hiddenInput = document.getElementById('compressed-' + fieldIdentifier);
        const options = container.querySelector('.compression-options');


        if (!container || !qualitySlider || !widthSelect || !hiddenInput || !fileInput || !options) {
            console.error('Missing elements for field:', fieldIdentifier);
            console.log('Container:', container);
            console.log('Quality slider:', qualitySlider);
            console.log('Width select:', widthSelect);
            console.log('Hidden input:', hiddenInput);
            console.log('File input:', fileInput);
            console.log('Options:', options);
            return;
        }

        // Get settings
        const quality = parseInt(qualitySlider.value);
        const maxWidth = parseInt(widthSelect.value);

        console.log('Compression settings for', fieldIdentifier, '- Quality:', quality, 'Max width:', maxWidth);

        // Show processing message
        options.innerHTML = '<div class="alert alert-info">Processing image... Please wait.</div>';

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
                const compressedDataUrl = canvas.toDataURL('image/jpeg', quality / 100);

                // Send to server for additional optimization
                fetch('{{ route("mobile.image.upload") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        image: compressedDataUrl,
                        quality: quality,
                        maxWidth: maxWidth
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Store the server-optimized image path in hidden input
                        hiddenInput.value = data.path;

                        // For repeater fields, make sure the name attribute is correctly set
                        if (isRepeater && repeaterIndex !== null && repeaterKey !== null) {
                            // Double check that the hidden input has the correct name attribute for the repeater field
                            const expectedName = `fields[${fieldIdentifier.split('-')[0]}][${repeaterIndex}][${repeaterKey}]`;
                            if (hiddenInput.name !== expectedName) {
                                console.log('Fixing hidden input name from', hiddenInput.name, 'to', expectedName);
                                hiddenInput.name = expectedName;
                            }

                            // Make the hidden input visible to debugging (temporary)
                            console.log('Hidden input value set to:', data.path);
                            console.log('Hidden input name:', hiddenInput.name);

                            // Ensure we don't have duplicate hidden inputs for the same field
                            const duplicateInputs = document.querySelectorAll(`input[name="${expectedName}"]`);
                            duplicateInputs.forEach(input => {
                                if (input.id !== hiddenInput.id) {
                                    console.log('Removing duplicate hidden input:', input.id);
                                    input.parentNode.removeChild(input);
                                }
                            });

                            // If the hidden input is not in the form, add it
                            const form = document.querySelector('form');
                            if (!hiddenInput.parentNode) {
                                console.log('Adding hidden input to form');
                                form.appendChild(hiddenInput);
                            }
                        }

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
                    console.error('Error uploading image:', error);
                    options.innerHTML = '<div class="alert alert-danger">Error uploading image. Please try again.</div>';
                    fileInput.disabled = false;
                });
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    function cancelCompression(fieldIdentifier) {
        const container = document.getElementById('mobile-upload-' + fieldIdentifier);
        const options = container.querySelector('.compression-options');
        const fileInput = document.getElementById('input-' + fieldIdentifier);
        const hiddenInput = document.getElementById('compressed-' + fieldIdentifier);

        // Reset everything
        options.classList.add('d-none');
        fileInput.value = '';
        hiddenInput.value = '';
        delete originalFiles[fieldIdentifier];
    }
</script>

{{-- Auto-switch to the correct tab when an invalid hidden input is detected --}}
<script>
(function () {
    // Use capture phase so we run before the browser shows its own message
    document.addEventListener('invalid', function (event) {
        const input = event.target;

        // Find enclosing tab pane, if any
        const pane = input.closest('.tab-pane');
        if (pane && !pane.classList.contains('show')) {
            // Locate the tab button that toggles this pane
            const tabTrigger = document.querySelector(`button[data-bs-target="#${pane.id}"]`);
            if (tabTrigger) {
                // Activate the tab so the field becomes visible / focusable
                const tabObj = bootstrap.Tab.getOrCreateInstance(tabTrigger);
                tabObj.show();

                // Small timeout to allow the pane to become visible before focusing
                setTimeout(() => input.focus(), 100);
            }
        }
    }, true); // capture
})();
</script>
