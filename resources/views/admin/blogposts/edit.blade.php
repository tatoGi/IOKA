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
                <form action="{{ route('blogposts.update', $blogPost) }}" method="POST">
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
                        <label for="body" class="form-label">Body</label>
                        <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="10"
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
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="show_on_main_page" name="show_on_main_page"
                                value="1"
                                {{ old('show_on_main_page', $blogPost->show_on_main_page) ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_on_main_page">Show on Main Page</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <select class="form-select" id="tags" name="tags[]" multiple>
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

    @push('scripts')
        <script>
            // Initialize select2 for tags
            $(document).ready(function() {
                $('#tags').select2({
                    tags: true,
                    tokenSeparators: [',', ' '],
                    placeholder: 'Select or create tags'
                });
            });
        </script>
    @endpush
@endsection
