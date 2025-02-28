@extends('admin.layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Developer</h4>
                        <a href="{{ route('admin.developer.create') }}" class="btn btn-primary">Add Developer</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Phone</th>
                                        <th>WhatsApp</th>
                                        <th>Awards</th>
                                        <th>photo</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($developers)
                                    @foreach($developers as $developer)
                                        <tr>
                                            <td>{{ $developer->id }}</td>
                                            <td>{{ $developer->title }}</td>
                                            <td>{{ $developer->phone }}</td>

                                            <td>{{ $developer->whatsapp }}</td>

                                            <td>
                                                <ul>
                                                    @foreach($developer->awards as $award)
                                                        <li>{{ $award->award_title }} ({{ $award->award_year }})</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>
                                                <div class="developer_image">
                                                    <img src="{{ asset('storage/' . $developer->photo) }}" alt="Developer Photo" width="100">
                                                </div>

                                            </td>
                                            <td>
                                                <a href="{{ route('admin.developer.edit', $developer->id) }}" class="btn btn-primary">Edit</a>
                                                <form action="{{ route('admin.developer.delete', $developer->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
