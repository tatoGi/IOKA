@extends('admin.layouts.app')

@section('content')
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">Add New Partner</h1>
            <a href="{{ url('ioka_admin/partners') }}" class="btn btn-secondary">
                Back to Partners
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Use POST method with hidden _method field to simulate PUT -->
                <form action="{{ url('ioka_admin/partners') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST') <!-- This simulates the PUT request -->

                    <!-- Title Field -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Partner Title</label>
                        <input type="text" id="title" name="title"
                            class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Image Field -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Partner Image</label>
                        <input type="file" id="image" name="image"
                            class="form-control @error('image') is-invalid @enderror" accept="image/*" required>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- URL Field -->
                    <div class="mb-3">
                        <label for="url" class="form-label">Partner URL</label>
                        <input type="url" id="url" name="url"
                            class="form-control @error('url') is-invalid @enderror" value="{{ old('url') }}" required>
                        @error('url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit and Cancel Buttons -->
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Save Partner</button>
                        <a href="{{ url('ioka_admin/partners') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
