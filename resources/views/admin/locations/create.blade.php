@extends('admin.layouts.app')

@section('content')

<div class="container mt-5">
    <h1 class="mb-4">Create Location</h1>
    <div class="card-body">
        <form action="{{ route('admin.locations.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Location title</label>
                <input type="text" name="title" class="form-control" id="name" required>
            </div>
            <button type="submit" class="btn btn-success mt-3">Create</button>
        </form>
    </div>
</div>

@endsection
