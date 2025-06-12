<div class="tab-pane fade" id="metadata-tab" role="tabpanel" aria-labelledby="metadata-tab-button">
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- Slug -->
                    <div class="col-md-12 mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug"
                            value="{{ old('slug', isset($model) ? $model->slug : '') }}">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Meta Title -->
                    <div class="col-md-6 mb-3">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" class="form-control" id="meta_title" name="metadata[meta_title]"
                            value="{{ old('metadata.meta_title', isset($model) ? $model->metadata?->meta_title : '') }}">
                        @error('metadata.meta_title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Meta Keywords -->
                    <div class="col-md-6 mb-3">
                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                        <input type="text" class="form-control" id="meta_keywords" name="metadata[meta_keywords]"
                            value="{{ old('metadata.meta_keywords', isset($model) ? $model->metadata?->meta_keywords : '') }}">
                        <small class="text-muted">Separate keywords with commas</small>
                        @error('metadata.meta_keywords')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Meta Description -->
                    <div class="col-12 mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control" id="meta_description" name="metadata[meta_description]" rows="3">{{ old('metadata.meta_description', isset($model) ? $model->metadata?->meta_description : '') }}</textarea>
                        @error('metadata.meta_description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Open Graph Section -->
                    <div class="col-12 mb-3">
                        <h5 class="border-bottom pb-2">Open Graph Settings</h5>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="og_title" class="form-label">OG Title</label>
                        <input type="text" class="form-control" id="og_title" name="metadata[og_title]"
                            value="{{ old('metadata.og_title', isset($model) ? $model->metadata?->og_title : '') }}">
                        @error('metadata.og_title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="og_image" class="form-label">OG Image</label>
                        <input type="file" class="form-control @error('metadata.og_image') is-invalid @enderror" id="og_image_input" name="metadata[og_image]">
                        @error('metadata.og_image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @if(isset($model) && $model && $model->metadata?->og_image)
                            <div class="mt-2" id="og_image_preview_container">
                                <img src="{{ asset('storage/' . $model->metadata->og_image) }}" alt="Current OG Image" class="img-thumbnail" width="200">
                                <button type="button" class="btn btn-danger btn-sm mt-1 remove-image-button" data-image-type="og">Remove OG Image</button>
                            </div>
                            <input type="hidden" name="metadata[remove_og_image]" id="remove_og_image_hidden_input" value="0">
                        @else
                            <input type="hidden" name="metadata[remove_og_image]" value="0">
                        @endif
                    </div>

                    <div class="col-12 mb-3">
                        <label for="og_description" class="form-label">OG Description</label>
                        <textarea class="form-control" id="og_description" name="metadata[og_description]" rows="3">{{ old('metadata.og_description', isset($model) ? $model->metadata?->og_description : '') }}</textarea>
                        @error('metadata.og_description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Twitter Card Section -->
                    <div class="col-12 mb-3">
                        <h5 class="border-bottom pb-2">Twitter Card Settings</h5>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="twitter_card" class="form-label">Card Type</label>
                        <select class="form-control" id="twitter_card" name="metadata[twitter_card]">
                            <option value="summary" {{ old('metadata.twitter_card', isset($model) ? $model->metadata?->twitter_card : '') === 'summary' ? 'selected' : '' }}>Summary</option>
                            <option value="summary_large_image" {{ old('metadata.twitter_card', isset($model) ? $model->metadata?->twitter_card : '') === 'summary_large_image' ? 'selected' : '' }}>Summary Large Image</option>
                        </select>
                        @error('metadata.twitter_card')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="twitter_image" class="form-label">Twitter Image</label>
                        <input type="file" class="form-control @error('metadata.twitter_image') is-invalid @enderror" id="twitter_image_input" name="metadata[twitter_image]">
                        @error('metadata.twitter_image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @if(isset($model) && $model && $model->metadata?->twitter_image)
                            <div class="mt-2" id="twitter_image_preview_container">
                                <img src="{{ asset('storage/' . $model->metadata->twitter_image) }}" alt="Current Twitter Image" class="img-thumbnail" width="200">
                                <button type="button" class="btn btn-danger btn-sm mt-1 remove-image-button" data-image-type="twitter">Remove Twitter Image</button>
                            </div>
                            <input type="hidden" name="metadata[remove_twitter_image]" id="remove_twitter_image_hidden_input" value="0">
                        @else
                             <input type="hidden" name="metadata[remove_twitter_image]" value="0">
                        @endif
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="twitter_title" class="form-label">Twitter Title</label>
                        <input type="text" class="form-control" id="twitter_title" name="metadata[twitter_title]"
                            value="{{ old('metadata.twitter_title', isset($model) ? $model->metadata?->twitter_title : '') }}">
                        @error('metadata.twitter_title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label for="twitter_description" class="form-label">Twitter Description</label>
                        <textarea class="form-control" id="twitter_description" name="metadata[twitter_description]" rows="3">{{ old('metadata.twitter_description', isset($model) ? $model->metadata?->twitter_description : '') }}</textarea>
                        @error('metadata.twitter_description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.remove-image-button').forEach(button => {
            button.addEventListener('click', function() {
                const imageType = this.dataset.imageType; // 'og' or 'twitter'
                const previewContainer = document.getElementById(imageType + '_image_preview_container');
                const hiddenInput = document.getElementById('remove_' + imageType + '_image_hidden_input');

                if (confirm('Are you sure you want to remove this image?')) {
                    // Only make AJAX request if we have a model ID (edit scenario)
                    @if(isset($model) && $model && $model->id)
                        // Determine the route based on model type
                        @if(isset($model) && method_exists($model, 'getTable'))
                            @if($model->getTable() === 'rental_resales')
                                fetch(`/admin/rental_resale/{{ $model->id }}/delete-${imageType}-image`, {
                            @elseif($model->getTable() === 'developers')
                                fetch(`/admin/developer/{{ $model->id }}/delete-${imageType}-image`, {
                            @elseif($model->getTable() === 'blog_posts')
                                fetch(`/admin/blogposts/{{ $model->id }}/delete-${imageType}-image`, {
                            @elseif($model->getTable() === 'offplans')
                                fetch(`/admin/offplan/{{ $model->id }}/delete-${imageType}-image`, {
                            @else
                                // Default fallback
                                fetch(`/admin/developer/{{ $model->id }}/delete-${imageType}-image`, {
                            @endif
                        @else
                            // Default fallback if getTable method doesn't exist
                            fetch(`/admin/developer/{{ $model->id }}/delete-${imageType}-image`, {
                        @endif
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (previewContainer) {
                                previewContainer.style.display = 'none';
                            }
                            if (hiddenInput) {
                                hiddenInput.value = '1';
                            }
                            alert('Image removed successfully.');
                        } else {
                            alert(data.message || 'Failed to remove image.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error removing image.');
                    });
                    @else
                    // For create scenario, just hide the preview and set the hidden input
                    if (previewContainer) {
                        previewContainer.style.display = 'none';
                    }
                    if (hiddenInput) {
                        hiddenInput.value = '1';
                    }
                    alert('Image will be removed when form is submitted.');
                    @endif
                }
            });
        });
    });
</script>
