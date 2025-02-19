@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Section - {{ $page->title }}</h3>
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
                        @include('admin.sections.fields', ['section' => $sectionConfig])
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
