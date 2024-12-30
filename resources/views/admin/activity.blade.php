@extends('admin.layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Admin Login Activities</h4>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm">Back to Dashboard</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Username</th>
                                <th scope="col">IP Address</th>
                                <th scope="col">Device Details</th>
                                <th scope="col">Login Time</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($loginActivities as $activity)
                                <tr>
                                    <td>{{ $activity->id }}</td>
                                    <td>{{ $activity->admin_username }}</td>
                                    <td>{{ $activity->ip_address }}</td>
                                    <td class="text-truncate" style="max-width: 200px;">
                                        {{ $activity->device_details }}
                                    </td>
                                    <td>{{ $activity->login_time }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $activity->status === 'success' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No login activities found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $loginActivities->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
