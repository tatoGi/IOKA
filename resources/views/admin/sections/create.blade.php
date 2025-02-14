@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <form action="{{ route('admin.sections.store', ['pageId' => $pageId]) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Add this hidden input for section_key -->
            <input type="hidden" name="section_key" value="{{ $sectionKey }}">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <h3>{{ $page['title'] }}</h3>
                </div>
                <div class="card-body">
                    @include('admin.sections.fields', ['section' => $section])
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
