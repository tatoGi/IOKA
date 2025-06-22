@props(['model'])

@php
    // Determine the model type
    $modelType = '';
    $modelId = $model->id ?? null;
    $isNewModel = !isset($model->id);
    
    if ($model instanceof \App\Models\BlogPost) {
        $modelType = 'blogpost';
    } elseif ($model instanceof \App\Models\Developer) {
        $modelType = 'developer';
    } elseif ($model instanceof \App\Models\Offplan) {
        $modelType = 'offplan';
    } elseif ($model instanceof \App\Models\PostType) {
        $modelType = 'rental_resale';
    }
@endphp

<div class="metadata-form" data-model-type="{{ $modelType }}" data-model-id="{{ $modelId }}" data-is-new="{{ $isNewModel ? 'true' : 'false' }}">
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
        id="og_image" name="og_image" accept="image/*">
    @error('metadata.og_image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @if($model->metadata?->og_image)
        <div class="mt-2" id="og_image_preview">
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
        id="twitter_image" name="twitter_image" accept="image/*">
    @error('metadata.twitter_image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @if($model->metadata?->twitter_image)
        <div class="mt-2" id="twitter_image_preview">
            <img src="{{ asset('storage/' . $model->metadata->twitter_image) }}" class="img-thumbnail" width="200">
            <button type="button" class="btn btn-danger mt-2 remove-twitter-image-btn">Remove Twitter Image</button>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded for metadata form');
    
    // Log form attributes for debugging
    const metadataForm = document.querySelector('.metadata-form');
    if (metadataForm) {
        console.log('Form data attributes:', {
            modelType: metadataForm.dataset.modelType,
            modelId: metadataForm.dataset.modelId,
            isNew: metadataForm.dataset.isNew
        });
    } else {
        console.error('Metadata form not found!');
    }
    
    // Handle OG image removal
    const removeOgImageBtn = document.querySelector('.remove-og-image-btn');
    if (removeOgImageBtn) {
        removeOgImageBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove the OG image?')) {
                // Get the model type and ID from data attributes on the form
                const metadataForm = document.querySelector('.metadata-form');
                if (!metadataForm) {
                    console.error('Metadata form not found!');
                    return;
                }
                
                const modelType = metadataForm.dataset.modelType;
                const modelId = metadataForm.dataset.modelId;
                const isNew = metadataForm.dataset.isNew === 'true';
                
                console.log('OG Image removal - Data:', { modelType, modelId, isNew });
                
                if (isNew) {
                    // For new models (create form), just hide the preview and clear the input
                    const previewContainer = this.closest('.mt-2');
                    if (previewContainer) {
                        previewContainer.style.display = 'none';
                    }
                    const ogImageInput = document.getElementById('og_image');
                    if (ogImageInput) {
                        ogImageInput.value = '';
                    }
                    return;
                }
                
                // For existing models (edit form), call the API
                // Build the appropriate route based on model type
                let route;
                if (modelType === 'blogpost') {
                    route = `/admin/blogposts/${modelId}/delete-og-image`;
                } else if (modelType === 'developer') {
                    route = `/admin/developer/${modelId}/delete-og-image`;
                } else if (modelType === 'offplan') {
                    route = `/ioka_admin/offplan/${modelId}/delete-og-image`;
                } else if (modelType === 'rental_resale') {
                    route = `/admin/rental_resale/${modelId}/delete-og-image`;
                }
                
                if (route) {
                    console.log('Sending OG image DELETE request to:', route);
                    
                    // Create a form for the DELETE request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = route;
                    form.style.display = 'none';
                    
                    // Add CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);
                    
                    // Add method spoofing for DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        });
    }
    
    // Handle Twitter image removal
    const removeTwitterImageBtn = document.querySelector('.remove-twitter-image-btn');
    if (removeTwitterImageBtn) {
        removeTwitterImageBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove the Twitter image?')) {
                // Get the model type and ID from data attributes on the form
                const metadataForm = document.querySelector('.metadata-form');
                if (!metadataForm) {
                    console.error('Metadata form not found!');
                    return;
                }
                
                const modelType = metadataForm.dataset.modelType;
                const modelId = metadataForm.dataset.modelId;
                const isNew = metadataForm.dataset.isNew === 'true';
                
                console.log('Twitter Image removal - Data:', { modelType, modelId, isNew });
                
                if (isNew) {
                    // For new models (create form), just hide the preview and clear the input
                    const previewContainer = this.closest('.mt-2');
                    if (previewContainer) {
                        previewContainer.style.display = 'none';
                    }
                    const twitterImageInput = document.getElementById('twitter_image');
                    if (twitterImageInput) {
                        twitterImageInput.value = '';
                    }
                    return;
                }
                
                // For existing models (edit form), call the API
                // Build the appropriate route based on model type
                let route;
                if (modelType === 'blogpost') {
                    route = `/admin/blogposts/${modelId}/delete-twitter-image`;
                } else if (modelType === 'developer') {
                    route = `/admin/developer/${modelId}/delete-twitter-image`;
                } else if (modelType === 'offplan') {
                    route = `/ioka_admin/offplan/${modelId}/delete-twitter-image`;
                } else if (modelType === 'rental_resale') {
                    route = `/admin/rental_resale/${modelId}/delete-twitter-image`;
                }
                
                if (route) {
                    console.log('Sending Twitter image DELETE request to:', route);
                    
                    // Create a form for the DELETE request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = route;
                    form.style.display = 'none';
                    
                    // Add CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);
                    
                    // Add method spoofing for DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        });
    }

    // Handle file input changes
    ['og_image', 'twitter_image'].forEach(imageType => {
        const fileInput = document.getElementById(imageType);
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                // If we're uploading a new file, hide any existing preview
                const previewElement = document.getElementById(imageType + '_preview');
                if (previewElement && this.files.length > 0) {
                    previewElement.style.display = 'none';
                }
            });
        }
    });
});
</script>
@endpush
