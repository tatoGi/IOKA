@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Messages</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Country</th>
                    <th>Message</th>
                    <th>Page</th>
                    <th>Page Link</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $message)

                <tr>
                    <td>{{ $message->id }}</td>
                    <td>{{ $message->name }}</td>
                    <td>{{ $message->email }}</td>
                    <td>{{ $message->phone }}</td>
                    <td>{{ $message->country }}</td>
                    <td>{{ Str::limit($message->message, 50) }}</td>
                    <td>{{ $message->page_title }}</td>
                    <td><a href="{{ $message->page_url }}">{{ $message->page_url }} </a>   </td>
                    <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.messages.show', $message->id) }}"
                           class="btn btn-info btn-sm">
                           <i class="fas fa-eye"></i> View
                        </a>
                        <form action="{{ route('admin.messages.destroy', $message->id) }}"
                              method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
