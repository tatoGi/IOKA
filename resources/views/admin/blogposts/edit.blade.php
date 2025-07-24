@extends('admin.layouts.app')

@push('meta')
    <meta name="blog-post-id" content="{{ $blogPost->id }}">
@endpush

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
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                    @method('PUT')

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
                                    name="title" value="{{ old('title', $blogPost->title) }}" required>
                                @error('title')
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
                                <label for="image" class="form-label">Image ( 372 x 200)</label>
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
                                <label for="banner_image" class="form-label">Banner Image (1560 x 400)</label>
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
                                <label for="banner_title" class="form-label">Banner Title</label>
                                <input type="text" class="form-control @error('banner_title') is-invalid @enderror" id="banner_title" name="banner_title" value="{{ old('banner_title', $blogPost->banner_title) }}" placeholder="Enter banner title">
                                @error('banner_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="banner_image_alt" class="form-label">Banner Image Alt Text</label>
                                <input type="text" class="form-control @error('banner_image_alt') is-invalid @enderror" id="banner_image_alt" name="banner_image_alt" value="{{ old('banner_image_alt', $blogPost->banner_image_alt) }}">
                                @error('banner_image_alt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="mobile_banner_image_alt" class="form-label">Mobile Banner Photo Alt Text</label>
                                <input type="text" class="form-control @error('mobile_banner_image_alt') is-invalid @enderror" id="mobile_banner_image_alt" name="mobile_banner_image_alt" value="{{ old('mobile_banner_image_alt', $blogPost->mobile_banner_image_alt ?? '') }}">
                                @error('mobile_banner_image_alt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div id="mobile-upload-banner">
                                <x-mobile-image-upload
                                    name="mobile_banner_image"
                                    label="Mobile Banner Photo"
                                    altName="mobile_banner_image_alt"
                                    altLabel="Mobile Banner Photo Alt Text"
                                    altValue="{{ old('mobile_banner_image_alt', $blogPost->mobile_banner_image_alt) }}"
                                    fieldId="mobile_banner_image"
                                    value="{{ old('mobile_banner_image', $blogPost->mobile_banner_image) }}"
                                    fieldIdentifier="mobile_banner_image"
                                    upload-url="{{ route('mobile.image.upload') }}"
                                />
                            </div>
                            @if($blogPost->mobile_banner_image)

                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $blogPost->mobile_banner_image) }}" alt="{{ $blogPost->mobile_banner_image_alt }}" class="img-thumbnail" width="200">
                                <button type="button" class="btn btn-danger remove-mobile-banner-image-btn">
                                    <i class="fas fa-trash-alt me-1"></i> Remove Mobile Banner Photo
                                </button>
                            </div>
                            @endif
                           
                            <div id="mobile-upload-mobile">
                                <x-mobile-image-upload
                                    name="mobile_image"
                                    label="Mobile Photo"
                                    altName="mobile_image_alt"
                                    altLabel="Mobile Photo Alt Text"
                                    altValue="{{ old('mobile_image_alt', $blogPost->mobile_image_alt) }}"
                                    fieldId="mobile"
                                    value="{{ old('mobile_image', $blogPost->mobile_image) }}"
                                    fieldIdentifier="mobile"
                                    upload-url="{{ route('mobile.image.upload') }}"
                                />
                            </div>
                            @if($blogPost->mobile_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $blogPost->mobile_image) }}" alt="{{ $blogPost->mobile_image_alt }}" class="img-thumbnail" style="max-height: 150px;" alt="{{ $blogPost->mobile_image_alt ?? '' }}">
                                <button type="button" class="btn btn-danger remove-mobile-image-btn">
                                    <i class="fas fa-trash-alt me-1"></i> Remove Mobile Photo
                                </button>
                            </div>
                            @endif
                            <div class="mb-3">
                                <label for="mobile_image_alt" class="form-label">Mobile Photo Alt Text</label>
                                <input type="text" class="form-control @error('mobile_image_alt') is-invalid @enderror" id="mobile_image_alt" name="mobile_image_alt" value="{{ old('mobile_image_alt', $blogPost->mobile_image_alt ?? '') }}">
                                @error('mobile_image_alt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="hidden" name="show_on_main_page" value="0">
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
                        </div>

                        <!-- Meta Data Tab -->
                        <div class="tab-pane" id="metadata-tab" role="tabpanel">
                            <div class="mb-3">
                                <label for="metadata[meta_title]" class="form-label">Meta Title</label>
                                <input type="text" class="form-control @error('metadata.meta_title') is-invalid @enderror"
                                    id="metadata[meta_title]" name="metadata[meta_title]"
                                    value="{{ old('metadata.meta_title', $blogPost->metadata?->meta_title) }}">
                                @error('metadata.meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="metadata[meta_description]" class="form-label">Meta Description</label>
                                <textarea class="form-control @error('metadata.meta_description') is-invalid @enderror"
                                    id="metadata[meta_description]" name="metadata[meta_description]" rows="3"
                                    >{{ old('metadata.meta_description', $blogPost->metadata?->meta_description) }}</textarea>
                                @error('metadata.meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="metadata[meta_keywords]" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control @error('metadata.meta_keywords') is-invalid @enderror"
                                    id="metadata[meta_keywords]" name="metadata[meta_keywords]"
                                    value="{{ old('metadata.meta_keywords', $blogPost->metadata?->meta_keywords) }}">
                                @error('metadata.meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <h4 class="mt-4">Open Graph</h4>
                            <div class="mb-3">
                                <label for="metadata[og_title]" class="form-label">OG Title</label>
                                <input type="text" class="form-control @error('metadata.og_title') is-invalid @enderror"
                                    id="metadata[og_title]" name="metadata[og_title]"
                                    value="{{ old('metadata.og_title', $blogPost->metadata?->og_title) }}">
                                @error('metadata.og_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="metadata[og_description]" class="form-label">OG Description</label>
                                <textarea class="form-control @error('metadata.og_description') is-invalid @enderror"
                                    id="metadata[og_description]" name="metadata[og_description]" rows="3"
                                    >{{ old('metadata.og_description', $blogPost->metadata?->og_description) }}</textarea>
                                @error('metadata.og_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <h4 class="mt-4">Twitter Card</h4>
                            <div class="mb-3">
                                <label for="metadata[twitter_card]" class="form-label">Twitter Card Type</label>
                                <select class="form-control @error('metadata.twitter_card') is-invalid @enderror"
                                    id="metadata[twitter_card]" name="metadata[twitter_card]">
                                    <option value="summary" {{ old('metadata.twitter_card', $blogPost->metadata?->twitter_card) == 'summary' ? 'selected' : '' }}>Summary</option>
                                    <option value="summary_large_image" {{ old('metadata.twitter_card', $blogPost->metadata?->twitter_card) == 'summary_large_image' ? 'selected' : '' }}>Summary Large Image</option>
                                </select>
                                @error('metadata.twitter_card')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="metadata[twitter_title]" class="form-label">Twitter Title</label>
                                <input type="text" class="form-control @error('metadata.twitter_title') is-invalid @enderror"
                                    id="metadata[twitter_title]" name="metadata[twitter_title]"
                                    value="{{ old('metadata.twitter_title', $blogPost->metadata?->twitter_title) }}">
                                @error('metadata.twitter_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="metadata[twitter_description]" class="form-label">Twitter Description</label>
                                <textarea class="form-control @error('metadata.twitter_description') is-invalid @enderror"
                                    id="metadata[twitter_description]" name="metadata[twitter_description]" rows="3"
                                    >{{ old('metadata.twitter_description', $blogPost->metadata?->twitter_description) }}</textarea>
                                @error('metadata.twitter_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
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

@push('scripts')
    <script src="{{ asset('storage/admin/assets/blogpost.js') }}"></script>

    <script>
        document.getElementById('remove-image-btn')?.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove the image?')) {
                fetch('{{ route('blogposts.removeImage', ['blogPost' => $blogPost]) }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ type: 'image' })
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

        document.getElementById('remove-banner-image-btn')?.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove the banner image?')) {
                fetch('{{ route('blogposts.removeImage', ['blogPost' => $blogPost]) }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ type: 'banner_image' })
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

        document.getElementById('remove-og-image-btn')?.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove the OG image?')) {
                fetch('{{ route("admin.blogposts.delete-og-image", ["blogpost" => $blogPost]) }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('OG image removed successfully');
                        location.reload();
                    } else {
                        alert('Failed to remove OG image.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });

        document.getElementById('remove-twitter-image-btn')?.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove the Twitter image?')) {
                fetch('{{ route("admin.blogposts.delete-twitter-image", ["blogpost" => $blogPost]) }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Twitter image removed successfully');
                        location.reload();
                    } else {
                        alert('Failed to remove Twitter image.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });

        // Remove Mobile Banner Image (use class for multiple possible buttons)
        document.querySelectorAll('.remove-mobile-banner-image-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                if (confirm('Are you sure you want to remove the mobile banner photo?')) {
                    fetch('{{ route("blogposts.removeImage", ["blogPost" => $blogPost]) }}', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ type: 'mobile_banner_image' })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert('Failed to remove mobile banner photo.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });

        // Remove Mobile Photo (use class for multiple possible buttons)
        document.querySelectorAll('.remove-mobile-image-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                if (confirm('Are you sure you want to remove the mobile photo?')) {
                    fetch('{{ route("blogposts.removeImage", ["blogPost" => $blogPost]) }}', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ type: 'mobile_image' })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert('Failed to remove mobile photo.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });
    </script>
@endpush

