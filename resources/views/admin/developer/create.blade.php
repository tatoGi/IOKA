@extends('admin.layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Create Developer</h1>
    <div class="card-body">
        <form action="{{ route('admin.developer.store') }}" method="POST" enctype="multipart/form-data" class="shadow p-4 rounded bg-light">
            @csrf
            <div class="form-group mb-3">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="paragraph editor">Paragraph</label>
                <textarea name="paragraph" id="paragraph" class="form-control editor" required></textarea>
            </div>
            <div class="container mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="whatsapp">WhatsApp</label>
                            <input type="text" name="whatsapp" id="whatsapp" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="photo">Photo</label>
                <input type="file" name="photo" id="photo" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="award_title">Award Title</label>
                <input type="text" name="award_title" id="award_title" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="award_year">Award Year</label>
                <input type="text" name="award_year" id="award_year" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="award_description">Award Description</label>
                <textarea name="award_description" id="award_description" class="form-control editor"></textarea>
            </div>
            <div class="form-group mb-3">
                <label for="award_photo">Award Photo</label>
                <input type="file" name="award_photo" id="award_photo" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="rental_listings">Rental Listings</label>
                <select name="rental_listings[]" id="rental_listings" class="form-control" multiple>
                    @foreach($rentalandresaleListings as $listing)
                        <option value="{{ $listing->id }}">{{ $listing->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="offplan_listings">Offplan Listings</label>
                <select name="offplan_listings[]" id="offplan_listings" class="form-control" multiple>
                    @foreach($offplanListings as $listing)
                        <option value="{{ $listing->id }}">{{ $listing->title }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#rental_listings').select2();
            $('#offplan_listings').select2();
        });
    </script>
@endsection
