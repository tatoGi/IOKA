@extends('admin.layouts.app')

@section('title', 'Policy Pages')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Policy Pages</h2>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Policy Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(['privacy_policy', 'cookie_policy', 'terms_agreement'] as $type)
                        <tr>
                            <td>{{ ucwords(str_replace('_', ' ', $type)) }}</td>
                            <td>
                                @if(isset($policies[$type]))
                                    <a href="{{ route('admin.policy-pages.edit', $type) }}" class="btn btn-primary btn-sm">Edit</a>
                                @else
                                    <a href="{{ route('admin.policy-pages.edit', $type) }}" class="btn btn-success btn-sm">Create</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
