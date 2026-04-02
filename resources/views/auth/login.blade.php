<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | RECLASSIFICATION FORM FOR TEACHING POSITIONS</title>

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
        .login-card {
            width: 400px;
        }
        .login-card .card-body {
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

        <!-- HEADER (MOVED HIGHER) -->
        <div style="margin-top: -80px;">

            <!-- LOGOS -->
            <div class="d-flex justify-content-center align-items-center gap-3 mb-2">
                <img src="{{ asset('images/depEd-logo.png') }}" alt="DepEd Logo" style="height: 80px;">
                <img src="{{ asset('images/do-logo.png') }}" alt="DO Logo" style="height: 80px;">
            </div>

            <!-- REPUBLIC TITLE -->
            <div class="dep-rc-title"
                 style="font-size: 11pt; font-family: 'OldEnglishTextMT', 'Old English Text MT', serif;">
                Republika ng Pilipinas
            </div>

            <!-- DEPARTMENT -->
            <p class="lead mb-0"
               style="font-size: 16pt; font-family: 'OldEnglishTextMT', 'Old English Text MT', serif;">
                Department of Education
            </p>
            <p class="lead mb-0"
               style="font-size: 17pt;">
                Schools Division Office of Valenzuela City
            </p>
            <p class="lead mb-0"
               style="font-size: 10pt;">
                Pio Valenzuela Street, Brgy. Marulas, Valenzuela City, 1440
            </p><br><br>

        </div>

        <!-- RFTP (UNCHANGED) -->
        <h3 class="fw-bold mt-3">
            RECLASSIFICATION FORM FOR TEACHING POSITIONS (RFTP)
        </h3><br>

        <!-- TAGLINE (SLIGHTLY LOWERED) -->
        <small class="d-block mt-4">
            <i>Empowering quality educators through a transparent and efficient hiring process.</i>
        </small>

    </div>
</div>
        
<!-- RIGHT SIDE: LOGIN FORM -->
<div class="col-12 col-md-4 d-flex align-items-center justify-content-center">
    <div class="card shadow login-card mx-auto border-0">
        <div class="card-body px-4 py-5">

            <!-- TITLE -->
            <div class="text-center mb-4">
                <h4 class="fw-semibold login-title">Sign in</h4>
                <p class="text-muted small mb-0">Access your account</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- EMAIL -->
                <div class="mb-3">
                    <label for="email" class="form-label small text-muted">Email</label>
                    <input type="email" name="email" id="email"
                        value="{{ old('email') }}"
                        class="form-control modern-input"
                        placeholder="Enter your email"
                        required autofocus>
                </div>

                <!-- PASSWORD -->
                <div class="mb-3">
                    <label for="password" class="form-label small text-muted">Password</label>
                    <input type="password" name="password" id="password"
                        class="form-control modern-input"
                        placeholder="Enter your password"
                        required>
                </div>

                <!-- REMEMBER -->
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember_me">
                    <label class="form-check-label small text-muted" for="remember_me">
                        Remember me
                    </label>
                </div>

                <!-- ACTIONS -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small text-decoration-none">
                            Forgot password?
                        </a>
                    @endif

                    <button type="submit" class="btn btn-primary px-4 modern-btn">
                        Login
                    </button>
                </div>
            </form>

            <!-- FOOTER -->
            <div class="footer-text text-center mt-4 small text-muted">
                &copy; {{ date('Y') }} Information & Communication Technology (ICTU)
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>