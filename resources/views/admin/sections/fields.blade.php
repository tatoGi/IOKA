@switch($field['type'])
    @case('repeater')
        <div class="form-group">
            <label>{{ $field['label'] }}</label>
            <div class="repeater-container" data-min="{{ $field['min_items'] ?? 0 }}" data-max="{{ $field['max_items'] ?? null }}">
                {{-- Repeater fields implementation --}}
            </div>
        </div>
    @break

    @case('image')
        <div class="form-group">
            <label>{{ $field['label'] }}</label>
            <input type="file" name="{{ $fieldName }}" class="form-control @error($fieldName) is-invalid @enderror"
                @if ($field['required'] ?? false) required @endif>
            @error($fieldName)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @break

    @case('text')
        <div class="form-group">
            <label>{{ $field['label'] }}</label>
            <input type="text" name="{{ $fieldName }}" value="{{ $value ?? ($field['default'] ?? '') }}"
                class="form-control @error($fieldName) is-invalid @enderror" @if ($field['required'] ?? false) required @endif>
            @error($fieldName)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @break

    @case('textarea')
        <div class="form-group">
            <label>{{ $field['label'] }}</label>
            <textarea name="{{ $fieldName }}" class="form-control @error($fieldName) is-invalid @enderror"
                @if ($field['required'] ?? false) required @endif>{{ $value ?? ($field['default'] ?? '') }}</textarea>
            @error($fieldName)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @break

    @case('url')
        <div class="form-group">
            <label>{{ $field['label'] }}</label>
            <input type="url" name="{{ $fieldName }}" value="{{ $value ?? ($field['default'] ?? '') }}"
                class="form-control @error($fieldName) is-invalid @enderror" @if ($field['required'] ?? false) required @endif>
            @error($fieldName)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @break

    @default
        <div class="form-group">
            <label>{{ $field['label'] }}</label>
            <input type="{{ $field['type'] }}" name="{{ $fieldName }}" value="{{ $value ?? ($field['default'] ?? '') }}"
                class="form-control @error($fieldName) is-invalid @enderror" @if ($field['required'] ?? false) required @endif>
            @error($fieldName)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
@endswitch
