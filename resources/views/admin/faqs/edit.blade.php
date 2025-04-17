@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Edit FAQ</h2>
    <form method="POST" action="{{ route('admin.faq.update', $faq) }}">
        @csrf
        @method('PUT')
        @include('admin.faqs.form')
        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
