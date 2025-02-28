@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Offplan Properties</h1>
    <a href="{{ route('admin.offplan.offplan.create') }}" class="btn btn-success mb-3">Create New Offplan</a>
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Subtitle</th>
                <th>amount $</th>
                <th>amount dirham</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @isset($offplans)
            @foreach($offplans as $offplan)
            <tr>
                <td>{{ $offplan->title }}</td>
                <td>{{ $offplan->subtitle }}</td>
                <td>{{ $offplan->amount }}</td>
                <td>{{ $offplan->amount_dirham }}</td>
                <td>{{ $offplan->description }}</td>
                <td>
                    <a href="{{ route('admin.offplan.edit', $offplan->id) }}" class="btn btn-primary">Edit</a>
                    <form action="{{ route('admin.offplan.destroy', $offplan->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
            @endisset
        </tbody>
    </table>
</div>
@endsection
