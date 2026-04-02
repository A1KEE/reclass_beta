<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Change Password | RFTP</title>

    <!-- Bootstrap CSS -->
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

        /* RIGHT CARD */
        .login-card {
            width: 400px;
        }

        .login-card .card-body {
            padding: 2.5rem;
        }

        .btn-primary {
            background-color: #0d1f5f;
            border-color: #0d1f5f;
        }

        .btn-primary:hover {
            background-color: #0a1845;
            border-color: #0a1845;
        }

        .form-control:focus {
            border-color: #0d1f5f;
            box-shadow: 0 0 0 0.2rem rgba(13,31,95,.15);
        }

        .footer-text {
            font-size: 0.8rem;
            text-align: center;
            margin-top: 15px;
            color: #6c757d;
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

                <div style="margin-top: -80px;">

                    <div class="d-flex justify-content-center align-items-center gap-3 mb-2">
                        <img src="{{ asset('images/depEd-logo.png') }}" style="height:80px;">
                        <img src="{{ asset('images/do-logo.png') }}" style="height:80px;">
                    </div>

                    <div style="font-size:11pt; font-family: serif;">
                        Republika ng Pilipinas
                    </div>

                    <p style="font-size:16pt; font-family: serif;">
                        Department of Education
                    </p>

                    <p style="font-size:17pt;">
                        Schools Division Office of Valenzuela City
                    </p>

                    <p style="font-size:10pt;">
                        Pio Valenzuela Street, Brgy. Marulas, Valenzuela City, 1440
                    </p>

                    <br><br>

                </div>

                <h3 class="fw-bold mt-3">
                    RECLASSIFICATION FORM FOR TEACHING POSITIONS (RFTP)
                </h3>

                <small class="d-block mt-4">
                    <i>Empowering quality educators through a transparent and efficient hiring process.</i>
                </small>

            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="col-12 col-md-4 d-flex align-items-center justify-content-center">

            <div class="card shadow login-card border-0 mx-auto">
                <div class="card-body">

                    <!-- TITLE -->
                    <div class="text-center mb-4">
                        <h4 class="fw-semibold">Change Password</h4>
                        <p class="text-muted small mb-0">Secure your account</p>
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

                    <!-- FORM -->
                    <form method="POST" action="{{ route('change.password.update') }}">
                        @csrf

                        <!-- CURRENT PASSWORD -->
                        <div class="mb-3">
                            <label class="form-label small text-muted">Current Password</label>
                            <input type="password" name="current_password"
                                class="form-control"
                                placeholder="Enter current password"
                                required>
                        </div>

                        <!-- NEW PASSWORD -->
                        <div class="mb-3">
                            <label class="form-label small text-muted">New Password</label>
                            <input type="password" name="new_password"
                                class="form-control"
                                placeholder="Enter new password"
                                required>
                        </div>

                        <!-- CONFIRM PASSWORD -->
                        <div class="mb-3">
                            <label class="form-label small text-muted">Confirm Password</label>
                            <input type="password" name="new_password_confirmation"
                                class="form-control"
                                placeholder="Confirm new password"
                                required>
                        </div>

                        <!-- BUTTON -->
                        <button type="submit" class="btn btn-primary w-100">
                            Update Password
                        </button>
                    </form>

                    <!-- FOOTER -->
                    <div class="footer-text mt-4">
                        &copy; {{ date('Y') }} ICTU System
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

</body>
</html>