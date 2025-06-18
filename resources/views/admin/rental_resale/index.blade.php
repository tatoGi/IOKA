@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Rental Resale Posts</h1>
    <a href="{{ url('ioka_admin/postypes/rental_resale/create') }}" class="btn btn-primary mb-3">Create New Post</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Property Type</th>
                <th>Bathroom</th>
                <th>Bedroom</th>
                <th>SQ Ft</th>
                <th>Garage</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @isset($rentalResales)
                @foreach($rentalResales as $rentalResale)

                    <tr>
                        <td>{{ $rentalResale->id }}</td>
                        <td>{{ $rentalResale->title }}</td>
                        <td>{{ $rentalResale->property_type }}</td>
                        <td>{{ $rentalResale->bathroom }}</td>
                        <td>{{ $rentalResale->bedroom }}</td>
                        <td>{{ $rentalResale->sq_ft }}</td>
                        <td>{{ $rentalResale->garage }}</td>

                        <td>
                            <a href="{{ route('admin.postypes.rental_resale.edit', $rentalResale->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('admin.postypes.rental_resale.destroy', $rentalResale->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endisset
        </tbody>
    </table>
    <!-- Paginate Links -->
    <div class="pagination">
        {{ $rentalResales->links('admin.componenets.pagination') }} <!-- Use Bootstrap 4 pagination -->
    </div>
</div>
@endsection
