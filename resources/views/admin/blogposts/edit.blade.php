@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1>Edit Blog Post</h1>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('blogposts.update', $blogPost) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                            name="title" value="{{ old('title', $blogPost->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Subtitle</label>
                        <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle"
                            name="subtitle" value="{{ old('subtitle', $blogPost->subtitle) }}">
                        @error('subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug"
                            name="slug" value="{{ old('slug', $blogPost->slug) }}" required>
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="body" class="form-label">Body</label>
                        <textarea class="form-control editor @error('body')  is-invalid @enderror" id="body" name="body" rows="10"
                            required>{{ old('body', $blogPost->body) }}</textarea>
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" id="date"
                            name="date"
                            value="{{ old('date', $blogPost->date ? \Carbon\Carbon::parse($blogPost->date)->format('Y-m-d') : '') }}"
                            required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if ($blogPost->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $blogPost->image) }}" alt="{{ $blogPost->image_alt }}" class="img-thumbnail" width="200">
                                <button type="button" class="btn btn-danger mt-2" id="remove-image-btn">Remove Image</button>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="banner_image" class="form-label">Banner Image</label>
                        <input type="file" class="form-control @error('banner_image') is-invalid @enderror" id="banner_image" name="banner_image">
                        @error('banner_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if ($blogPost->banner_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $blogPost->banner_image) }}" alt="{{ $blogPost->banner_image_alt }}" class="img-thumbnail" width="200">
                                <button type="button" class="btn btn-danger mt-2" id="remove-banner-image-btn">Remove Banner Image</button>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="banner_image_alt" class="form-label">Banner Image Alt Text</label>
                        <input type="text" class="form-control @error('banner_image_alt') is-invalid @enderror" id="banner_image_alt" name="banner_image_alt" value="{{ old('banner_image_alt', $blogPost->banner_image_alt) }}">
                        @error('banner_image_alt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image_alt" class="form-label">Image Alt Text</label>
                        <input type="text" class="form-control @error('image_alt') is-invalid @enderror" id="image_alt" name="image_alt" value="{{ old('image_alt', $blogPost->image_alt) }}">
                        @error('image_alt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="show_on_main_page" name="show_on_main_page"
                                value="1"
                                {{ old('show_on_main_page', $blogPost->show_on_main_page) ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_on_main_page">Show on Main Page</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <select class="form-control tags" id="tags" name="tags[]" multiple>
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->name }}"
                                    {{ in_array($tag->name, $blogPost->tags->pluck('name')->toArray()) ? 'selected' : '' }}>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Update Post</button>
                        <a href="{{ route('blogposts.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('storage/admin/assets/blogpost.js') }}"></script>
    <script>
        document.getElementById('remove-image-btn').addEventListener('click', function() {
            if (confirm('Are you sure you want to remove the image?')) {
                fetch('{{ route('blogposts.removeImage', $blogPost) }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Failed to remove image.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });

        document.getElementById('remove-banner-image-btn').addEventListener('click', function() {
            if (confirm('Are you sure you want to remove the banner image?')) {
                fetch('{{ route('blogposts.removeImage', $blogPost) }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ banner_image: true })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Failed to remove banner image.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    </script>
@endsection
