@extends('admin.layouts.app')

@section('content')

<div class="container mt-5">
    <h1 class="mb-4">locations</h1>
    <div class="card-body">
        <a href="{{ route('admin.locations.create') }}" class="btn btn-success mb-3">Create Location</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($locations) && count($locations) > 0)
                @foreach($locations as $location)
                <tr>
                    <td>{{ $location->title }}</td>
                    <td>
                        <a href="{{ route('admin.locations.edit', $location->id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('admin.locations.destroy', $location->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @else
                <p>No locations available.</p>
                @endif
            </tbody>
        </table>
    </div>
</div>

@endsection
