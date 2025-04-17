@extends('admin.layouts.app')

@section('title', 'Edit ' . ucwords(str_replace('_', ' ', $type)))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Edit {{ ucwords(str_replace('_', ' ', $type)) }}
                    </h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.policy-pages.update', $type) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="content">{{ ucwords(str_replace('_', ' ', $type)) }} Content</label>
                            <textarea class="form-control editor @error('content') is-invalid @enderror"
                                      id="content"
                                      name="content"
                                      rows="20">{{ old('content', $policy->content ?? '') }}</textarea>
                            @error('content')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="{{ route('admin.policy-pages.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .tox-tinymce {
        border-radius: 0.25rem !important;
    }
</style>
@endpush

