@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>FAQ List</h2>
    <a href="{{ route('admin.faq.create') }}" class="btn btn-primary mb-3">Add FAQ</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Question</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($faqs as $faq)
                <tr>
                    <td>{{ $faq->question }}</td>
                    <td>{{ $faq->is_active ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('admin.faq.edit', $faq) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.faq.destroy', $faq) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this FAQ?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $faqs->links() }}
</div>
@endsection
