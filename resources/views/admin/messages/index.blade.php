@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h1>Messages</h1>
    </div>
    <div class="container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection
