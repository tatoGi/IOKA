@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <h1>Offplan Properties</h1>
    <a href="{{ route('admin.offplan.offplan.create') }}" class="btn btn-success mb-3">Create New Offplan</a>
    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>Title</th>
                <th>Amount $</th>
                <th>Amount Dirham</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @isset($offplans)
            @foreach($offplans as $offplan)
            <tr>
                <td>{{ $offplan->title }}</td>
                <td>{{ $offplan->amount }}</td>
                <td>{{ $offplan->amount_dirhams }}</td>
                <td>{!! Str::limit($offplan->description, 50) !!}...</td> <!-- Limit description to 100 chars -->
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

    <!-- Paginate Links -->
    <div class="pagination">
        {{ $offplans->links('admin.componenets.pagination') }} <!-- Use Bootstrap 4 pagination -->
    </div>
</div>
@endsection
