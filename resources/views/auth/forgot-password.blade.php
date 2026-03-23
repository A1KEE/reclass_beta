<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password | RECLASSIFICATION FORM FOR TEACHING POSITIONS</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* LEFT SIDE */
        .left-side {
            background: url('{{ asset('images/division.jpg') }}') center center/cover no-repeat;
            position: relative;
            color: white;
            background-color: #0d1f5f; /* dark blue fallback */
        }
        .left-overlay {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, rgba(0,0,0,0.3) 100%);
        }
        .left-content {
            position: relative;
            z-index: 2;
        }

        /* RIGHT SIDE */
        .card-form {
            width: 400px;
        }
        .card-form .card-body {
            padding: 2.5rem;
        }
        .footer-text {
            font-size: 0.8rem;
            text-align: center;
            margin-top: 15px;
            color: #6c757d;
        }
        body {
            background-color: #f0f4ff; /* very light blue */
        }

        /* Center logo */
        .logo-img {
            display: block;
            margin: 0 auto 15px auto;
            width: 90px;
            border-radius: 50%;
        }

        /* Button hover */
        .btn-primary {
            background-color: #0d1f5f;
            border-color: #0d1f5f;
        }
        .btn-primary:hover {
            background-color: #0a1845;
            border-color: #0a1845;
        }
    </style>
</head>
<body>

<div class="container-fluid vh-100">
    <div class="row h-100">

        <!-- LEFT SIDE -->
        <div class="col-md-8 d-none d-md-flex left-side align-items-center justify-content-center">
            <div class="left-overlay"></div>
            <div class="text-center left-content px-4">
                <p class="lead">Department of Education</p>
                <h3 class="fw-bold">RECLASSIFICATION FORM FOR TEACHING POSITIONS (RFTP)</h3><br>
                <small>Empowering quality educators through a transparent and efficient hiring process.</small>
            </div>
        </div>

        <!-- RIGHT SIDE: FORGOT PASSWORD FORM -->
        <div class="col-12 col-md-4 d-flex align-items-center justify-content-center">
            <div class="card shadow card-form mx-auto">
                <div class="card-body text-center">
                    <!-- Centered Logo -->
                    <img src="{{ asset('images/DO-LOGO.png') }}" class="logo-img">
                    <h4 class="fw-bold mb-4">Forgot Password</h4>

                    <p class="text-muted mb-4 text-start">
                        Forgot your password? No problem. Just enter your email address and we will email you a password reset link.
                    </p>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger text-start">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3 text-start">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" required autofocus>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Email Password Reset Link</button>
                          <div class="mt-3 text-center">
        <a href="{{ route('login') }}" class="text-decoration-none text-primary">← Back to Login</a>
    </div>
                    </form>

                    <div class="footer-text mt-3">
                        &copy; {{ date('Y') }} Information & Communication Technology Unit(ICTU)
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>