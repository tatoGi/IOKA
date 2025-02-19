@extends('admin.layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-sm" style="width: 100%; max-width: 600px;">
            <div class="card-body">
                <h4 class="mb-4 text-center">Create New Page</h4>

                <form action="/ioka_admin/menu" method="POST" class="bg-white p-4 rounded">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                            class="form-control @error('title') is-invalid @enderror">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                            class="form-control @error('slug') is-invalid @enderror">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="keywords" class="form-label">Keywords</label>
                        <input type="text" name="keywords" id="keywords" value="{{ old('keywords') }}"
                            class="form-control @error('keywords') is-invalid @enderror">
                        @error('keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="desc" class="form-label">Description</label>
                        <textarea name="desc" id="desc" rows="4" class="form-control editor @error('desc') is-invalid @enderror">{{ old('desc') }}</textarea>
                        @error('desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="type_id" class="form-label">Type</label>
                        <select name="type_id" id="type_id" class="form-select @error('type_id') is-invalid @enderror">
                            <option value="">Select Page Type</option>
                            @foreach (Config::get('PageTypes') as $type)
                                <option value="{{ $type['id'] }}" {{ old('type_id') == $type['id'] ? 'selected' : '' }}>
                                    {{ $type['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="active" class="form-check-label">Active</label>
                        <input type="checkbox" name="active" id="active" value="1"
                            {{ old('active', 1) ? 'checked' : '' }} class="form-check-input">
                        @error('active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof ClassicEditor !== "undefined") {
            ClassicEditor.create(document.querySelector("#desc")).catch((error) => {
                console.error(error);
            });
        } else {
            console.error("CKEditor is not loaded properly.");
        }
    });
</script>
