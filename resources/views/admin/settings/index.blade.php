@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>System Settings</h1>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#header">
                    Header
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#meta">
                    Meta Tags
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#footer">
                    Footer
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#social">
                    Social Links
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Header Tab -->
            <div class="tab-pane fade show active" id="header">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="mb-3">Logo Settings</h5>
                            <div class="mb-3">
                                <label class="form-label">Header Logo</label>
                                <div class="mb-2">
                                    @if(isset($settings['header']['logo']))
                                        <img src="{{ asset($settings['header']['logo']) }}" alt="Current Logo" class="img-thumbnail" style="max-height: 100px">
                                    @endif
                                </div>
                                <input type="file" class="form-control" name="header[logo]" accept="image/*">
                                <small class="text-muted">Recommended size: 200x60px. Supported formats: JPEG, PNG, JPG, GIF, SVG</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meta Tags Tab -->
            <div class="tab-pane fade" id="meta">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="mb-3">Global Meta Tags</h5>
                            <div class="mb-3">
                                <label class="form-label">Meta Title</label>
                                <input type="text" class="form-control"
                                       name="meta[title]"
                                       value="{{ old('meta.title', $settings['meta']['title'] ?? '') }}"
                                       placeholder="Default page title">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description</label>
                                <textarea class="form-control"
                                          name="meta[description]"
                                          rows="3"
                                          placeholder="Default meta description">{{ old('meta.description', $settings['meta']['description'] ?? '') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control"
                                       name="meta[keywords]"
                                       value="{{ old('meta.keywords', $settings['meta']['keywords'] ?? '') }}"
                                       placeholder="keyword1, keyword2, keyword3">
                                <small class="text-muted">Separate keywords with commas</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="mb-3">Open Graph Tags</h5>
                            <div class="mb-3">
                                <label class="form-label">OG Title</label>
                                <input type="text" class="form-control"
                                       name="meta[og_title]"
                                       value="{{ old('meta.og_title', $settings['meta']['og_title'] ?? '') }}"
                                       placeholder="Open Graph title">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">OG Description</label>
                                <textarea class="form-control"
                                          name="meta[og_description]"
                                          rows="3"
                                          placeholder="Open Graph description">{{ old('meta.og_description', $settings['meta']['og_description'] ?? '') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">OG Image</label>
                                <div class="mb-2">
                                    @if(isset($settings['meta']['og_image']))
                                        <img src="{{ asset($settings['meta']['og_image']) }}" alt="OG Image" class="img-thumbnail" style="max-height: 100px">
                                    @endif
                                </div>
                                <input type="file" class="form-control" name="meta[og_image]" accept="image/*">
                                <small class="text-muted">Recommended size: 1200x630px</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="mb-3">Twitter Card Tags</h5>
                            <div class="mb-3">
                                <label class="form-label">Twitter Card Type</label>
                                <select class="form-control" name="meta[twitter_card]">
                                    <option value="summary" {{ (old('meta.twitter_card', $settings['meta']['twitter_card'] ?? '') == 'summary') ? 'selected' : '' }}>Summary</option>
                                    <option value="summary_large_image" {{ (old('meta.twitter_card', $settings['meta']['twitter_card'] ?? '') == 'summary_large_image') ? 'selected' : '' }}>Summary Large Image</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Twitter Title</label>
                                <input type="text" class="form-control"
                                       name="meta[twitter_title]"
                                       value="{{ old('meta.twitter_title', $settings['meta']['twitter_title'] ?? '') }}"
                                       placeholder="Twitter card title">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Twitter Description</label>
                                <textarea class="form-control"
                                          name="meta[twitter_description]"
                                          rows="3"
                                          placeholder="Twitter card description">{{ old('meta.twitter_description', $settings['meta']['twitter_description'] ?? '') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Twitter Image</label>
                                <div class="mb-2">
                                    @if(isset($settings['meta']['twitter_image']))
                                        <img src="{{ asset($settings['meta']['twitter_image']) }}" alt="Twitter Image" class="img-thumbnail" style="max-height: 100px">
                                    @endif
                                </div>
                                <input type="file" class="form-control" name="meta[twitter_image]" accept="image/*">
                                <small class="text-muted">Recommended size: 1200x600px</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Tab -->
            <div class="tab-pane fade" id="footer">
                <div class="card">
                    <div class="card-body">
                        <!-- General Footer Settings -->
                        <div class="mb-4">
                            <h5 class="mb-3">General Settings</h5>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="footer[description]">{{ old('footer.description', $settings['footer']['description'] ?? '') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Copyright Text</label>
                                <input type="text" class="form-control" name="footer[copyright]" value="{{ old('footer.copyright', $settings['footer']['copyright'] ?? '') }}">
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="mb-4 border-bottom pb-3">
                            <h5 class="mb-3">Contact Information</h5>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="footer[contact][address]" value="{{ old('footer.contact.address', $settings['footer']['contact']['address'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="footer[contact][phone]" value="{{ old('footer.contact.phone', $settings['footer']['contact']['phone'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="footer[contact][email]" value="{{ old('footer.contact.email', $settings['footer']['contact']['email'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Working Hours</label>
                                <input type="text" class="form-control" name="footer[contact][working_hours]" value="{{ old('footer.contact.working_hours', $settings['footer']['contact']['working_hours'] ?? '') }}">
                            </div>
                        </div>

                        <!-- Newsletter Settings -->
                        <div class="mb-4 border-bottom pb-3">
                            <h5 class="mb-3">Newsletter</h5>
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" name="footer[newsletter][title]" value="{{ old('footer.newsletter.title', $settings['footer']['newsletter']['title'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="footer[newsletter][description]">{{ old('footer.newsletter.description', $settings['footer']['newsletter']['description'] ?? '') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Placeholder Text</label>
                                <input type="text" class="form-control" name="footer[newsletter][placeholder]" value="{{ old('footer.newsletter.placeholder', $settings['footer']['newsletter']['placeholder'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Button Text</label>
                                <input type="text" class="form-control" name="footer[newsletter][button_text]" value="{{ old('footer.newsletter.button_text', $settings['footer']['newsletter']['button_text'] ?? '') }}">
                            </div>
                        </div>

                        <!-- Legal Links -->
                        <div class="mb-4">
                            <h5 class="mb-3">Legal Links</h5>
                            <div id="legal-links-container">
                                @foreach(($settings['footer']['legal_links'] ?? []) as $index => $link)
                                <div class="link-item mb-3 p-3 border rounded position-relative">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 remove-link"
                                            data-index="{{ $index }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label>Title</label>
                                            <input type="text" class="form-control"
                                                   name="footer[legal_links][{{ $index }}][title]"
                                                   value="{{ old("footer.legal_links.$index.title", $link['title'] ?? '') }}">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>URL</label>
                                            <input type="text" class="form-control"
                                                   name="footer[legal_links][{{ $index }}][url]"
                                                   value="{{ old("footer.legal_links.$index.url", $link['url'] ?? '') }}"
                                                   placeholder="terms-of-service">
                                            <small class="text-muted">Enter URL as slug (e.g., terms-of-service)</small>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-sm btn-secondary mt-2" id="add-legal-link">
                                <i class="fas fa-plus"></i> Add Legal Link
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Links Tab -->
            <div class="tab-pane fade" id="social">
                <div class="card">
                    <div class="card-body">
                        @foreach($settings['social'] ?? [] as $platform => $url)
                        <div class="mb-3">
                            <label class="form-label">{{ ucfirst($platform) }} URL</label>
                            <input type="url" class="form-control"
                                   name="social[{{ $platform }}]"
                                   value="{{ old("social.$platform", $url) }}"
                                   placeholder="https://">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Save Settings</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add new legal link
    document.getElementById('add-legal-link').addEventListener('click', function() {
        const container = document.getElementById('legal-links-container');
        const index = container.children.length;

        const div = document.createElement('div');
        div.className = 'link-item mb-3 p-3 border rounded position-relative';
        div.innerHTML = `
            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 remove-link"
                    data-index="${index}">
                <i class="fas fa-times"></i>
            </button>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label>Title</label>
                    <input type="text" class="form-control"
                           name="footer[legal_links][${index}][title]">
                </div>
                <div class="col-md-6 mb-2">
                    <label>URL</label>
                    <input type="text" class="form-control"
                           name="footer[legal_links][${index}][url]">
                </div>
            </div>
        `;

        container.appendChild(div);

        // Add event listener to the new remove button
        div.querySelector('.remove-link').addEventListener('click', function() {
            this.closest('.link-item').remove();
            // Re-index remaining items
            const items = container.querySelectorAll('.link-item');
            items.forEach((item, newIndex) => {
                const titleInput = item.querySelector('input[name*="[title]"]');
                const urlInput = item.querySelector('input[name*="[url]"]');
                if (titleInput && urlInput) {
                    titleInput.name = `footer[legal_links][${newIndex}][title]`;
                    urlInput.name = `footer[legal_links][${newIndex}][url]`;
                }
            });
        });
    });

    // Add event listeners to existing remove buttons
    document.querySelectorAll('.remove-link').forEach(button => {
        button.addEventListener('click', function() {
            const container = document.getElementById('legal-links-container');
            this.closest('.link-item').remove();

            // Re-index remaining items
            const items = container.querySelectorAll('.link-item');
            items.forEach((item, newIndex) => {
                const titleInput = item.querySelector('input[name*="[title]"]');
                const urlInput = item.querySelector('input[name*="[url]"]');
                if (titleInput && urlInput) {
                    titleInput.name = `footer[legal_links][${newIndex}][title]`;
                    urlInput.name = `footer[legal_links][${newIndex}][url]`;
                }
            });
        });
    });
});
</script>
@endsection
