@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Developer</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.developer.update', $developer->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ $developer->title }}" required>
                            </div>
                            <div class="form-group">
                                <label for="paragraph">Paragraph</label>
                                <textarea name="paragraph" id="paragraph" class="form-control" required>{{ $developer->paragraph }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" id="phone" class="form-control" value="{{ $developer->phone }}" required>
                            </div>
                            <div class="form-group">
                                <label for="whatsapp">WhatsApp</label>
                                <input type="text" name="whatsapp" id="whatsapp" class="form-control" value="{{ $developer->whatsapp }}" required>
                            </div>
                            <div class="form-group">
                                <label for="photo">Photo</label>
                                <input type="file" name="photo" id="photo" class="form-control">
                                <img src="{{ asset('storage/' . $developer->photo) }}" alt="Developer Photo" width="100">
                            </div>
                            <div class="form-group">
                                <label for="award_title">Award Title</label>
                                <input type="text" name="award_title" id="award_title" class="form-control" value="{{ $developer->award_title }}">
                            </div>
                            <div class="form-group">
                                <label for="award_year">Award Year</label>
                                <input type="text" name="award_year" id="award_year" class="form-control" value="{{ $developer->award_year }}">
                            </div>
                            <div class="form-group">
                                <label for="award_description">Award Description</label>
                                <textarea name="award_description" id="award_description" class="form-control">{{ $developer->award_description }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="award_photo">Award Photo</label>
                                <input type="file" name="award_photo" id="award_photo" class="form-control">
                                <img src="{{ asset('storage/' . $developer->award_photo) }}" alt="Award Photo" width="100">
                            </div>
                            <div class="form-group">
                                <label for="rental_listings">Rental Listings</label>
                                <select name="rental_listings[]" id="rental_listings" class="form-control" multiple>
                                    @foreach($rentalListings as $listing)
                                        <option value="{{ $listing->id }}" {{ in_array($listing->id, $developer->rentalListings->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $listing->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="resale_listings">Resale Listings</label>
                                <select name="resale_listings[]" id="resale_listings" class="form-control" multiple>
                                    @foreach($resaleListings as $listing)
                                        <option value="{{ $listing->id }}" {{ in_array($listing->id, $developer->resaleListings->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $listing->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="offplan_listings">Offplan Listings</label>
                                <select name="offplan_listings[]" id="offplan_listings" class="form-control" multiple>
                                    @foreach($offplanListings as $listing)
                                        <option value="{{ $listing->id }}" {{ in_array($listing->id, $developer->offplanListings->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $listing->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
