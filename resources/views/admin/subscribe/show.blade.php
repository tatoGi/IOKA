@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Subscriber Details</h1>

    <div class="card">
        <div class="card-header">
            Subscriber #{{ $message->id }}
        </div>
        <div class="card-body">
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
                    <strong>Email Verified:</strong>
                </div>
                <div class="col-md-8">
                    @if($message->email_verified_at)
                        <span class="badge badge-success">Yes</span>
                        <small class="text-muted">({{ $message->email_verified_at->setTimezone('Asia/Tbilisi')->format('d/m/Y H:i') }})</small>
                    @else
                        <span class="badge badge-warning">No</span>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Status:</strong>
                </div>
                <div class="col-md-8">
                    @if($message->is_active)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-danger">Inactive</span>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Verification Token:</strong>
                </div>
                <div class="col-md-8">
                    @if($message->token)
                        <code>{{ $message->token }}</code>
                    @else
                        <span class="text-muted">No token (already verified)</span>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Subscribed Date:</strong>
                </div>
                <div class="col-md-8">
                    {{ $message->created_at->setTimezone('Asia/Tbilisi')->format('d/m/Y H:i') }}
                </div>
            </div>

            @if($message->updated_at != $message->created_at)
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Last Updated:</strong>
                </div>
                <div class="col-md-8">
                    {{ $message->updated_at->setTimezone('Asia/Tbilisi')->format('d/m/Y H:i') }}
                </div>
            </div>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.subscribe.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Subscribers
            </a>
        </div>
    </div>
</div>
@endsection
