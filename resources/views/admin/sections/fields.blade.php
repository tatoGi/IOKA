@php
    // Get the section configuration from the page type
    $page = \App\Models\Page::find($pageId);
    $pageType = collect(Config::get('PageTypes'))->firstWhere('id', $page->type_id);
    $sectionConfig = $pageType['sections'][$sectionKey] ?? null;

    // Helper function to safely get value
    function getSafeValue($value)
    {
        if (is_array($value)) {
            return json_encode($value);
        }
        return $value;
    }

@endphp
@if ($sectionConfig && isset($sectionConfig['fields']))
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
                    <input type="file" class="form-control @error($fieldKey) is-invalid @enderror" id="{{ $fieldKey }}"
                        name="fields[{{ $fieldKey }}]" accept="image/*"
                        {{ isset($field['required']) && $field['required'] ? 'required' : '' }}>
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
                    <input type="file" class="form-control @error($fieldKey) is-invalid @enderror" id="{{ $fieldKey }}"
                        name="fields[{{ $fieldKey }}]" accept="image/*"
                        {{ isset($field['required']) && $field['required'] ? 'required' : '' }}>
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

                                                                                @case('select')
                                                                                    <select class="form-control select2"
                                                                                            name="fields[{{ $fieldKey }}][{{ $tabKey }}][{{ $tabFieldKey }}][__INDEX__][{{ $repeaterFieldKey }}]{{ isset($repeaterField['multiple']) && $repeaterField['multiple'] ? '[]' : '' }}"
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
                                                                                        name="fields[{{ $fieldKey }}][{{ $tabKey }}][{{ $tabFieldKey }}][__INDEX__][{{ $repeaterFieldKey }}]"
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
            jQuery('select').each(function() {
                if (!jQuery(this).hasClass('select2-hidden-accessible')) {
                    const $select = jQuery(this);
                    const isMultiple = $select.attr('multiple') !== undefined;

                    $select.select2({
                        placeholder: isMultiple ? "Select options..." : "Select an option...",
                        allowClear: true,
                        width: '100%'
                    });
                }
            });
        }
    }

    function initializeRepeaterHandlers() {
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
                        if (!jQuery(this).hasClass('select2-hidden-accessible')) {
                            jQuery(this).select2({
                                placeholder: "Select an option...",
                                allowClear: true,
                                width: '100%'
                            });
                        }
                    });
                }

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
                // Clear hidden input
                let hiddenInputName = '';
                if (index !== null && repeaterField !== null) {
                    hiddenInputName = `old_${fieldKey}_${index}`;
                } else if (tab !== null && tabsField !== null) {
                    hiddenInputName = `old_${tab}_${fieldKey}`;
                } else {
                    hiddenInputName = `old_${fieldKey}`;
                }

                const hiddenInput = document.querySelector(`input[name="${hiddenInputName}"]`);
                if (hiddenInput) {
                    hiddenInput.value = '';
                }

                // Remove the image container
                const imageContainer = deleteButton.closest('.mb-2');
                if (imageContainer) {
                    imageContainer.remove();
                }
            } else {
                console.error('Delete image failed:', data.message || 'Unknown error');
            }
        })
        .catch(error => {
            console.error('Error deleting image:', error);
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
            initializeSelect2();
        });
    } else {
        console.log('DOM already loaded, initializing immediately...');
        initializeRepeaterHandlers();
        initializeSelect2();
    }
</script>
