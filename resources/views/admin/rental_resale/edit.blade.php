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
                            value="{{ $rentalResale->amount->amount ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount_dirhams" class="form-label">Amount (in Dirhams)</label>
                        <input type="number" step="0.01" class="form-control amount_dirhams" id="amount_dirhams" name="amount_dirhams"
                            value="{{ $rentalResale->amount->amount_dirhams ?? '' }}" readonly>
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
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="agent_email" class="form-label">Agent Email</label>
                                    <input type="email" class="form-control" id="agent_email" name="agent_email" value="{{ old('agent_email', $rentalResale->agent_email) }}">
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
                                <div class="mb-3">
                                    <label for="mobile_agent_photo" class="form-label">Mobile Agent Photo</label>
                                    <div class="mobile-image-upload" id="mobile-upload-blog_mobile_agent_photo">
                                        <div class="mb-2">
                                            <div class="d-flex align-items-center">
                                                <input type="file" class="form-control mobile-image-input @error('mobile_agent_photo') is-invalid @enderror" 
                                                    id="input-blog_mobile_agent_photo" name="mobile_agent_photo" accept="image/*" capture="environment"
                                                    data-field="blog_mobile_agent_photo"
                                                    onchange="handleMobileImageSelect(this)">
                                                @error('mobile_agent_photo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="compression-options mb-2 d-none">
                                            <div class="card p-3">
                                                <div class="mb-2">
                                                    <label class="form-label">Image Quality</label>
                                                    <input type="range" class="form-range quality-slider" min="10" max="100" value="70" 
                                                        id="quality-blog_mobile_banner_image" data-field="blog_mobile_banner_image">
                                                    <div class="d-flex justify-content-between">
                                                        <small>Lower (Smaller File)</small>
                                                        <small class="quality-value">70%</small>
                                                        <small>Higher (Better Quality)</small>
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-2">
                                                    <label class="form-label">Max Width</label>
                                                    <select class="form-select max-width" id="max-width-blog_mobile_banner_image" data-field="blog_mobile_banner_image" style="display: block !important; width: 100%;">
                                                        <option value="800" selected>Small (800px)</option>
                                                        <option value="1200">Medium (1200px)</option>
                                                        <option value="1600">Large (1600px)</option>
                                                        <option value="0">Original Size</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="image-preview-container mb-2 d-none">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <label class="form-label mb-0">Preview</label>
                                                        <div class="file-info small text-muted"></div>
                                                    </div>
                                                    <img src="" class="img-fluid img-thumbnail preview-image" style="max-height: 200px;">
                                                </div>
                                                
                                                <div class="d-flex justify-content-between">
                                                    <button type="button" class="btn btn-secondary btn-sm cancel-compression" 
                                                        onclick="cancelCompression('blog_mobile_banner_image')">Cancel</button>
                                                    <button type="button" class="btn btn-primary btn-sm apply-compression" 
                                                        onclick="applyCompression('blog_mobile_banner_image')">Apply & Upload</button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <input type="hidden" name="mobile_banner_image_compressed" id="compressed-blog_mobile_banner_image" class="compressed-image-data">
                                    </div>
                                    @if (isset($rentalResale->mobile_agent_photo) && $rentalResale->mobile_agent_photo)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $rentalResale->mobile_agent_photo) }}" alt="{{ $rentalResale->mobile_agent_photo_alt }}" class="img-thumbnail" width="200">
                                            <button type="button" class="btn btn-danger mt-2" id="remove-mobile-agent-photo-btn">Remove Mobile Agent Photo</button>
                                        </div>
                                    @endif
                                </div>
    
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="languages" class="form-label">Languages</label>
                                    <div class="languages-repeater">
                                        <div data-repeater-list="languages">
                                            @php
                                                $languages = $rentalResale->languages;
                                                if (is_string($languages)) {
                                                    $languages = json_decode($languages, true) ?: [];
                                                }
                                                if (!is_array($languages)) {
                                                    $languages = [];
                                                }
                                                // If it's an array of strings, transform it to an array of arrays/objects.
                                                if (!empty($languages) && is_array($languages) && is_string(current($languages))) {
                                                    $languages = array_map(fn($v) => ['language' => $v], $languages);
                                                }
                                                $languagesData = old('languages', $languages);
                                            @endphp

                                            @if (!empty($languagesData))
                                                @foreach ($languagesData as $languageItem)
                                                    <div data-repeater-item class="repeater-item mb-2">
                                                        <input type="text" name="language" class="form-control mb-2" value="{{ $languageItem['language'] ?? '' }}" placeholder="Language">
                                                        <button type="button" class="btn btn-danger" data-repeater-delete>
                                                            <i class="fas fa-trash-alt"></i> Remove
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div data-repeater-item class="repeater-item mb-2">
                                                    <input type="text" name="language" class="form-control mb-2" placeholder="Language">
                                                    <button type="button" class="btn btn-danger" data-repeater-delete>
                                                        <i class="fas fa-trash-alt"></i> Remove
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-primary mt-2" data-repeater-create>
                                            <i class="fas fa-plus"></i> Add Language
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

                        <label for="location_id" class="form-label">Location</label>
                        <select name="location_id[]" id="location_id" class="form-control select2" >
                            <option value="">Select Location</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ !empty($selectedLocations) && $selectedLocations[0] == $location->id ? 'selected' : '' }}>
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
                        <label for="mobile_qr_image" class="form-label">Mobile qr Photo</label>
                        <div class="mobile-image-upload" id="mobile-upload-blog_mobile_qr_image">
                            <div class="mb-2">
                                <div class="d-flex align-items-center">
                                    <input type="file" class="form-control mobile-image-input @error('mobile_qr_image') is-invalid @enderror" 
                                        id="input-blog_mobile_qr_image" name="mobile_qr_image" accept="image/*" capture="environment"
                                        data-field="blog_mobile_qr_image"
                                        onchange="handleMobileImageSelect(this)">
                                    @error('mobile_qr_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="compression-options mb-2 d-none">
                                <div class="card p-3">
                                    <div class="mb-2">
                                        <label class="form-label">Image Quality</label>
                                        <input type="range" class="form-range quality-slider" min="10" max="100" value="70" 
                                            id="quality-blog_mobile_banner_image" data-field="blog_mobile_banner_image">
                                        <div class="d-flex justify-content-between">
                                            <small>Lower (Smaller File)</small>
                                            <small class="quality-value">70%</small>
                                            <small>Higher (Better Quality)</small>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <label class="form-label">Max Width</label>
                                        <select class="form-select max-width" id="max-width-blog_mobile_banner_image" data-field="blog_mobile_banner_image" style="display: block !important; width: 100%;">
                                            <option value="800" selected>Small (800px)</option>
                                            <option value="1200">Medium (1200px)</option>
                                            <option value="1600">Large (1600px)</option>
                                            <option value="0">Original Size</option>
                                        </select>
                                    </div>
                                    
                                    <div class="image-preview-container mb-2 d-none">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="form-label mb-0">Preview</label>
                                            <div class="file-info small text-muted"></div>
                                        </div>
                                        <img src="" class="img-fluid img-thumbnail preview-image" style="max-height: 200px;">
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-secondary btn-sm cancel-compression" 
                                            onclick="cancelCompression('blog_mobile_banner_image')">Cancel</button>
                                        <button type="button" class="btn btn-primary btn-sm apply-compression" 
                                            onclick="applyCompression('blog_mobile_banner_image')">Apply & Upload</button>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="hidden" name="mobile_banner_image_compressed" id="compressed-blog_mobile_banner_image" class="compressed-image-data">
                        </div>
                        @if (isset($rentalResale->mobile_qr_photo) && $rentalResale->mobile_qr_photo)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $rentalResale->mobile_qr_photo) }}" alt="{{ $rentalResale->mobile_qr_photo_alt }}" class="img-thumbnail" width="200">
                                <button type="button" class="btn btn-danger mt-2" id="remove-mobile-qr-image-btn">Remove Mobile QR Photo</button>
                            </div>
                        @endif
                    </div>

                    <!-- Mobile Gallery -->
                    <div class="mb-3">
                        <label for="mobile_gallery_images" class="form-label">Mobile Gallery Images</label>
                        <div class="row g-3 mb-3" id="mobile-gallery-preview">
                            @if($rentalResale->mobile_gallery_images && is_array($rentalResale->mobile_gallery_images))
                                @foreach($rentalResale->mobile_gallery_images as $image)
                                    <div class="col-6 col-md-4 col-lg-3 mobile-gallery-item" data-image="{{ $image }}">
                                        <div class="card h-100">
                                            <img src="{{ asset('storage/' . $image) }}" class="card-img-top" alt="Mobile Gallery Image" style="height: 150px; object-fit: cover;">
                                            <div class="card-body text-center">
                                                <button type="button" class="btn btn-danger btn-sm remove-mobile-gallery-image" data-image="{{ $image }}">
                                                    <i class="fas fa-trash-alt"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="input-group">
                            <input type="file" 
                                   class="form-control" 
                                   id="mobile_gallery_images" 
                                   name="mobile_gallery_images[]" 
                                   multiple 
                                   accept="image/*" 
                                   onchange="handleMobileGallerySelect(this)">
                            <label class="input-group-text" for="mobile_gallery_images">
                                <i class="fas fa-upload"></i> Upload
                            </label>
                        </div>
                        <small class="form-text text-muted">Upload multiple images for the mobile gallery (JPEG, PNG, JPG, GIF, max 5MB each)</small>
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
                                    <input class="form-check-input" type="checkbox" id="top" name="top" value="1" {{ old('top', $rentalResale->top) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="top">
                                        Mark as Top Listing
                                    </label>
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

                    <!-- Mobile Gallery -->
                    <div class="mb-3">
                        <label for="mobile_gallery" class="form-label">Mobile Gallery</label>
                        <div class="mobile-gallery-container">
                            <div class="row row-cols-1 row-cols-md-3 g-4" id="mobile-gallery-preview">
                                @php
                                    $mobileGallery = is_array($rentalResale->mobile_gallery_images) 
                                        ? $rentalResale->mobile_gallery_images 
                                        : (json_decode($rentalResale->mobile_gallery_images, true) ?? []);
                                    $mobileGallery = array_filter($mobileGallery);
                                @endphp
                                @foreach($mobileGallery as $index => $image)
                                    <div class="col mobile-gallery-item" data-image="{{ $image }}">
                                        <div class="card h-100">
                                            <img src="{{ asset('storage/' . $image) }}" class="card-img-top" alt="Mobile Gallery Image">
                                            <div class="card-body text-center">
                                                <button type="button" class="btn btn-danger btn-sm remove-mobile-gallery-image" data-image="{{ $image }}">
                                                    <i class="fas fa-trash-alt"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-3">
                                <div class="mobile-image-upload" id="mobile-upload-gallery">
                                    <div class="mb-2">
                                        <div class="d-flex align-items-center">
                                            <input type="file" class="form-control mobile-image-input" 
                                                id="input-mobile-gallery" name="mobile_gallery_images[]" 
                                                accept="image/*" multiple capture="environment"
                                                onchange="handleMobileGallerySelect(this)">
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="mobile_gallery_compressed" id="compressed-mobile-gallery">
                            </div>
                        </div>
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
        $(document).ready(function() {
            $('.details-repeater, .amenities-repeater, .languages-repeater').repeater({
                show: function() {
                    $(this).slideDown();
                },
                hide: function(deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                    }
                }
            });
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

        // Handle gallery image deletion
        $('.remove-gallery-image').on('click', function() {
            if (confirm('Are you sure you want to remove this image?')) {
                const imagePath = $(this).data('image-path');
                const postId = {{ $rentalResale->id }};
                const container = $(this).closest('.gallery-image-item');

                fetch(`{{ route('admin.postypes.rental_resale.removeGalleryImage', ['postype' => $rentalResale->id]) }}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ image: imagePath })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Image removed successfully.');
                        container.remove();
                    } else {
                        alert('Failed to remove image: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while removing the image.');
                });
            }
        });

        // Handle agent photo deletion
        $('#remove-agent-photo').on('click', function() {
            if (confirm('Are you sure you want to remove the agent photo?')) {
                const postId = {{ $rentalResale->id }};

                fetch(`{{ route('admin.postypes.rental_resale.removeAgentPhoto', ['postype' => $rentalResale->id]) }}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Agent photo removed successfully.');
                        location.reload();
                    } else {
                        alert('Failed to remove agent photo.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });

        // Handle Mobile Gallery Image Upload
        function handleMobileGallerySelect(input) {
            const files = input.files;
            const previewContainer = document.getElementById('mobile-gallery-preview');
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Create a preview card for the uploaded image
                    const previewCard = document.createElement('div');
                    previewCard.className = 'col mobile-gallery-item';
                    previewCard.innerHTML = `
                        <div class="card h-100">
                            <img src="${e.target.result}" class="card-img-top" alt="Mobile Gallery Preview">
                            <div class="card-body text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-mobile-gallery-image">
                                    <i class="fas fa-trash-alt"></i> Remove
                                </button>
                            </div>
                        </div>
                    `;
                    
                    // Add remove functionality
                    const removeBtn = previewCard.querySelector('.remove-mobile-gallery-image');
                    removeBtn.addEventListener('click', function() {
                        if (confirm('Are you sure you want to remove this image?')) {
                            previewCard.remove();
                            // If this was an existing image, add it to a hidden field for server-side cleanup
                            if (previewCard.dataset.image) {
                                const removedImages = document.getElementById('removed-mobile-gallery-images') || 
                                    (function() {
                                        const input = document.createElement('input');
                                        input.type = 'hidden';
                                        input.name = 'removed_mobile_gallery_images[]';
                                        input.id = 'removed-mobile-gallery-images';
                                        input.multiple = true;
                                        input.form.appendChild(input);
                                        return input;
                                    })();
                                const hiddenInput = document.createElement('input');
                                hiddenInput.type = 'hidden';
                                hiddenInput.name = 'removed_mobile_gallery_images[]';
                                hiddenInput.value = previewCard.dataset.image;
                                removedImages.parentNode.insertBefore(hiddenInput, removedImages.nextSibling);
                            }
                        }
                    });
                    
                    previewContainer.appendChild(previewCard);
                };
                
                reader.readAsDataURL(file);
            }
            
            // Reset the file input to allow selecting the same file again
            input.value = '';
        }

        // Handle Mobile Gallery Image Deletion
        $(document).on('click', '.remove-mobile-gallery-image', function() {
            if (confirm('Are you sure you want to remove this image from the mobile gallery?')) {
                const imagePath = $(this).data('image');
                const imageItem = $(this).closest('.mobile-gallery-item');
                
                // If this is an existing image (not a new upload)
                if (imagePath) {
                    const formData = new FormData();
                    formData.append('image', imagePath);
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('_method', 'DELETE');

                    fetch(`{{ route('admin.postypes.rental_resale.removeMobileGalleryImage', ['postype' => $rentalResale->id]) }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            imageItem.fadeOut(300, function() {
                                $(this).remove();
                            });
                        } else {
                            alert('Failed to remove image: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while removing the image.');
                    });
                } else {
                    // For newly added images that haven't been saved yet
                    imageItem.fadeOut(300, function() {
                        $(this).remove();
                    });
                }
            }
        });

        // Handle QR photo deletion
        $('#remove-qr-photo').on('click', function() {
            if (confirm('Are you sure you want to remove the QR photo?')) {
                const postId = {{ $rentalResale->id }};

                fetch(`{{ route('admin.postypes.rental_resale.removeQrPhoto', ['postype' => $rentalResale->id]) }}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('QR photo removed successfully.');
                        location.reload();
                    } else {
                        alert('Failed to remove QR photo.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
</script>
@endpush




