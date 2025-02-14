@foreach ($section['fields'] as $fieldKey => $field)
    <div class="mb-3">
        <label for="{{ $fieldKey }}" class="form-label">{{ $field['label'] }}</label>

        @switch($field['type'])
            @case('text')
                <input type="text" class="form-control @error($fieldKey) is-invalid @enderror"
                    id="{{ $fieldKey }}" name="fields[{{ $fieldKey }}]"
                    value="{{ old("fields.$fieldKey", $field['default'] ?? '') }}"
                    {{ isset($field['required']) && $field['required'] ? 'required' : '' }}>
            @break

            @case('textarea')
                <textarea
                    class="editor form-control @error($fieldKey) is-invalid @enderror"
                    id="{{ $fieldKey }}"
                    name="fields[{{ $fieldKey }}]"
                    {{ isset($field['required']) && $field['required'] ? 'required' : '' }}
                >{{ old("fields.$fieldKey", $field['default'] ?? '') }}</textarea>
            @break

            @case('image')
                <input type="file" class="form-control @error($fieldKey) is-invalid @enderror"
                    id="{{ $fieldKey }}" name="fields[{{ $fieldKey }}]" accept="image/*"
                    {{ isset($field['required']) && $field['required'] ? 'required' : '' }}>
            @break

            @case('url')
                <input type="url" class="form-control @error($fieldKey) is-invalid @enderror"
                    id="{{ $fieldKey }}" name="fields[{{ $fieldKey }}]"
                    value="{{ old("fields.$fieldKey", $field['default'] ?? '') }}"
                    {{ isset($field['required']) && $field['required'] ? 'required' : '' }}>
            @break

            @case('number')
                <input type="number" class="form-control @error($fieldKey) is-invalid @enderror"
                    id="{{ $fieldKey }}" name="fields[{{ $fieldKey }}]"
                    value="{{ old("fields.$fieldKey", $field['default'] ?? '') }}"
                    {{ isset($field['required']) && $field['required'] ? 'required' : '' }}>
            @break

            @case('image_or_like_this')
                <input type="file" class="form-control @error($fieldKey) is-invalid @enderror"
                    id="{{ $fieldKey }}" name="fields[{{ $fieldKey }}]" accept="image/*"
                    {{ isset($field['required']) && $field['required'] ? 'required' : '' }}>
                <button type="button" class="btn btn-secondary mt-2" onclick="previewImage('{{ $fieldKey }}')">Preview</button>
                <div id="preview-{{ $fieldKey }}" class="mt-2"></div>
            @break

            @case('repeater')
                <div class="repeater-container" data-field="{{ $fieldKey }}"
                    data-min="{{ $field['min'] ?? 0 }}" data-max="{{ $field['max'] ?? '' }}">
                    <div class="repeater-items">
                        @if (old("fields.$fieldKey"))
                            @foreach (old("fields.$fieldKey") as $index => $item)
                                <div class="repeater-item card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-end mb-2">
                                            <button type="button"
                                                class="btn btn-danger btn-sm remove-repeater-item">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </div>
                                        @foreach ($field['fields'] as $repeaterFieldKey => $repeaterField)
                                            <div class="mb-3">
                                                <label
                                                    class="form-label">{{ $repeaterField['label'] }}</label>
                                                @php
                                                    $inputType = $repeaterField['type'];
                                                    if ($repeaterFieldKey === 'phone') {
                                                        $inputType = 'tel';
                                                    } elseif (
                                                        $repeaterFieldKey === 'email'
                                                    ) {
                                                        $inputType = 'email';
                                                    }
                                                @endphp
                                                <input type="{{ $inputType }}"
                                                    class="form-control @error("fields.{$fieldKey}.{$index}.{$repeaterFieldKey}") is-invalid @enderror"
                                                    name="fields[{{ $fieldKey }}][{{ $index }}][{{ $repeaterFieldKey }}]"
                                                    value="{{ $item[$repeaterFieldKey] ?? '' }}"
                                                    {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}>
                                                @error("fields.{$fieldKey}.{$index}.{$repeaterFieldKey}")
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="repeater-item card mb-3" style="display: none;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-end mb-2">
                                        <button type="button" class="btn btn-danger btn-sm remove-repeater-item">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </div>
                                    @foreach ($field['fields'] as $repeaterFieldKey => $repeaterField)
                                        <div class="mb-3">
                                            <label class="form-label">{{ $repeaterField['label'] }}</label>
                                            @php
                                                $inputType = $repeaterField['type'];
                                                if ($repeaterFieldKey === 'phone') {
                                                    $inputType = 'tel';
                                                } elseif ($repeaterFieldKey === 'email') {
                                                    $inputType = 'email';
                                                }
                                            @endphp
                                            <input type="{{ $inputType }}"
                                                class="form-control"
                                                name="fields[{{ $fieldKey }}][0][{{ $repeaterFieldKey }}]"
                                                value=""
                                                {{ isset($repeaterField['required']) && $repeaterField['required'] ? 'required' : '' }}>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
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
                                    @case('text')
                                        <input type="text"
                                            class="form-control @error("fields.{$fieldKey}.{$groupFieldKey}") is-invalid @enderror"
                                            name="fields[{{ $fieldKey }}][{{ $groupFieldKey }}]"
                                            value="{{ old("fields.{$fieldKey}.{$groupFieldKey}", $groupField['default'] ?? '') }}"
                                            {{ isset($groupField['required']) && $groupField['required'] ? 'required' : '' }}>
                                    @break

                                    @case('textarea')
                                        <textarea
                                            class="editor form-control @error("fields.{$fieldKey}.{$groupFieldKey}") is-invalid @enderror"
                                            name="fields[{{ $fieldKey }}][{{ $groupFieldKey }}]"
                                            {{ isset($groupField['required']) && $groupField['required'] ? 'required' : '' }}
                                        >{{ old("fields.{$fieldKey}.{$groupFieldKey}", $groupField['default'] ?? '') }}</textarea>
                                    @break

                                    @default
                                        <input type="{{ $groupField['type'] }}"
                                            class="form-control @error("fields.{$fieldKey}.{$groupFieldKey}") is-invalid @enderror"
                                            name="fields[{{ $fieldKey }}][{{ $groupFieldKey }}]"
                                            value="{{ old("fields.{$fieldKey}.{$groupFieldKey}", $groupField['default'] ?? '') }}"
                                            {{ isset($groupField['required']) && $groupField['required'] ? 'required' : '' }}>
                                @endswitch
                                @error("fields.{$fieldKey}.{$groupFieldKey}")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                    data-bs-target="#content-{{ $tabKey }}" type="button"
                                    role="tab">
                                    {{ $tab['label'] }}
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content mt-3">
                        @foreach ($field['tabs'] as $tabKey => $tab)
                            <div class="tab-pane fade @if ($loop->first) show active @endif"
                                id="content-{{ $tabKey }}" role="tabpanel">
                                @if (isset($tab['fields']) && is_array($tab['fields']))
                                    @foreach ($tab['fields'] as $tabFieldKey => $tabField)
                                        <div class="mb-3">
                                            <label
                                                class="form-label">{{ $tabField['label'] ?? ucfirst($tabFieldKey) }}</label>

                                            @switch($tabField['type'] ?? 'text')
                                                @case('text')
                                                    <input type="text"
                                                        class="form-control @error("{$tabKey}.{$tabFieldKey}") is-invalid @enderror"
                                                        name="fields[{{ $tabKey }}][{{ $tabFieldKey }}]"
                                                        value="{{ old("fields.{$tabKey}.{$tabFieldKey}", $tabField['default'] ?? '') }}"
                                                        {{ isset($tabField['required']) && $tabField['required'] ? 'required' : '' }}>
                                                @break

                                                @case('textarea')
                                                    <textarea class="form-control @error("{$tabKey}.{$tabFieldKey}") is-invalid @enderror"
                                                        name="fields[{{ $tabKey }}][{{ $tabFieldKey }}]" rows="3"
                                                        id="paragraph"
                                                        {{ isset($tabField['required']) && $tabField['required'] ? 'required' : '' }}>{{ old("fields.{$tabKey}.{$tabFieldKey}", $tabField['default'] ?? '') }}</textarea>
                                                @break

                                                @case('image')
                                                    <input type="file"
                                                        class="form-control @error("{$tabKey}.{$tabFieldKey}") is-invalid @enderror"
                                                        name="fields[{{ $tabKey }}][{{ $tabFieldKey }}]"
                                                        accept="image/*"
                                                        {{ isset($tabField['required']) && $tabField['required'] ? 'required' : '' }}>
                                                @break

                                                @default
                                                    <input type="text"
                                                        class="form-control @error("{$tabKey}.{$tabFieldKey}") is-invalid @enderror"
                                                        name="fields[{{ $tabKey }}][{{ $tabFieldKey }}]"
                                                        value="{{ old("fields.{$tabKey}.{$tabFieldKey}", $tabField['default'] ?? '') }}"
                                                        {{ isset($tabField['required']) && $tabField['required'] ? 'required' : '' }}>
                                            @endswitch

                                            @error("{$tabKey}.{$tabFieldKey}")
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    @endforeach
                                @else
                                    <div class="alert alert-warning">
                                        No fields defined for this tab.
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @break

            @default
                <input type="text"
                    class="form-control @error($fieldKey) is-invalid @enderror"
                    id="{{ $fieldKey }}" name="fields[{{ $fieldKey }}]"
                    value="{{ old("fields.$fieldKey", $field['default'] ?? '') }}"
                    {{ isset($field['required']) && $field['required'] ? 'required' : '' }}>
        @endswitch

        @error($fieldKey)
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
@endforeach

<script>
    function previewImage(fieldKey) {
        const input = document.getElementById(fieldKey);
        const previewContainer = document.getElementById(`preview-${fieldKey}`);
        previewContainer.innerHTML = '';

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-thumbnail');
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.add-repeater-item').forEach(button => {
            button.addEventListener('click', function () {
                const container = this.closest('.repeater-container');
                const itemsContainer = container.querySelector('.repeater-items');
                const fieldKey = container.getAttribute('data-field');
                const index = itemsContainer.children.length;
                const templateItem = container.querySelector('.repeater-item');

                if (templateItem) {
                    const newItem = document.createElement('div');
                    newItem.classList.add('repeater-item', 'card', 'mb-3');
                    newItem.innerHTML = `
                        <div class="card-body">
                            <div class="d-flex justify-content-end mb-2">
                                <button type="button" class="btn btn-danger btn-sm remove-repeater-item">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                            ${templateItem.innerHTML.replace(/\[0\]/g, `[${index}]`).replace(/\.0\./g, `.${index}.`)}
                        </div>
                    `;
                    itemsContainer.appendChild(newItem);

                    newItem.querySelector('.remove-repeater-item').addEventListener('click', function () {
                        newItem.remove();
                    });
                }
            });
        });

        document.querySelectorAll('.remove-repeater-item').forEach(button => {
            button.addEventListener('click', function () {
                this.closest('.repeater-item').remove();
            });
        });
    });
</script>
