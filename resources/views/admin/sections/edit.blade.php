@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Section - {{ $section->title }}</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-arrow-left"></i> Back to Page
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.sections.update', ['pageId' => $pageId, 'sectionKey' => $sectionKey]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div id="section-fields">
                        @foreach ($sectionConfig['fields'] as $fieldKey => $field)

                            <div class="form-group">
                                <label
                                    for="{{ $fieldKey }}">{{ $field['label'] ?? ucfirst(str_replace('_', ' ', $fieldKey)) }}</label>

                                @switch($field['type'])
                                    @case('text')
                                        <input type="text" name="fields[{{ $fieldKey }}]" id="{{ $fieldKey }}"
                                            class="form-control @error('fields.' . $fieldKey) is-invalid @enderror"
                                            value="{{ old('fields.' . $fieldKey, $section->$fieldKey ?? $section->additional_fields[$fieldKey] ?? '') }}">
                                    @break

                                    @case('textarea')
                                        <textarea name="fields[{{ $fieldKey }}]" id="{{ $fieldKey }}"
                                            class="form-control @error('fields.' . $fieldKey) is-invalid @enderror" rows="4">{{ old('fields.' . $fieldKey, $section->additional_fields[$fieldKey] ?? '') }}</textarea>
                                    @break

                                    @case('image')
                                        @if (isset($section->additional_fields[$fieldKey]))
                                            <div class="mb-2">
                                                <img src="{{ Storage::url($section->additional_fields[$fieldKey]) }}"
                                                    alt="Current Image" class="img-thumbnail" style="max-height: 200px;">
                                            </div>
                                        @endif
                                        <div class="custom-file">
                                            <input type="file" name="fields[{{ $fieldKey }}]" id="{{ $fieldKey }}"
                                                class="custom-file-input @error('fields.' . $fieldKey) is-invalid @enderror"
                                                accept="image/*">
                                            <label class="custom-file-label" for="{{ $fieldKey }}">Choose file</label>
                                        </div>
                                    @break

                                    @case('repeater')
                                        <div class="repeater-container" data-field="{{ $fieldKey }}">
                                            @if (isset($section->additional_fields[$fieldKey]) && is_array($section->additional_fields[$fieldKey]))
                                                @foreach ($section->additional_fields[$fieldKey] as $index => $item)
                                                    <div class="repeater-item card mb-3">
                                                        <div class="card-body">
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm float-right remove-repeater">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                            @foreach ($field['fields'] as $subKey => $subField)
                                                                <div class="form-group">
                                                                    <label>{{ $subField['label'] }}</label>
                                                                    @if ($subField['type'] === 'image')
                                                                        @if (isset($item[$subKey]))
                                                                            <div class="mb-2">
                                                                                <img src="{{ Storage::url($item[$subKey]) }}"
                                                                                    alt="Current Image" class="img-thumbnail"
                                                                                    style="max-height: 100px;">
                                                                            </div>
                                                                        @endif
                                                                        <div class="custom-file">
                                                                            <input type="file"
                                                                                name="fields[{{ $fieldKey }}][{{ $index }}][{{ $subKey }}]"
                                                                                class="custom-file-input" accept="image/*">
                                                                            <label class="custom-file-label">Choose file</label>
                                                                        </div>
                                                                    @else
                                                                        <input type="text"
                                                                            name="fields[{{ $fieldKey }}][{{ $index }}][{{ $subKey }}]"
                                                                            class="form-control"
                                                                            value="{{ $item[$subKey] ?? '' }}">
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif

                                        </div>
                                    @break

                                    @default
                                        <input type="text" name="fields[{{ $fieldKey }}]" id="{{ $fieldKey }}"
                                            class="form-control @error('fields.' . $fieldKey) is-invalid @enderror"
                                            value="{{ old('fields.' . $fieldKey, $section->additional_fields[$fieldKey] ?? '') }}">
                                @endswitch

                                @error('fields.' . $fieldKey)
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update Section</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Wait for the DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Select all file inputs
            const fileInputs = document.querySelectorAll('.custom-file-input');

            // Add event listener to each file input
            fileInputs.forEach(function(input) {
                input.addEventListener('change', function(e) {
                    var fileName = e.target.files[0].name;
                    var label = e.target.nextElementSibling;
                    label.innerHTML = fileName;
                });
            });
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            // Log the form data
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            // Send the form data using fetch to see the validation errors
            fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                })
                .then(response => response.json())
                .catch(error => {
                    console.error('Error:', error);
                })
                .then(data => {
                    console.log('Response:', data);
                    if (!data.errors) {
                        // If no validation errors, submit the form normally
                        this.submit();
                    }
                });
        });
    </script>
@endpush
