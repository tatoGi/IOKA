@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1>Create Blog Post</h1>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('blogposts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                            name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Subtitle</label>
                        <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle"
                            name="subtitle" value="{{ old('subtitle') }}">
                        @error('subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug"
                            name="slug" value="{{ old('slug') }}" required>
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="body" class="form-label">Body</label>
                        <textarea class="form-control editor @error('body') is-invalid @enderror" id="body" name="body" rows="10"
                            required>{{ old('body') }}</textarea>
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" id="date"
                            name="date" value="{{ old('date') }}" required>
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
                    </div>

                    <div class="mb-3">
                        <label for="banner_image" class="form-label">Banner Image</label>
                        <input type="file" class="form-control @error('banner_image') is-invalid @enderror" id="banner_image" name="banner_image">
                        @error('banner_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="banner_image_alt" class="form-label">Banner Image Alt Text</label>
                        <input type="text" class="form-control @error('banner_image_alt') is-invalid @enderror" id="banner_image_alt" name="banner_image_alt" value="{{ old('banner_image_alt') }}">
                        @error('banner_image_alt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image_alt" class="form-label">Image Alt Text</label>
                        <input type="text" class="form-control @error('image_alt') is-invalid @enderror" id="image_alt" name="image_alt" value="{{ old('image_alt') }}">
                        @error('image_alt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input show_on" id="show_on_main_page"
                                name="show_on_main_page" value="1" {{ old('show_on_main_page') ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_on_main_page">Show on Main Page</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <select class="form-control tags" id="tags" name="tags[]" multiple="multiple">
                            @if (old('tags'))
                                @foreach (old('tags') as $tag)
                                    <option value="{{ $tag }}" selected>{{ $tag }}</option>
                                @endforeach
                            @endif
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->name }}"
                                    {{ in_array($tag->name, old('tags', [])) ? 'selected' : '' }}>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Type to add new tags or select existing ones. Press Enter or
                            comma to add.</small>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Create Post</button>
                        <a href="{{ route('blogposts.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('storage/admin/assets/blogpost.js') }}"></script>
@endsection


