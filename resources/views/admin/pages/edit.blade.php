@extends('admin.layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-sm" style="width: 100%; max-width: 600px;">
            <div class="card-body">
                <h4 class="mb-4 text-center">Edit Page</h4>

                <form action="{{ route('menu.update', $page->id) }}" method="POST" class="bg-white p-4 rounded" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="general-tab" data-bs-toggle="tab"
                                data-bs-target="#general" type="button" role="tab" aria-controls="general"
                                aria-selected="true">General</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="metadata-tab-button" data-bs-toggle="tab" data-bs-target="#metadata-tab"
                                type="button" role="tab" aria-controls="metadata-tab"
                                aria-selected="false">Metadata</button>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="general" role="tabpanel"
                            aria-labelledby="general-tab">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" id="title"
                                    value="{{ old('title', $page->title) }}"
                                    class="form-control @error('title') is-invalid @enderror">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="desc" class="form-label">Main Description (for page content)</label>
                                <textarea name="desc" id="desc" rows="4" class="form-control editor @error('desc') is-invalid @enderror">{{ old('desc', $page->desc) }}</textarea>
                                @error('desc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="type_id" class="form-label">Type</label>
                                <select name="type_id" id="type_id"
                                    class="form-select @error('type_id') is-invalid @enderror">
                                    <option value="">Select Page Type</option>
                                    @foreach (Config::get('PageTypes') as $type)
                                        <option value="{{ $type['id'] }}"
                                            {{ old('type_id', $page->type_id) == $type['id'] ? 'selected' : '' }}>
                                            {{ $type['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input type="hidden" name="active" value="0">
                                <input type="checkbox" name="active" id="active" value="1"
                                    {{ old('active', $page->active) ? 'checked' : '' }} class="form-check-input">
                                <label for="active" class="form-check-label">Active</label>
                                @error('active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @include('components.metadata-tab', ['model' => $page])

                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-3">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection

