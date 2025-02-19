@php
    // Get the section configuration from the page type
    $page = \App\Models\Page::find($pageId);
    $pageType = collect(Config::get('PageTypes'))->firstWhere('id', $page->type_id);
    $sectionConfig = $pageType['sections'][$sectionKey] ?? null;

    // Helper function to safely get value
    function getSafeValue($value) {

        if (is_array($value)) {

            return json_encode($value);
        }
        return $value;
    }

@endphp

@if($sectionConfig && isset($sectionConfig['fields']))
    @foreach($sectionConfig['fields'] as $fieldKey => $field)
        <div class="mb-3">
            <label for="{{ $fieldKey }}" class="form-label">{{ $field['label'] }}</label>

            @switch($field['type'])
                @case('text')
                    <input type="text" class="form-control @error($fieldKey) is-invalid @enderror"
                        id="{{ $fieldKey }}" name="fields[{{ $fieldKey }}]"
                        value="{{ old("fields.$fieldKey", $additionalFields[$fieldKey] ?? $field['default'] ?? '') }}"
                        {{ isset($field['required']) && $field['required'] ? 'required' : '' }}>
                @break

                @case('textarea')
                    <textarea class="editor form-control @error($fieldKey) is-invalid @enderror"
                        id="{{ $fieldKey }}" name="fields[{{ $fieldKey }}]"
                        {{ isset($field['required']) && $field['required'] ? 'required' : '' }}
                    >{{ getSafeValue($additionalFields[$fieldKey] ?? $field['default'] ?? '') }}</textarea>
                @break

                @case('image')
                    @if(isset($additionalFields[$fieldKey]))
                        <div class="mb-2">
                            <img src="{{ Storage::url($additionalFields[$fieldKey]) }}"
                                alt="Current Image"
                                class="img-thumbnail"
                                style="max-height: 200px;">
                        </div>
                        <input type="hidden" name="old_{{ $fieldKey }}" value="{{ $additionalFields[$fieldKey] }}">
                    @endif
                    <input type="file" class="form-control @error($fieldKey) is-invalid @enderror"
                        id="{{ $fieldKey }}" name="fields[{{ $fieldKey }}]" accept="image/*"
                        {{ isset($field['required']) && $field['required'] ? 'required' : '' }}>
                @break

                @case('photo')
                    @if(isset($additionalFields[$fieldKey]))
                        <div class="mb-2">
                            <img src="{{ Storage::url($additionalFields[$fieldKey]) }}"
                                alt="Current Image"
                                class="img-thumbnail"
                                style="max-height: 200px;">
                        </div>
                        <input type="hidden" name="old_{{ $fieldKey }}" value="{{ $additionalFields[$fieldKey] }}">
                    @endif
                    <input type="file" class="form-control @error($fieldKey) is-invalid @enderror"
                        id="{{ $fieldKey }}" name="fields[{{ $fieldKey }}]" accept="image/*"
                        {{ isset($field['required']) && $field['required'] ? 'required' : '' }}>
                @break

                @case('repeater')
                    <div class="repeater-container" data-field="{{ $fieldKey }}">
                        <div class="repeater-items">
                            @if(isset($additionalFields[$fieldKey]) && is_array($additionalFields[$fieldKey]))
                                @foreach($additionalFields[$fieldKey] as $index => $item)
                                    <div class="repeater-item card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-end mb-2">
                                                <button type="button" class="btn btn-danger btn-sm remove-repeater-item">
                                                    <i class="fas fa-trash"></i> Remove
                                                </button>
                                            </div>
                                            @foreach($field['fields'] as $repeaterFieldKey => $repeaterField)
                                                <div class="mb-3">
                                                    <label class="form-label">{{ $repeaterField['label'] }}</label>
                                                    @switch($repeaterField['type'])
                                                        @case('image')
                                                            @if(isset($item[$repeaterFieldKey]))
                                                                <div class="mb-2">
                                                                    <img src="{{ Storage::url($item[$repeaterFieldKey]) }}"
                                                                        alt="Current Image"
                                                                        class="img-thumbnail"
                                                                        style="max-height: 200px;">
                                                                </div>
                                                                <input type="hidden"
                                                                    name="old_{{ $repeaterFieldKey }}_{{ $index }}"
                                                                    value="{{ $item[$repeaterFieldKey] }}">
                                                            @endif
                                                            <input type="file"
                                                                class="form-control"
                                                                name="fields[{{ $fieldKey }}][{{ $index }}][{{ $repeaterFieldKey }}]"
                                                                accept="image/*">
                                                        @break

                                                        @default
                                                            <input type="{{ $repeaterField['type'] }}"
                                                                class="form-control"
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
                                        <button type="button" class="btn btn-danger btn-sm remove-repeater-item">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </div>
                                    @foreach($field['fields'] as $repeaterFieldKey => $repeaterField)
                                        <div class="mb-3">
                                            <label class="form-label">{{ $repeaterField['label'] }}</label>
                                            @switch($repeaterField['type'])
                                                @case('image')
                                                    <input type="file"
                                                        class="form-control"
                                                        name="fields[{{ $fieldKey }}][__INDEX__][{{ $repeaterFieldKey }}]"
                                                        accept="image/*">
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
                            Add {{ $field['label'] }}
                        </button>
                    </div>
                @break

                @case('group')
                    <div class="card">
                        <div class="card-body">
                            @foreach($field['fields'] as $groupFieldKey => $groupField)
                                <div class="mb-3">
                                    <label class="form-label">{{ $groupField['label'] }}</label>
                                    @switch($groupField['type'])
                                        @case('textarea')
                                            <textarea class="editor form-control"
                                                name="fields[{{ $fieldKey }}][{{ $groupFieldKey }}]"
                                                {{ isset($groupField['required']) && $groupField['required'] ? 'required' : '' }}
                                            >{{ getSafeValue($additionalFields[$fieldKey][$groupFieldKey] ?? $groupField['value'] ?? '') }}</textarea>
                                        @break

                                        @default
                                            <input type="{{ $groupField['type'] }}"
                                                class="form-control"
                                                name="fields[{{ $fieldKey }}][{{ $groupFieldKey }}]"
                                                value="{{ getSafeValue($additionalFields[$fieldKey][$groupFieldKey] ?? $groupField['value'] ?? '') }}"
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
                            @foreach($field['tabs'] as $tabKey => $tab)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link @if($loop->first) active @endif"
                                        id="tab-{{ $tabKey }}" data-bs-toggle="tab"
                                        data-bs-target="#content-{{ $tabKey }}" type="button"
                                        role="tab">
                                        {{ $tab['label'] }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content mt-3">
                            @foreach($field['tabs'] as $tabKey => $tab)
                                <div class="tab-pane fade @if($loop->first) show active @endif"
                                    id="content-{{ $tabKey }}" role="tabpanel">
                                    @foreach($tab['fields'] as $tabFieldKey => $tabField)
                                        <div class="mb-3">
                                            <label class="form-label">{{ $tabField['label'] }}</label>
                                            @switch($tabField['type'])
                                                @case('image')
                                                    @if(isset($additionalFields[$fieldKey][$tabKey][$tabFieldKey]))
                                                        <div class="mb-2">
                                                            <img src="{{ Storage::url($additionalFields[$fieldKey][$tabKey][$tabFieldKey]) }}"
                                                                alt="Current Image"
                                                                class="img-thumbnail"
                                                                style="max-height: 200px;">
                                                        </div>
                                                        <input type="hidden"
                                                            name="old_{{ $tabKey }}_{{ $tabFieldKey }}"
                                                            value="{{ $additionalFields[$fieldKey][$tabKey][$tabFieldKey] }}">
                                                    @endif
                                                    <input type="file"
                                                        class="form-control"
                                                        name="fields[{{ $fieldKey }}][{{ $tabKey }}][{{ $tabFieldKey }}]"
                                                        accept="image/*">
                                                @break

                                                @default
                                                    <input type="{{ $tabField['type'] }}"
                                                        class="form-control"
                                                        name="fields[{{ $fieldKey }}][{{ $tabKey }}][{{ $tabFieldKey }}]"
                                                        value="{{ getSafeValue($additionalFields[$fieldKey][$tabKey][$tabFieldKey] ?? $tabField['default'] ?? '') }}"
                                                        {{ isset($tabField['required']) && $tabField['required'] ? 'required' : '' }}>
                                            @endswitch
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                @break

                @default
                    <input type="text" class="form-control @error($fieldKey) is-invalid @enderror"
                        id="{{ $fieldKey }}" name="fields[{{ $fieldKey }}]"
                        value="{{ getSafeValue($additionalFields[$fieldKey] ?? $field['default'] ?? '') }}"
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
    // Initialize repeater functionality
    function initializeRepeaterHandlers() {
        document.querySelectorAll('.add-repeater-item').forEach(button => {
            button.removeEventListener('click', handleAddRepeaterItem);
            button.addEventListener('click', handleAddRepeaterItem);
        });

        document.querySelectorAll('.remove-repeater-item').forEach(button => {
            button.removeEventListener('click', handleRemoveRepeaterItem);
            button.addEventListener('click', handleRemoveRepeaterItem);
        });
    }

    function handleAddRepeaterItem() {
        const container = this.closest('.repeater-container');
        const itemsContainer = container.querySelector('.repeater-items');
        const template = container.querySelector('.repeater-template');
        const index = itemsContainer.querySelectorAll('.repeater-item').length;

        const templateContent = template.content.cloneNode(true);
        templateContent.querySelectorAll('[name*="__INDEX__"]').forEach(input => {
            input.name = input.name.replace('__INDEX__', index);
        });

        itemsContainer.appendChild(templateContent);
        initializeRepeaterHandlers();
    }

    function handleRemoveRepeaterItem() {
        this.closest('.repeater-item').remove();
    }

    document.addEventListener('DOMContentLoaded', function() {
        initializeRepeaterHandlers();
    });
</script>
