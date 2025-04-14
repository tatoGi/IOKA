@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Message Details</h1>

    <div class="card">
        <div class="card-header">
            Message #{{ $message->id }}
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Name:</strong>
                </div>
                <div class="col-md-8">
                    {{ $message->name }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Email:</strong>
                </div>
                <div class="col-md-8">
                    {{ $message->email }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Phone:</strong>
                </div>
                <div class="col-md-8">
                    {{ $message->phone }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Country:</strong>
                </div>
                <div class="col-md-8">
                    {{ $message->country }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Page:</strong>
                </div>
                <div class="col-md-8">
                    {{ $message->page_title }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Page Link:</strong>
                </div>
                <div class="col-md-8">
                    {{ $message->page_url }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Date:</strong>
                </div>
                <div class="col-md-8">
                    {{ $message->created_at->format('d/m/Y H:i') }}
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <strong>Message:</strong>
                </div>
                <div class="col-md-8">
                    <div class="border p-3">
                        {{ $message->message }}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Messages
            </a>
        </div>
    </div>
</div>
@endsection
