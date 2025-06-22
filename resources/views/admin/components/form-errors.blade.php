@if ($errors->any())
    <div class="alert alert-danger">
        <h5><i class="icon fas fa-ban"></i> There were some errors with your submission!</h5>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
