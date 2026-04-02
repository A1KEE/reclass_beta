<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f0f4ff;
        }

        .top-bar {
            background: #0d1f5f;
            color: white;
            padding: 15px 20px;
        }

        .card-box {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .btn-primary {
            background-color: #0d1f5f;
            border-color: #0d1f5f;
        }

        .btn-primary:hover {
            background-color: #0a1845;
        }
    </style>
</head>

<body>

<!-- HEADER -->
<div class="top-bar d-flex justify-content-between align-items-center">
    <div>
        <h5 class="mb-0">Applicant Dashboard</h5>
        <small>Welcome, {{ auth()->user()->name }}</small>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-light btn-sm">Logout</button>
    </form>
</div>

<div class="container py-4">

    <!-- STATUS CARD -->
    <div class="row">

        <div class="col-md-4 mb-3">
            <div class="card card-box p-3">
                <h6 class="text-muted">Application Status</h6>
                <h4 class="fw-bold text-primary">
                    {{ auth()->user()->application_id ? 'Submitted' : 'Not Submitted' }}
                </h4>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card card-box p-3">
                <h6 class="text-muted">Account Status</h6>
                <h4 class="fw-bold text-success">
                    Active
                </h4>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card card-box p-3">
                <h6 class="text-muted">Next Step</h6>
                <h4 class="fw-bold text-warning">
                    Awaiting Review
                </h4>
            </div>
        </div>

    </div>

    <!-- MAIN ACTION -->
    <div class="card card-box p-4 mt-3">

        <h5 class="mb-3">Quick Actions</h5>

        <div class="d-flex gap-2 flex-wrap">

            <a href="/applicants/create" class="btn btn-primary">
                Fill Application
            </a>

            <a href="#" class="btn btn-outline-primary">
                View My Application
            </a>

            <a href="{{ route('change.password') }}" class="btn btn-outline-secondary">
                Change Password
            </a>

        </div>

    </div>

</div>

</body>
</html>