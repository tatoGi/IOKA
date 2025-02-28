@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Edit Offplan</h1>
    <form action="{{ route('admin.offplan.update', $offplan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $offplan->title }}" required>
        </div>
        <div class="form-group">
            <label for="subtitle">Subtitle</label>
            <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{ $offplan->subtitle }}">
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Amount (in dollars)</label>
            <input type="number" step="0.01" class="form-control amount" id="amount" name="amount" value="{{ $offplan->amount }}" required>
        </div>
        <div class="mb-3">
            <label for="amount_dirhams" class="form-label">Amount (in Dirhams)</label>
            <input type="number" step="0.01" class="form-control amount_dirhams" id="amount_dirhams" name="amount_dirhams" value="{{ $offplan->amount_dirhams }}" readonly>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control editor" id="description" name="description" required>{{ $offplan->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="features">Features</label>
            <div id="features_repeater">
                @foreach (json_decode($offplan->features, true) as $index => $feature)
                <div class="features_item">
                    <input type="text" class="form-control mb-2" name="features[{{ $index }}]" value="{{ $feature }}" placeholder="Feature">
                </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-secondary" id="add_feature">Add More</button>
        </div>
        <div class="form-group">
            <label for="amenities">Amenities</label>
            <textarea class="form-control editor" id="amenities" name="amenities">{{ $offplan->amenities }}</textarea>
        </div>
        <div class="form-group">
            <label for="map_location">Map Location</label>
            <input type="text" class="form-control" id="map_location" name="map_location" value="{{ $offplan->map_location }}">
        </div>
        <div class="form-group">
            <label for="near_by">Near By</label>
            <div id="near_by_repeater">
                @foreach (json_decode($offplan->near_by, true) as $index => $nearBy)
                <div class="near_by_item">
                    <input type="text" class="form-control mb-2" name="near_by[{{ $index }}][title]" value="{{ $nearBy['title'] }}" placeholder="Title">
                    <input type="number" step="0.1" class="form-control mb-2" name="near_by[{{ $index }}][distance]" value="{{ $nearBy['distance'] }}" placeholder="Distance (e.g., 4.5)">
                </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-secondary" id="add_near_by">Add More</button>
        </div>
        <div class="form-group">
            <label for="main_photo">Main Photo</label>
            <input type="file" class="form-control" id="main_photo" name="main_photo" accept="image/*">
            <div id="main_photo_preview" class="uploaded-files">
                @if ($offplan->main_photo)
                <div class="uploaded-file">
                    <img src="{{ asset('storage/' . $offplan->main_photo) }}" alt="Main Photo" class="img-thumbnail" style="max-width: 100px;">
                </div>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label for="exterior_gallery">Exterior Gallery</label>
            <input type="file" class="form-control" id="exterior_gallery" name="exterior_gallery[]" multiple accept="image/*">
            <div id="exterior_gallery_preview" class="uploaded-files">
                @foreach (json_decode($offplan->exterior_gallery, true) as $photo)
                <div class="uploaded-file">
                    <img src="{{ asset('storage/' . $photo) }}" alt="Exterior Photo" class="img-thumbnail" style="max-width: 100px;">
                </div>
                @endforeach
            </div>
        </div>
        <div class="form-group">
            <label for="interior_gallery">Interior Gallery</label>
            <input type="file" class="form-control" id="interior_gallery" name="interior_gallery[]" multiple accept="image/*">
            <div id="interior_gallery_preview" class="uploaded-files">
                @foreach (json_decode($offplan->interior_gallery, true) as $photo)
                <div class="uploaded-file">
                    <img src="{{ asset('storage/' . $photo) }}" alt="Interior Photo" class="img-thumbnail" style="max-width: 100px;">
                </div>
                @endforeach
            </div>
        </div>
        <div class="form-group">
            <label for="property_type">Property Type</label>
            <select class="form-control" id="property_type" name="property_type">
                <option value="Villa" {{ $offplan->property_type == 'Villa' ? 'selected' : '' }}>Villa</option>
                <option value="Townhouse" {{ $offplan->property_type == 'Townhouse' ? 'selected' : '' }}>Townhouse</option>
                <option value="Apartment" {{ $offplan->property_type == 'Apartment' ? 'selected' : '' }}>Apartment</option>
                <option value="Land" {{ $offplan->property_type == 'Land' ? 'selected' : '' }}>Land</option>
                <option value="Full Building" {{ $offplan->property_type == 'Full Building' ? 'selected' : '' }}>Full Building</option>
                <option value="Commercial" {{ $offplan->property_type == 'Commercial' ? 'selected' : '' }}>Commercial</option>
            </select>
        </div>
        <div class="form-group">
            <label for="bathroom">Bathroom</label>
            <input type="number" class="form-control" id="bathroom" name="bathroom" value="{{ $offplan->bathroom }}">
        </div>
        <div class="form-group">
            <label for="bedroom">Bedroom</label>
            <input type="number" class="form-control" id="bedroom" name="bedroom" value="{{ $offplan->bedroom }}">
        </div>
        <div class="form-group">
            <label for="garage">Garage</label>
            <input type="number" class="form-control" id="garage" name="garage" value="{{ $offplan->garage }}">
        </div>
        <div class="form-group">
            <label for="sq_ft">Square Feet</label>
            <input type="number" class="form-control" id="sq_ft" name="sq_ft" value="{{ $offplan->sq_ft }}">
        </div>
        <div class="form-group">
            <label for="qr_title">QR Title</label>
            <input type="text" class="form-control" id="qr_title" name="qr_title" value="{{ $offplan->qr_title }}">
        </div>
        <div class="form-group">
            <label for="qr_photo">QR Photo</label>
            <input type="file" class="form-control" id="qr_photo" name="qr_photo" accept="image/*">
            <div id="qr_photo_preview" class="uploaded-files">
                @if ($offplan->qr_photo)
                <div class="uploaded-file">
                    <img src="{{ asset('storage/' . $offplan->qr_photo) }}" alt="QR Photo" class="img-thumbnail" style="max-width: 100px;">
                </div>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label for="qr_text">QR Text</label>
            <textarea class="form-control editor" id="qr_text" name="qr_text">{{ $offplan->qr_text }}</textarea>
        </div>
        <div class="form-group">
            <label for="download_brochure">Download Brochure</label>
            <input type="text" class="form-control" id="download_brochure" name="download_brochure" value="{{ $offplan->download_brochure }}">
        </div>
        <div class="form-group">
            <label for="agent_title">Agent Title</label>
            <input type="text" class="form-control" id="agent_title" name="agent_title" value="{{ $offplan->agent_title }}">
        </div>
        <div class="form-group">
            <label for="agent_status">Agent Status</label>
            <input type="text" class="form-control" id="agent_status" name="agent_status" value="{{ $offplan->agent_status }}">
        </div>
        <div class="form-group">
            <label for="agent_image">Agent Image</label>
            <input type="file" class="form-control" id="agent_image" name="agent_image" accept="image/*">
            <div id="agent_image_preview" class="uploaded-files">
                @if ($offplan->agent_image)
                <div class="uploaded-file">
                    <img src="{{ asset('storage/' . $offplan->agent_image) }}" alt="Agent Image" class="img-thumbnail" style="max-width: 100px;">
                </div>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label for="agent_telephone">Agent Telephone</label>
            <input type="text" class="form-control" id="agent_telephone" name="agent_telephone" value="{{ $offplan->agent_telephone }}">
        </div>
        <div class="form-group">
            <label for="agent_whatsapp">Agent WhatsApp</label>
            <input type="text" class="form-control" id="agent_whatsapp" name="agent_whatsapp" value="{{ $offplan->agent_whatsapp }}">
        </div>
        <div class="form-group">
            <label for="agent_linkedin">Agent LinkedIn</label>
            <input type="text" class="form-control" id="agent_linkedin" name="agent_linkedin" value="{{ $offplan->agent_linkedin }}">
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" class="form-control" id="location" name="location" value="{{ $offplan->location }}">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<script>
    function handleFileInput(event, previewId) {
        const files = event.target.files;
        const preview = document.getElementById(previewId);
        preview.innerHTML = '';
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            reader.onload = function(e) {
                const fileDiv = document.createElement('div');
                fileDiv.classList.add('uploaded-file');
                fileDiv.innerHTML = `
                    <img src="${e.target.result}" alt="${file.name}" class="img-thumbnail" style="max-width: 100px;">
                `;
                preview.appendChild(fileDiv);
            };
            reader.readAsDataURL(file);
        }
    }

    document.getElementById('main_photo').addEventListener('change', function(event) {
        handleFileInput(event, 'main_photo_preview');
    });

    document.getElementById('exterior_gallery').addEventListener('change', function(event) {
        handleFileInput(event, 'exterior_gallery_preview');
    });

    document.getElementById('interior_gallery').addEventListener('change', function(event) {
        handleFileInput(event, 'interior_gallery_preview');
    });

    document.getElementById('qr_photo').addEventListener('change', function(event) {
        handleFileInput(event, 'qr_photo_preview');
    });

    document.getElementById('agent_image').addEventListener('change', function(event) {
        handleFileInput(event, 'agent_image_preview');
    });

    document.getElementById('add_feature').addEventListener('click', function() {
        var repeater = document.getElementById('features_repeater');
        var index = repeater.children.length;
        var newItem = document.createElement('div');
        newItem.classList.add('features_item');
        newItem.innerHTML = `
            <input type="text" class="form-control mb-2" name="features[${index}]" placeholder="Feature">
        `;
        repeater.appendChild(newItem);
    });

    document.getElementById('add_near_by').addEventListener('click', function() {
        var repeater = document.getElementById('near_by_repeater');
        var index = repeater.children.length;
        var newItem = document.createElement('div');
        newItem.classList.add('near_by_item');
        newItem.innerHTML = `
            <input type="text" class="form-control mb-2" name="near_by[${index}][title]" placeholder="Title">
            <input type="number" step="0.1" class="form-control mb-2" name="near_by[${index}][distance]" placeholder="Distance (e.g., 4.5)">
        `;
        repeater.appendChild(newItem);
    });

</script>
@endsection
