@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Add FAQ</h2>
    <form method="POST" action="{{ route('admin.faq.store') }}">
        @csrf
        @include('admin.faqs.form')
        <button class="btn btn-success">Create</button>
    </form>
</div>
@endsection
