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

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs mb-4" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#basic-info" role="tab">Basic Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#metadata-tab" role="tab">Meta Data</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <!-- Basic Info Tab -->
                        <div class="tab-pane active" id="basic-info" role="tabpanel">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                    name="title" value="{{ old('title') }}" required>
                                @error('title')
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
                                <label for="image" class="form-label">Image ( 372 x 200)</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="banner_image" class="form-label">Banner Imag (1560 x 400)</label>
                                <input type="file" class="form-control @error('banner_image') is-invalid @enderror" id="banner_image" name="banner_image">
                                @error('banner_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label for="banner_title" class="form-label">Banner Title</label>
                                <input type="text" class="form-control @error('banner_title') is-invalid @enderror" id="banner_title" name="banner_title" value="{{ old('banner_title') }}" placeholder="Enter banner title">
                                @error('banner_title')
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

                            <x-mobile-image-upload 
                                name="mobile_banner_image"
                                label="Mobile Banner Photo"
                                :required="true"
                                altName="mobile_banner_image_alt"
                                altLabel="Mobile Banner Photo Alt Text"
                                :altRequired="true"
                                altValue="{{ old('mobile_banner_image_alt') }}"
                                fieldId="mobile-upload-blog_mobile_banner_image"
                                :uploadRoute="route('mobile.image.upload')"
                            />

                            <div class="mb-3">
                                <label for="image_alt" class="form-label">Image Alt Text</label>
                                <input type="text" class="form-control @error('image_alt') is-invalid @enderror" id="image_alt" name="image_alt" value="{{ old('image_alt') }}">
                                @error('image_alt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <x-mobile-image-upload 
                                name="mobile_image"
                                label="Mobile Photo"
                                :required="true"
                                altName="mobile_image_alt"
                                altLabel="Mobile Photo Alt Text"
                                :altRequired="true"
                                altValue="{{ old('mobile_image_alt') }}"
                                fieldId="mobile-upload-blog_mobile_image"
                                :uploadRoute="route('mobile.image.upload')"
                            />

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
                        </div>

                        <!-- Meta Data Tab -->
                        @include('components.metadata-tab')
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

@push('styles')
    <style>
        .mobile-image-upload .preview-container {
            margin-top: 1rem;
        }
        .mobile-image-upload .preview-image {
            max-width: 100%;
            max-height: 200px;
            object-fit: contain;
        }
    </style>
@endpush

@push('scripts')
<script src="{{ asset('storage/admin/assets/blogpost.js') }}"></script>
@endpush
