@extends('admin.layouts.app')

@section('content')

<div class="container mt-5">
    <h1 class="mb-4">Edit Location</h1>
    <div class="card-body">
        <form action="{{ route('admin.locations.update', $location->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Location Name</label>
                <input type="text" name="title" class="form-control" id="name" value="{{ $location->title }}" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Update</button>
        </form>
    </div>
</div>

@endsection
