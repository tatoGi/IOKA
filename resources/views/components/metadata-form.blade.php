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
            <button type="button" class="btn btn-danger mt-2 remove-og-image-btn">Remove OG Image</button>
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
            <button type="button" class="btn btn-danger mt-2 remove-twitter-image-btn">Remove Twitter Image</button>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle image removal buttons
    document.querySelectorAll('.remove-image-button').forEach(button => {
        button.addEventListener('click', function() {
            const imageType = this.dataset.imageType;
            const previewContainer = document.getElementById(imageType + '_image_preview_container');
            const hiddenInput = document.getElementById('remove_' + imageType + '_image_hidden_input');
            const fileInput = document.getElementById('metadata[' + imageType + '_image]');

            if (previewContainer) {
                previewContainer.style.display = 'none';
            }
            if (hiddenInput) {
                hiddenInput.value = '1';
            }
            if (fileInput) {
                fileInput.value = '';
            }
        });
    });

    // Handle file input changes
    ['og_image', 'twitter_image'].forEach(imageType => {
        const fileInput = document.getElementById('metadata[' + imageType + ']');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const previewContainer = document.getElementById(imageType + '_preview_container');
                const hiddenInput = document.getElementById('remove_' + imageType + '_image_hidden_input');

                if (this.files.length > 0) {
                    if (previewContainer) {
                        previewContainer.style.display = 'none';
                    }
                    if (hiddenInput) {
                        hiddenInput.value = '0';
                    }
                }
            });
        }
    });
});
</script>
@endpush
