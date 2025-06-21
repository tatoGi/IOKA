@extends('admin.layouts.app')

@section('content')
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">Partners</h1>
            <a href="{{ url('ioka_admin/partners/create') }}" class="btn btn-primary">
                Add New Partner
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Image</th>
                            <th>URL</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($partners as $partner)
                            <tr>
                                <td>{{ $partner->title }}</td>
                               
                                <td>
                                    @if ($partner->image && Storage::disk('public')->exists($partner->image))
                                        <img src="{{ Storage::disk('public')->url($partner->image) }}" alt="{{ $partner->title }}"
                                            class="img-fluid rounded-3" style="max-width: 100px;">
                                    @else
                                        <!-- Display the fallback icon if no image exists -->
                                        <i class="bx bx-image-alt" style="font-size: 2rem; color: #bbb;"></i>
                                    @endif
                                </td>

                                <td>{{ $partner->url }}</td>
                                <td>
                                    <a href="{{ route('admin.partners.edit', $partner) }}"
                                        class="btn btn-info btn-sm mr-2">Edit</a>
                                    <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination">
                    {{ $partners->links('admin.componenets.pagination') }} <!-- Use Bootstrap 4 pagination -->
                </div>
            </div>
        </div>
    </div>
@endsection
