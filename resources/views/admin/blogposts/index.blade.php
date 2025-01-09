@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h1>Blog Posts</h1>
                <a href="{{ route('blogposts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create New Post
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <form action="{{ route('blogposts.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by title..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Tags</th>
                                <th>Show on Main</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($blogPosts as $post)
                                <tr>

                                    <td>{{ $post->title }}</td>
                                    <td>{{ \Carbon\Carbon::parse($post->date)->format('Y-m-d') }}</td>
                                    <td>
                                        @foreach ($post->tags as $tag)
                                            <span class="badge bg-secondary">{{ $tag->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if ($post->show_on_main_page)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-danger">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('blogposts.edit', $post) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('blogposts.destroy', $post) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No blog posts found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $blogPosts->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
