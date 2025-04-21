@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>System Settings</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" id="settingsForm">
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
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ Storage::url($settings['header']['logo']) }}" alt="Current Logo" class="img-thumbnail" style="max-height: 100px">
                                            <form action="{{ route('admin.settings.delete-logo') }}" method="POST" class="position-absolute top-0 end-0 m-1">
                                                @csrf
                                                <input type="hidden" name="type" value="header">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" class="form-control @error('header_logo') is-invalid @enderror" name="header_logo" accept="image/*,.svg">
                                @error('header_logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                <input type="text" class="form-control @error('meta.title') is-invalid @enderror"
                                       name="meta[title]"
                                       value="{{ old('meta.title', $settings['meta']['title'] ?? '') }}"
                                       placeholder="Default page title">
                                @error('meta.title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description</label>
                                <textarea class="form-control editor @error('meta.description') is-invalid @enderror"
                                          name="meta[description]"
                                          rows="3"
                                          placeholder="Default meta description">{{ old('meta.description', $settings['meta']['description'] ?? '') }}</textarea>
                                @error('meta.description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control @error('meta.keywords') is-invalid @enderror"
                                       name="meta[keywords]"
                                       value="{{ old('meta.keywords', $settings['meta']['keywords'] ?? '') }}"
                                       placeholder="keyword1, keyword2, keyword3">
                                @error('meta.keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Separate keywords with commas</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="mb-3">Open Graph Tags</h5>
                            <div class="mb-3">
                                <label class="form-label">OG Title</label>
                                <input type="text" class="form-control @error('meta.og_title') is-invalid @enderror"
                                       name="meta[og_title]"
                                       value="{{ old('meta.og_title', $settings['meta']['og_title'] ?? '') }}"
                                       placeholder="Open Graph title">
                                @error('meta.og_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">OG Description</label>
                                <textarea class="form-control editor @error('meta.og_description') is-invalid @enderror"
                                          name="meta[og_description]"
                                          rows="3"
                                          placeholder="Open Graph description">{{ old('meta.og_description', $settings['meta']['og_description'] ?? '') }}</textarea>
                                @error('meta.og_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">OG Image</label>
                                <div class="mb-2">
                                    @if(isset($settings['meta']['og_image']))
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ asset('storage/' . $settings['meta']['og_image']) }}" alt="OG Image" class="img-thumbnail" style="max-height: 100px">
                                            <form action="{{ route('admin.settings.delete-meta-image') }}" method="POST" class="position-absolute top-0 end-0 m-1">
                                                @csrf
                                                <input type="hidden" name="type" value="og">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" class="form-control @error('meta.og_image') is-invalid @enderror" name="meta[og_image]" accept="image/*">
                                @error('meta.og_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Recommended size: 1200x630px</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="mb-3">Twitter Card Tags</h5>
                            <div class="mb-3">
                                <label class="form-label">Twitter Card Type</label>
                                <select class="form-control @error('meta.twitter_card') is-invalid @enderror" name="meta[twitter_card]">
                                    <option value="summary" {{ (old('meta.twitter_card', $settings['meta']['twitter_card'] ?? '') == 'summary') ? 'selected' : '' }}>Summary</option>
                                    <option value="summary_large_image" {{ (old('meta.twitter_card', $settings['meta']['twitter_card'] ?? '') == 'summary_large_image') ? 'selected' : '' }}>Summary Large Image</option>
                                </select>
                                @error('meta.twitter_card')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Twitter Title</label>
                                <input type="text" class="form-control @error('meta.twitter_title') is-invalid @enderror"
                                       name="meta[twitter_title]"
                                       value="{{ old('meta.twitter_title', $settings['meta']['twitter_title'] ?? '') }}"
                                       placeholder="Twitter card title">
                                @error('meta.twitter_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Twitter Description</label>
                                <textarea class="form-control editor @error('meta.twitter_description') is-invalid @enderror"
                                          name="meta[twitter_description]"
                                          rows="3"
                                          placeholder="Twitter card description">{{ old('meta.twitter_description', $settings['meta']['twitter_description'] ?? '') }}</textarea>
                                @error('meta.twitter_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Twitter Image</label>
                                <div class="mb-2">
                                    @if(isset($settings['meta']['twitter_image']))
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ asset('storage/' . $settings['meta']['twitter_image']) }}" alt="Twitter Image" class="img-thumbnail" style="max-height: 100px">
                                            <form action="{{ route('admin.settings.delete-meta-image') }}" method="POST" class="position-absolute top-0 end-0 m-1">
                                                @csrf
                                                <input type="hidden" name="type" value="twitter">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" class="form-control @error('meta.twitter_image') is-invalid @enderror" name="meta[twitter_image]" accept="image/*">
                                @error('meta.twitter_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                <label class="form-label">Footer Logo</label>
                                <div class="mb-2">
                                    @if(isset($settings['footer']['logo']))
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ asset($settings['footer']['logo']) }}" alt="Current Footer Logo" class="img-thumbnail" style="max-height: 100px">
                                            <form action="{{ route('admin.settings.delete-logo') }}" method="POST" class="position-absolute top-0 end-0 m-1">
                                                @csrf
                                                <input type="hidden" name="type" value="footer">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" class="form-control @error('footer_logo') is-invalid @enderror" name="footer_logo" accept="image/*,.svg">
                                @error('footer_logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Recommended size: 200x60px. Supported formats: JPEG, PNG, JPG, GIF, SVG</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control editor @error('footer.description') is-invalid @enderror" name="footer[description]">{{ old('footer.description', $settings['footer']['description'] ?? '') }}</textarea>
                                @error('footer.description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Copyright Text</label>
                                <input type="text" class="form-control @error('footer.copyright') is-invalid @enderror" name="footer[copyright]" value="{{ old('footer.copyright', $settings['footer']['copyright'] ?? '') }}">
                                @error('footer.copyright')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="mb-4 border-bottom pb-3">
                            <h5 class="mb-3">Contact Information</h5>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control @error('footer.contact.address') is-invalid @enderror" name="footer[contact][address]" value="{{ old('footer.contact.address', $settings['footer']['contact']['address'] ?? '') }}">
                                @error('footer.contact.address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control @error('footer.contact.phone') is-invalid @enderror" name="footer[contact][phone]" value="{{ old('footer.contact.phone', $settings['footer']['contact']['phone'] ?? '') }}">
                                @error('footer.contact.phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control @error('footer.contact.email') is-invalid @enderror" name="footer[contact][email]" value="{{ old('footer.contact.email', $settings['footer']['contact']['email'] ?? '') }}">
                                @error('footer.contact.email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Working Hours</label>
                                <input type="text" class="form-control @error('footer.contact.working_hours') is-invalid @enderror" name="footer[contact][working_hours]" value="{{ old('footer.contact.working_hours', $settings['footer']['contact']['working_hours'] ?? '') }}">
                                @error('footer.contact.working_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Newsletter Settings -->
                        <div class="mb-4 border-bottom pb-3">
                            <h5 class="mb-3">Newsletter</h5>
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control @error('footer.newsletter.title') is-invalid @enderror" name="footer[newsletter][title]" value="{{ old('footer.newsletter.title', $settings['footer']['newsletter']['title'] ?? '') }}">
                                @error('footer.newsletter.title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control editor @error('footer.newsletter.description') is-invalid @enderror" name="footer[newsletter][description]">{{ old('footer.newsletter.description', $settings['footer']['newsletter']['description'] ?? '') }}</textarea>
                                @error('footer.newsletter.description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Placeholder Text</label>
                                <input type="text" class="form-control @error('footer.newsletter.placeholder') is-invalid @enderror" name="footer[newsletter][placeholder]" value="{{ old('footer.newsletter.placeholder', $settings['footer']['newsletter']['placeholder'] ?? '') }}">
                                @error('footer.newsletter.placeholder')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Button Text</label>
                                <input type="text" class="form-control @error('footer.newsletter.button_text') is-invalid @enderror" name="footer[newsletter][button_text]" value="{{ old('footer.newsletter.button_text', $settings['footer']['newsletter']['button_text'] ?? '') }}">
                                @error('footer.newsletter.button_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                            <input type="text" class="form-control @error("footer.legal_links.$index.title") is-invalid @enderror"
                                                   name="footer[legal_links][{{ $index }}][title]"
                                                   value="{{ old("footer.legal_links.$index.title", $link['title'] ?? '') }}">
                                            @error("footer.legal_links.$index.title")
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>URL</label>
                                            <input type="text" class="form-control @error("footer.legal_links.$index.url") is-invalid @enderror"
                                                   name="footer[legal_links][{{ $index }}][url]"
                                                   value="{{ old("footer.legal_links.$index.url", $link['url'] ?? '') }}"
                                                   placeholder="terms-of-service">
                                            @error("footer.legal_links.$index.url")
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
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
                            <input type="url" class="form-control @error("social.$platform") is-invalid @enderror"
                                   name="social[{{ $platform }}]"
                                   value="{{ old("social.$platform", $url) }}"
                                   placeholder="https://">
                            @error("social.$platform")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3" id="saveSettings">Save Settings</button>
    </form>
</div>

<script>
document.getElementById('saveSettings').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('settingsForm').submit();
});
</script>
@endsection
