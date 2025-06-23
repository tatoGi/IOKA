<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel</title>
    <link rel="icon" href="{{ asset('storage/admin/logo/logo.png') }}" type="image/x-icon">
    <!-- bootstrap 5 css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- BOX ICONS CSS-->
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/nestable2@1.6.0/jquery.nestable.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('storage/admin/nestable2/jquery.nestable.css') }}">
    <link rel="stylesheet" href="{{ asset('storage/admin/assets/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- TinyMCE CSS is loaded automatically -->

    @yield('styles')
</head>

<body>
    <!-- Side-Nav -->
    @include('admin.componenets.sidebar')

    <!-- Main Wrapper -->
    <div class="p-1 my-container active-cont">
        <!-- Top Nav -->
        @include('admin.componenets.header')
        <!--End Top Nav -->
        @yield('content')
    </div>

    <!-- bootstrap js -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('storage/admin/assets/main.js') }}"></script>
    <!-- TinyMCE Editor (Self-hosted version to avoid API key issues) -->
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6.7.2/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="{{ asset('admin/assets/tinymce-init.js') }}"></script>
    @stack('scripts')
</body>

</html>
