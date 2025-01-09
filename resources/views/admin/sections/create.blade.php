@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create Section: {{ $section['label'] }}</h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.sections.store', ['pageId' => $pageId]) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="section_key" value="{{ $sectionKey }}">

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

                                        @case('repeater')
                                            <div class="repeater-container" data-field="{{ $fieldKey }}"
                                                data-min="{{ $field['min'] ?? 0 }}" data-max="{{ $field['max'] ?? '' }}">
                                                <div class="repeater-items">
                                                    <!-- Repeater items will be added here dynamically -->
                                                </div>
                                                <button type="button" class="btn btn-primary add-repeater-item mt-2">
                                                    Add {{ $field['label'] }}
                                                </button>
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
                                    @endswitch

                                    @error($fieldKey)
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            @endforeach
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Create Section</button>
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add this hidden input to pass section data to JavaScript -->
        <input type="hidden" id="section-data" value="{{ json_encode($section) }}">
    </div>

    @push('scripts')
        <script src="{{ asset('admin/sections/section.js') }}"></script>
    @endpush

    @push('styles')
        <style>
            .nav-tabs .nav-link {
                color: #6c757d;
                border: 1px solid transparent;
                margin-bottom: -1px;
                transition: all 0.3s ease;
            }

            .nav-tabs .nav-link:hover {
                border-color: #e9ecef #e9ecef #dee2e6;
                isolation: isolate;
                color: #0d6efd;
            }

            .nav-tabs .nav-link.active {
                color: #0d6efd;
                background-color: #fff;
                border-color: #dee2e6 #dee2e6 #fff;
                font-weight: 500;
            }

            .nav-tabs .nav-item.show .nav-link,
            .nav-tabs .nav-link.active {
                border-bottom: 2px solid #0d6efd;
            }
        </style>
    @endpush

@endsection
