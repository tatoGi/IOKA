@props(['model'])

<div class="mb-3">
    <label for="metadata[meta_title]" class="form-label">Meta Title</label>
    <input type="text" class="form-control @error('metadata.meta_title') is-invalid @enderror"
        id="metadata[meta_title]" name="metadata[meta_title]"
        value="{{ old('metadata.meta_title', $model->metadata?->meta_title) }}">
    @error('metadata.meta_title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="metadata[meta_description]" class="form-label">Meta Description</label>
    <textarea class="form-control @error('metadata.meta_description') is-invalid @enderror"
        id="metadata[meta_description]" name="metadata[meta_description]" rows="3"
        >{{ old('metadata.meta_description', $model->metadata?->meta_description) }}</textarea>
    @error('metadata.meta_description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="metadata[meta_keywords]" class="form-label">Meta Keywords</label>
    <input type="text" class="form-control @error('metadata.meta_keywords') is-invalid @enderror"
        id="metadata[meta_keywords]" name="metadata[meta_keywords]"
        value="{{ old('metadata.meta_keywords', $model->metadata?->meta_keywords) }}">
    @error('metadata.meta_keywords')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<h4 class="mt-4">Open Graph</h4>
<div class="mb-3">
    <label for="metadata[og_title]" class="form-label">OG Title</label>
    <input type="text" class="form-control @error('metadata.og_title') is-invalid @enderror"
        id="metadata[og_title]" name="metadata[og_title]"
        value="{{ old('metadata.og_title', $model->metadata?->og_title) }}">
    @error('metadata.og_title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="metadata[og_description]" class="form-label">OG Description</label>
    <textarea class="form-control @error('metadata.og_description') is-invalid @enderror"
        id="metadata[og_description]" name="metadata[og_description]" rows="3"
        >{{ old('metadata.og_description', $model->metadata?->og_description) }}</textarea>
    @error('metadata.og_description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="og_image" class="form-label">OG Image</label>
    <input type="file" class="form-control @error('metadata.og_image') is-invalid @enderror"
        id="og_image" name="og_image">
    @error('metadata.og_image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @if($model->metadata?->og_image)
        <div class="mt-2">
            <img src="{{ asset('storage/' . $model->metadata->og_image) }}" class="img-thumbnail" width="200">
            <button type="button" class="btn btn-danger btn-sm mt-2 delete-metadata-image"
                data-url="{{ route('admin.' . strtolower(class_basename($model)) . '.delete-og-image', $model->id) }}">
                Delete OG Image
            </button>
        </div>
    @endif
</div>

<h4 class="mt-4">Twitter Card</h4>
<div class="mb-3">
    <label for="metadata[twitter_card]" class="form-label">Twitter Card Type</label>
    <select class="form-control @error('metadata.twitter_card') is-invalid @enderror"
        id="metadata[twitter_card]" name="metadata[twitter_card]">
        <option value="summary" {{ old('metadata.twitter_card', $model->metadata?->twitter_card) == 'summary' ? 'selected' : '' }}>Summary</option>
        <option value="summary_large_image" {{ old('metadata.twitter_card', $model->metadata?->twitter_card) == 'summary_large_image' ? 'selected' : '' }}>Summary Large Image</option>
    </select>
    @error('metadata.twitter_card')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="metadata[twitter_title]" class="form-label">Twitter Title</label>
    <input type="text" class="form-control @error('metadata.twitter_title') is-invalid @enderror"
        id="metadata[twitter_title]" name="metadata[twitter_title]"
        value="{{ old('metadata.twitter_title', $model->metadata?->twitter_title) }}">
    @error('metadata.twitter_title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="metadata[twitter_description]" class="form-label">Twitter Description</label>
    <textarea class="form-control @error('metadata.twitter_description') is-invalid @enderror"
        id="metadata[twitter_description]" name="metadata[twitter_description]" rows="3"
        >{{ old('metadata.twitter_description', $model->metadata?->twitter_description) }}</textarea>
    @error('metadata.twitter_description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="twitter_image" class="form-label">Twitter Image</label>
    <input type="file" class="form-control @error('metadata.twitter_image') is-invalid @enderror"
        id="twitter_image" name="twitter_image">
    @error('metadata.twitter_image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @if($model->metadata?->twitter_image)
        <div class="mt-2">
            <img src="{{ asset('storage/' . $model->metadata->twitter_image) }}" class="img-thumbnail" width="200">
            <button type="button" class="btn btn-danger btn-sm mt-2 delete-metadata-image"
                data-url="{{ route('admin.' . strtolower(class_basename($model)) . '.delete-twitter-image', $model->id) }}">
                Delete Twitter Image
            </button>
        </div>
    @endif
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.delete-metadata-image').click(function() {
        const button = $(this);
        const url = button.data('url');

        if (confirm('Are you sure you want to delete this image?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        button.closest('.mt-2').remove();
                    }
                },
                error: function(xhr) {
                    alert('Error deleting image. Please try again.');
                }
            });
        }
    });
});
</script>
@endpush
