@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Section - {{ $page->title }}</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-arrow-left"></i> Back to Page
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.sections.update', ['pageId' => $pageId, 'sectionKey' => $sectionKey]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">

                    <div id="section-fields">
                        @include('admin.sections.fields', ['section' => $sectionConfig])
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update Section</button>
                </div>
            </form>
        </div>
    </div>
@endsection
