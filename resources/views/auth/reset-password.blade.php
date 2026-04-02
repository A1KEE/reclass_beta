<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password | RFTP</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f0f4ff;
        }

        /* LEFT SIDE */
        .left-side {
            background: url('{{ asset('images/division.jpg') }}') center center/cover no-repeat;
            position: relative;
            color: white;
            background-color: #0d1f5f;
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
        .card {
            border-radius: 16px;
        }

        .login-card {
            width: 400px;
        }

        .modern-input {
            border-radius: 10px;
            padding: 10px 12px;
            border: 1px solid #ddd;
        }

        .modern-input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.15);
        }

        .btn-primary {
            background-color: #0d1f5f;
            border-color: #0d1f5f;
            border-radius: 10px;
        }

        .btn-primary:hover {
            background-color: #0a1845;
        }

        .footer-text {
            font-size: 0.8rem;
            color: #6c757d;
        }
    </style>
</head>
<body>

<div class="container-fluid vh-100">
    <div class="row h-100">

        <!-- LEFT SIDE (SAME AS LOGIN) -->
        <div class="col-md-8 d-none d-md-flex left-side align-items-center justify-content-center">
            <div class="left-overlay"></div>

            <div class="text-center left-content px-4">

                <div class="d-flex justify-content-center gap-3 mb-2">
                    <img src="{{ asset('images/depEd-logo.png') }}" style="height:80px;">
                    <img src="{{ asset('images/do-logo.png') }}" style="height:80px;">
                </div>

                <div style="font-size: 11pt; font-family: serif;">
                    Republika ng Pilipinas
                </div>

                <p class="lead mb-3" style="font-size:16pt; font-family: serif;">
                    Department of Education
                </p><br><br>

                <h3 class="fw-bold">
                    RECLASSIFICATION FORM FOR TEACHING POSITIONS (RFTP)
                </h3><br>

                <small class="d-block mt-4">
                    <i>Empowering quality educators through a transparent and efficient hiring process.</i>
                </small>

            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="col-12 col-md-4 d-flex align-items-center justify-content-center">
            <div class="card shadow login-card border-0">
                <div class="card-body px-4 py-5">

                    <!-- TITLE -->
                    <div class="text-center mb-4">
                        <h4 class="fw-semibold">Reset Password</h4>
                        <p class="text-muted small">Enter your new password</p>
                    </div>

                    <!-- ERRORS -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <!-- TOKEN -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- EMAIL -->
                        <div class="mb-3">
                            <label class="form-label small text-muted">Email</label>
                            <input type="email" name="email"
                                value="{{ old('email', $request->email) }}"
                                class="form-control modern-input" required autofocus readonly>
                        </div>

                        <!-- PASSWORD -->
                        <div class="mb-3">
                            <label class="form-label small text-muted">New Password</label>
                            <input type="password" name="password"
                                class="form-control modern-input" required>
                        </div>

                        <!-- CONFIRM -->
                        <div class="mb-4">
                            <label class="form-label small text-muted">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                class="form-control modern-input" required>
                        </div>

                        <!-- BUTTON -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Reset Password
                            </button>
                        </div>
                    </form>

                    <div class="footer-text text-center mt-4">
                        &copy; {{ date('Y') }} Information & Communication Technology (ICTU)
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>