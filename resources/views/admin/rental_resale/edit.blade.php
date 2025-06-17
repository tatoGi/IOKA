@extends('admin.layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Edit Rental Resale Post</h1>
        <form id="rental-resale-form" action="{{ route('admin.postypes.rental_resale.update', $rentalResale->id) }}" method="POST" enctype="multipart/form-data" class="shadow p-4 rounded bg-light">
            @csrf
            @method('PUT')
            <input type="hidden" id="postId" value="{{ $rentalResale->id }}">
            <input type="hidden" name="alt_texts[gallery_images]" id="gallery-alt-texts-input" value="{{ is_string($rentalResale->alt_texts) ? $rentalResale->alt_texts : json_encode($rentalResale->alt_texts) }}">
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
                    <!-- Basic Info Fields -->
                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="tags" name="tags[]" multiple required>
                            <option value="6" {{ in_array(6, (array) $rentalResale->tags) ? 'selected' : '' }}>Resale</option>
                            <option value="5" {{ in_array(5, (array) $rentalResale->tags) ? 'selected' : '' }}>Rental</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (in dollars)</label>
                        <input type="number" step="0.01" class="form-control amount" id="amount" name="amount"
                            value="{{ $rentalResale->amount->amount }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount_dirhams" class="form-label">Amount (in Dirhams)</label>
                        <input type="number" step="0.01" class="form-control amount_dirhams" id="amount_dirhams" name="amount_dirhams"
                            value="{{ $rentalResale->amount->amount_dirhams }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="property_type" class="form-label">Property Type<span class="text-danger">*</span></label>
                        <select class="form-control select2" id="property_type" name="property_type" required>
                            <option value="Villa" {{ $rentalResale->property_type == 'Villa' ? 'selected' : '' }}>Villa</option>
                            <option value="Townhouse" {{ $rentalResale->property_type == 'Townhouse' ? 'selected' : '' }}>Townhouse</option>
                            <option value="Apartment" {{ $rentalResale->property_type == 'Apartment' ? 'selected' : '' }}>Apartment</option>
                            <option value="Land" {{ $rentalResale->property_type == 'Land' ? 'selected' : '' }}>Land</option>
                            <option value="Full Building" {{ $rentalResale->property_type == 'Full Building' ? 'selected' : '' }}>Full Building</option>
                            <option value="Commercial" {{ $rentalResale->property_type == 'Commercial' ? 'selected' : '' }}>Commercial</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $rentalResale->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Subtitle<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{ $rentalResale->subtitle }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="slug">Slug<span class="text-danger">*</span></label>
                        <input type="text" name="slug" id="slug" class="form-control" value="{{ $rentalResale->slug }}" required>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="bathroom" class="form-label">Bathroom<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="bathroom" name="bathroom"
                                        value="{{ $rentalResale->bathroom }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="bedroom" class="form-label">Bedroom<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="bedroom" name="bedroom"
                                        value="{{ $rentalResale->bedroom }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="sq_ft" class="form-label">SQ Ft<span class="text-danger">*</span></label>
                                    <input type="number" step="0.1" class="form-control" id="sq_ft" name="sq_ft"
                                        value="{{ $rentalResale->sq_ft }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="garage" class="form-label">Garage<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="garage" name="garage"
                                        value="{{ $rentalResale->garage }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description<span class="text-danger">*</span></label>
                        <textarea class="form-control editor" id="description" name="description" required>{{ $rentalResale->description }}</textarea>
                    </div>

                    <!-- Details, Amenities, and Addresses -->
                    <div class="container">
                        <div class="row">
                            <!-- Details Repeater -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="details" class="form-label">Details<span class="text-danger">*</span></label>
                                     <div class="details-repeater">
                                        <div data-repeater-list="details">
                                            @php
                                                $details = $rentalResale->details;
                                                if (is_string($details)) {
                                                    $details = is_array($details) ? $details : (json_decode($details, true) ?: []);
                                                }
                                                if (!is_array($details)) {
                                                    $details = [];
                                                }

                                                $normalized = [];
                                                foreach ($details as $item) {
                                                    if (is_array($item) && isset($item['title']) && isset($item['info'])) {
                                                        $normalized[] = [
                                                            'title' => is_scalar($item['title']) ? $item['title'] : '',
                                                            'info' => is_scalar($item['info']) ? $item['info'] : ''
                                                        ];
                                                    }
                                                }

                                                $detailsData = old('details', $normalized);
                                                if (empty($detailsData)) {
                                                    $detailsData = [['title' => '', 'info' => '']];
                                                }
                                            @endphp
                                            @foreach ($detailsData as $detail)
                                                <div data-repeater-item class="repeater-item mb-2">
                                                    <input type="text" name="title" class="form-control mb-2" value="{{ $detail['title'] ?? '' }}" placeholder="Title" required>
                                                    <input type="text" name="info" class="form-control mb-2" value="{{ $detail['info'] ?? '' }}" placeholder="Information" required>
                                                    <button type="button" class="btn btn-danger" data-repeater-delete>
                                                        <i class="fas fa-trash-alt"></i> Remove
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                         <button type="button" class="btn btn-primary mt-2" data-repeater-create>
                                             <i class="fas fa-plus"></i> Add Detail
                                         </button>
                                     </div>
                                </div>
                            </div>

                            <!-- Amenities Repeater -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="amenities" class="form-label">Amenities<span class="text-danger">*</span></label>
                                     <div class="amenities-repeater">
                                        <div data-repeater-list="amenities">
                                            @php
                                                $amenities = $rentalResale->amenities;
                                                if (is_string($amenities)) {
                                                    $amenities = is_array($amenities) ? $amenities : json_decode($amenities, true);
                                                }
                                                // If it's an array of strings, transform it.
                                                if (!empty($amenities) && is_array($amenities) && !is_array(current($amenities))) {
                                                    $amenities = array_map(fn($v) => ['amenity' => $v], $amenities);
                                                }
                                                $amenitiesData = old('amenities', $amenities);
                                                if (empty($amenitiesData)) {
                                                    $amenitiesData = [['amenity' => '']];
                                                }
                                            @endphp
                                            @foreach ($amenitiesData as $amenity)
                                                <div data-repeater-item class="repeater-item mb-2">
                                                    <input type="text" name="amenity" class="form-control mb-2" value="{{ $amenity['amenity'] ?? '' }}" placeholder="Amenity" required>
                                                    <button type="button" class="btn btn-danger" data-repeater-delete>
                                                        <i class="fas fa-trash-alt"></i> Remove
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                         <button type="button" class="btn btn-primary mt-2" data-repeater-create>
                                             <i class="fas fa-plus"></i> Add Amenity
                                         </button>
                                     </div>
                                </div>
                            </div>

                            <!-- Addresses Repeater -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="addresses" class="form-label">Addresses<span class="text-danger">*</span></label>
                                     <div class="addresses-repeater">
                                        <div data-repeater-list="addresses">
                                            @php
                                                $addresses = $rentalResale->addresses;
                                                if (is_string($addresses)) {
                                                    $addresses = is_array($addresses) ? $addresses : json_decode($addresses, true);
                                                }
                                                // If it's an array of strings, transform it.
                                                if (!empty($addresses) && is_array($addresses) && !is_array(current($addresses))) {
                                                    $addresses = array_map(fn($v) => ['address' => $v], $addresses);
                                                }
                                                $addressesData = old('addresses', $addresses);
                                                if (empty($addressesData)) {
                                                    $addressesData = [['address' => '']];
                                                }
                                            @endphp
                                            @foreach ($addressesData as $address)
                                                <div data-repeater-item class="repeater-item mb-2">
                                                    <input type="text" name="address" class="form-control mb-2" value="{{ $address['address'] ?? '' }}" placeholder="Address" required>
                                                    <button type="button" class="btn btn-danger" data-repeater-delete>
                                                        <i class="fas fa-trash-alt"></i> Remove
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                         <button type="button" class="btn btn-primary mt-2" data-repeater-create>
                                             <i class="fas fa-plus"></i> Add Address
                                         </button>
                                     </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Agent Information -->
                    <div class="container">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="agent_title" class="form-label">Agent Title<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="agent_title" name="agent_title" value="{{ $rentalResale->agent_title }}" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="agent_status" class="form-label">Agent Status<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="agent_status" name="agent_status" value="{{ $rentalResale->agent_status }}" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="agent_languages" class="form-label">Agent Languages<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="agent_languages" name="agent_languages" value="{{ $rentalResale->agent_languages }}" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="agent_call" class="form-label">Agent Call<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="agent_call" name="agent_call" value="{{ $rentalResale->agent_call }}" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="agent_whatsapp" class="form-label">Agent WhatsApp<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="agent_whatsapp" name="agent_whatsapp" value="{{ $rentalResale->agent_whatsapp }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="agent_photo" class="form-label">Agent Photo</label>
                                    @if ($rentalResale->agent_photo)
                                        <div class="mb-2">
                                            @php
                                                $agentPhotos = is_array($rentalResale->agent_photo) ? $rentalResale->agent_photo : json_decode($rentalResale->agent_photo, true);
                                            @endphp
                                            @if(is_array($agentPhotos))
                                                @foreach($agentPhotos as $photo)
                                                    <div class="mb-2">
                                                        <img src="{{ asset('storage/' . $photo) }}" alt="agent_photo" style="max-width: 200px;">
                                                    </div>
                                                @endforeach
                                            @else
                                                <img src="{{ asset('storage/' . $rentalResale->agent_photo) }}" alt="agent_photo" style="max-width: 200px;">
                                            @endif
                                            <button type="button" class="btn btn-danger btn-sm" id="remove-agent-photo">Remove</button>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" id="agent_photo" name="agent_photo[]" multiple>
                                    <input type="text" class="form-control mt-2" name="alt_texts[agent_photo]" value="{{ (is_array($rentalResale->alt_texts) ? $rentalResale->alt_texts : json_decode($rentalResale->alt_texts, true))['agent_photo'] ?? '' }}" placeholder="Alt text for agent photo">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="languages" class="form-label">languages</label>
                                    <div class="languages-repeater">
                                        <div data-repeater-list="languages">
                                            @if(isset($rentalResale->languages))
                                                @foreach ((is_array($rentalResale->languages) ? $rentalResale->languages : json_decode($rentalResale->languages, true)) as $index => $language)
                                                    <div data-repeater-item class="repeater-item mb-2">
                                                        <input type="text" class="form-control mb-2" name="languages[{{ $index }}]" value="{{ is_array($language) ? implode(', ', $language) : $language }}" required>
                                                        <button type="button" class="btn btn-danger" data-repeater-delete>
                                                            <i class="fas fa-trash-alt"></i> Remove
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-primary mt-2" data-repeater-create>
                                            <i class="fas fa-plus"></i> Add languages
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Fields -->
                    <div class="mb-3">
                        <label for="location_link" class="form-label">Location Link<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="location_link" name="location_link" value="{{ $rentalResale->location_link }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="location_id" class="form-label">Location<span class="text-danger">*</span></label>
                        <select name="location_id[]" id="location_id" class="form-control select2" required>
                            <option value="">Select Location</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ $selectedLocations[0] == $location->id ? 'selected' : '' }}>
                                    {{ $location->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="qr_photo" class="form-label">QR Photo<span class="text-danger">*</span></label>
                        @if ($rentalResale->qr_photo)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $rentalResale->qr_photo) }}" alt="QR Photo" style="max-width: 200px;">
                                <button type="button" class="btn btn-danger btn-sm" id="remove-qr-photo">Remove</button>
                            </div>
                        @endif
                        <input type="file" class="form-control" id="qr_photo" name="qr_photo">
                    </div>
                    <div class="mb-3">
                        <label for="reference" class="form-label">Reference<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="reference" name="reference" value="{{ $rentalResale->reference }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="dld_permit_number" class="form-label">DLD Permit Number<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="dld_permit_number" name="dld_permit_number" value="{{ $rentalResale->dld_permit_number }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="top" class="form-label">Mark as Top Listing<span class="text-danger">*</span></label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="top" name="top" value="1" {{ $rentalResale->top ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_top">Check this box to mark the property as a top listing</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="gallery" class="form-label">Gallery</label>
                        <input type="file" class="form-control" id="gallery" name="gallery_images[]" multiple>
                        <div id="gallery-alt-texts" class="mt-2">
                            <!-- New gallery images alt text inputs will be added here dynamically -->
                        </div>
                        <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#galleryModal">Manage Gallery</button>
                    </div>
                </div>

               <!-- Meta Data Tab -->
               <div class="tab-pane" id="metadata-tab" role="tabpanel">
                <div class="mb-3">
                    <label for="metadata[meta_title]" class="form-label">Meta Title</label>
                    <input type="text" class="form-control @error('metadata.meta_title') is-invalid @enderror"
                        id="metadata[meta_title]" name="metadata[meta_title]"
                        value="{{ old('metadata.meta_title', $rentalResale->metadata?->meta_title) }}">
                    @error('metadata.meta_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="metadata[meta_description]" class="form-label">Meta Description</label>
                    <textarea class="form-control @error('metadata.meta_description') is-invalid @enderror"
                        id="metadata[meta_description]" name="metadata[meta_description]" rows="3"
                        >{{ old('metadata.meta_description', $rentalResale->metadata?->meta_description) }}</textarea>
                    @error('metadata.meta_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="metadata[meta_keywords]" class="form-label">Meta Keywords</label>
                    <input type="text" class="form-control @error('metadata.meta_keywords') is-invalid @enderror"
                        id="metadata[meta_keywords]" name="metadata[meta_keywords]"
                        value="{{ old('metadata.meta_keywords', $rentalResale->metadata?->meta_keywords) }}">
                    @error('metadata.meta_keywords')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <h4 class="mt-4">Open Graph</h4>
                <div class="mb-3">
                    <label for="metadata[og_title]" class="form-label">OG Title</label>
                    <input type="text" class="form-control @error('metadata.og_title') is-invalid @enderror"
                        id="metadata[og_title]" name="metadata[og_title]"
                        value="{{ old('metadata.og_title', $rentalResale->metadata?->og_title) }}">
                    @error('metadata.og_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="metadata[og_description]" class="form-label">OG Description</label>
                    <textarea class="form-control @error('metadata.og_description') is-invalid @enderror"
                        id="metadata[og_description]" name="metadata[og_description]" rows="3"
                        >{{ old('metadata.og_description', $rentalResale->metadata?->og_description) }}</textarea>
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
                    @if($rentalResale->metadata?->og_image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $rentalResale->metadata->og_image) }}" class="img-thumbnail" width="200">
                            <button type="button" class="btn btn-danger mt-2" id="remove-og-image-btn">Remove OG Image</button>
                        </div>
                    @endif
                </div>

                <h4 class="mt-4">Twitter Card</h4>
                <div class="mb-3">
                    <label for="metadata[twitter_card]" class="form-label">Twitter Card Type</label>
                    <select class="form-control @error('metadata.twitter_card') is-invalid @enderror"
                        id="metadata[twitter_card]" name="metadata[twitter_card]">
                        <option value="summary" {{ old('metadata.twitter_card', $rentalResale->metadata?->twitter_card) == 'summary' ? 'selected' : '' }}>Summary</option>
                        <option value="summary_large_image" {{ old('metadata.twitter_card', $rentalResale->metadata?->twitter_card) == 'summary_large_image' ? 'selected' : '' }}>Summary Large Image</option>
                    </select>
                    @error('metadata.twitter_card')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="metadata[twitter_title]" class="form-label">Twitter Title</label>
                    <input type="text" class="form-control @error('metadata.twitter_title') is-invalid @enderror"
                        id="metadata[twitter_title]" name="metadata[twitter_title]"
                        value="{{ old('metadata.twitter_title', $rentalResale->metadata?->twitter_title) }}">
                    @error('metadata.twitter_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="metadata[twitter_description]" class="form-label">Twitter Description</label>
                    <textarea class="form-control @error('metadata.twitter_description') is-invalid @enderror"
                        id="metadata[twitter_description]" name="metadata[twitter_description]" rows="3"
                        >{{ old('metadata.twitter_description', $rentalResale->metadata?->twitter_description) }}</textarea>
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
                    @if($rentalResale->metadata?->twitter_image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $rentalResale->metadata->twitter_image) }}" class="img-thumbnail" width="200">
                            <button type="button" class="btn btn-danger mt-2" id="remove-twitter-image-btn">Remove Twitter Image</button>
                        </div>
                    @endif
                </div>
            </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Update Rental Resale</button>
            </div>
        </form>
    </div>

    <!-- Gallery Modal -->
    <div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="galleryModalLabel">Manage Gallery</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div id="gallery-images" class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
                            @foreach ((is_array($rentalResale->gallery_images) ? $rentalResale->gallery_images : json_decode($rentalResale->gallery_images, true)) as $index => $image)
                                <div class="col gallery-image-wrapper position-relative">
                                    <button type="button" class="btn btn-danger btn-sm remove-gallery-image" data-image="{{ $image }}" style="position: absolute; top: 10px; left: 10px; z-index: 2;">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <div class="card h-100">
                                        <img src="{{ asset('storage/' . $image) }}" alt="Gallery Image" class="card-img-top">
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <label class="form-label">Alt Text</label>
                                                @php
                                                    $altTexts = is_array($rentalResale->alt_texts) ? $rentalResale->alt_texts : json_decode($rentalResale->alt_texts, true);
                                                    $galleryAltTexts = $altTexts['gallery_images'] ?? [];
                                                    $currentAltText = '';

                                                    if (is_array($galleryAltTexts)) {
                                                        if (isset($galleryAltTexts[$index])) {
                                                            $currentAltText = $galleryAltTexts[$index];
                                                        } elseif (isset($galleryAltTexts['gallery_images'][$index])) {
                                                            $currentAltText = $galleryAltTexts['gallery_images'][$index];
                                                        }
                                                    }
                                                @endphp
                                                <input type="text" class="form-control gallery-alt-text" name="alt_texts[gallery_images][{{ $index }}]" value="{{ $currentAltText }}" placeholder="Describe this image">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-3">
                        <div id="modal-gallery-alt-texts">
                            <!-- New image alt text inputs will be added here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save-gallery-changes">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        const postId = document.getElementById('postId').value;
        const form = document.querySelector('form');
        const modal = document.getElementById('galleryModal');
        const saveChangesBtn = document.getElementById('save-gallery-changes');
        const galleryAltTextsInput = document.getElementById('gallery-alt-texts-input');

        // Handle save changes button click
        saveChangesBtn.addEventListener('click', function() {
            // Collect all alt texts from the modal
            const altTexts = {
                gallery_images: {}
            };

            // Get existing alt texts
            let existingAltTexts = {};
            try {
                existingAltTexts = JSON.parse(galleryAltTextsInput.value || '{}');
            } catch (e) {
                console.error('Error parsing existing alt texts:', e);
            }

            // Collect alt texts from the modal
            document.querySelectorAll('.gallery-alt-text').forEach(input => {
                const name = input.getAttribute('name');
                const value = input.value;
                if (name && value) {
                    // Extract the index from the name attribute
                    const match = name.match(/\[(\d+)\]$/);
                    if (match) {
                        const index = match[1];
                        altTexts.gallery_images[index] = value;
                    }
                }
            });

            // Merge with existing alt texts
            const mergedAltTexts = {
                ...existingAltTexts,
                gallery_images: altTexts.gallery_images
            };

            // Update the hidden input value
            galleryAltTextsInput.value = JSON.stringify(mergedAltTexts);

            // Close the modal
            const modalInstance = bootstrap.Modal.getInstance(modal);
            modalInstance.hide();
        });

        // Initialize Repeaters
        $('.details-repeater, .amenities-repeater, .addresses-repeater').repeater({
            show: function () {
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                if(confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                }
            },
            ready: function (setIndexes) {
                /* Not needed */
            }
        });

        $('#remove-og-image-btn')?.on('click', function() {
            if (confirm('Are you sure you want to remove the OG image?')) {
                fetch('{{ route('admin.rental_resale.delete-og-image', ['postype' => $rentalResale]) }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('OG image removed successfully.');
                        location.reload();
                    } else {
                        alert('Failed to remove OG image.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });

        $('#remove-twitter-image-btn')?.on('click', function() {
            if (confirm('Are you sure you want to remove the Twitter image?')) {
                fetch('{{ route('admin.rental_resale.delete-twitter-image', ['postype' => $rentalResale]) }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ type: 'twitter_image' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Failed to remove Twitter image.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
</script>
@endpush




