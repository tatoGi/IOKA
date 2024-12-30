<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login To Admin Panel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link href="{{ asset('storage/admin/login/login.css') }}" rel="stylesheet">
</head>

<body>
    <div class="login-page min-vh-100 d-flex justify-content-center align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="login-form shadow rounded">
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <!-- Logo Section -->
                                <div class="text-center py-4">
                                    <div class="logo mx-auto">
                                        <img src="{{ asset('storage/admin/logo/logo.png') }}" alt="Admin Panel Logo"
                                             class="img-fluid" style="max-width: 150px;">
                                    </div>
                                </div>

                                <!-- Form Section -->
                                <div class="form-center py-3 px-5">
                                    <form action="{{ route('admin.login.submit') }}" method="POST" class="row g-4">
                                        @csrf
                                        @if ($errors->any())
                                            <div class="col-12">
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-12">
                                            <label>Username<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="bi bi-person-fill"></i></div>
                                                <input type="text" class="form-control" name="name"
                                                    placeholder="Enter Username" required>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label>Password<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="bi bi-lock-fill"></i></div>
                                                <input type="password" class="form-control" name="password"
                                                    placeholder="Enter Password" required>
                                            </div>
                                        </div>

                                        <!-- Google reCAPTCHA -->
                                        <div class="col-12">
                                            <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITEKEY') }}">
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit"
                                                class="btn btn-primary px-4 float-end mt-4">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
</body>

</html>
