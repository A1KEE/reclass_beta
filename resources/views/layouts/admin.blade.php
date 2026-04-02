<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration Panel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('images/DO-LOGO.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/DO-LOGO.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/DO-LOGO.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

    <style>
    body {
        background: #f4f6f9;
        overflow-x: hidden;
    }

    /* =========================
       SIDEBAR
    ========================= */
    .sidebar {
        height: 100vh;
        width: 240px;
        position: fixed;
        top: 0;
        left: 0;
        background: #1e1e2d;
        color: #fff;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
    }

    .sidebar a {
        color: #cfcfcf;
        display: block;
        padding: 12px 20px;
        text-decoration: none;
    }

    .sidebar a:hover,
    .sidebar a.active {
        background: #343a40;
        color: #fff;
    }

    .sidebar .brand {
        font-size: 18px;
        font-weight: bold;
        padding: 15px 20px;
        background: #111;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sidebar .brand img {
        width: 28px;
        height: 28px;
    }

    .sidebar-menu {
        flex: 1;
    }

    .sidebar-footer {
        padding: 15px;
    }

    .user-card {
        background: #111;
        border-radius: 10px;
        padding: 12px;
        text-align: center;
    }

    /* =========================
       COLLAPSED SIDEBAR
    ========================= */
    .sidebar-collapsed .sidebar {
        width: 70px;
    }

    .sidebar-collapsed .content {
        margin-left: 70px;
    }

    .sidebar-collapsed .sidebar a span,
    .sidebar-collapsed .brand span {
        display: none;
    }

    .sidebar-collapsed .brand {
        justify-content: center;
    }

    /* =========================
       CONTENT
    ========================= */
    .content {
        margin-left: 240px;
        padding: 20px;
        transition: all 0.3s;
    }

    .topbar {
        background: #fff;
        padding: 10px 20px;
        border-bottom: 1px solid #ddd;
    }

    /* =========================
       DARK MODE
    ========================= */
    body.dark-mode {
        background: #121212;
        color: #eee;
    }

    body.dark-mode .topbar {
        background: #1f1f1f;
        border-color: #333;
    }

    body.dark-mode .sidebar {
        background: #111;
    }

    body.dark-mode .card {
        background: #1e1e1e;
        color: #fff;
    }

    /* =========================
       STAT CARDS (MODERN UI)
    ========================= */

    .stat-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        transition: 0.25s ease-in-out;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-4px);
    }

    /* FULL WIDTH ROW FIX (4 cards same row) */
    .stats-row {
    display: flex;
    flex-wrap: wrap;
    gap: 1px; /* mas dikit */
}

.stats-row .col-md-3 {
    flex: 1;
    min-width: 220px; /* para hindi sobrang sikip sa small screens */
}

    /* LEFT BORDER COLORS */
    .total-card {
        border-left: 5px solid #343a40;
    }

    .pending-card {
        border-left: 5px solid #ffc107;
    }

    .draft-card {
        border-left: 5px solid #0dcaf0;
    }

    .evaluated-card {
        border-left: 5px solid #198754;
    }

    /* ICON */
    .icon-box i {
        font-size: 30px;
        opacity: 0.85;
    }

    .pending-card .icon-box i {
        color: #ffc107;
    }

    .draft-card .icon-box i {
        color: #0dcaf0;
    }

    .evaluated-card .icon-box i {
        color: #198754;
    }

    /* =========================
       BADGES (STATUS COLORS)
    ========================= */

    .badge {
        padding: 6px 10px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 12px;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #000;
    }

    .badge-info {
        background-color: #0dcaf0;
        color: #fff;
    }

    .badge-success {
        background-color: #198754;
        color: #fff;
    }

    .badge-primary {
        background-color: #0d6efd;
        color: #fff;
    }

    /* =========================
       USER / ADMIN UI FIX
    ========================= */

    .user-info strong {
        font-size: 12px;
    }

    .user-info small {
        font-size: 11px;
        color: #aaa;
    }
#applicantsTable {
    font-size: 12px;
}

#applicantsTable thead th,
#applicantsTable tbody td {
    padding: 4px 6px;
}
#pageTitle {
    margin-left: 12px;
}
/* AVATAR CIRCLE */
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #0d6efd;
    color: #fff;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

/* USER TEXT */
.admin-name {
    font-size: 13px;
    font-weight: 600;
}

.admin-role {
    font-size: 11px;
    color: #aaa;
}

/* COLLAPSED STATE */
.sidebar-collapsed .user-text {
    display: none;
}

.sidebar-collapsed .sidebar-user {
    justify-content: center;
}
    /* =========================
       MOBILE FIX
    ========================= */
    @media(max-width: 768px) {
        .sidebar {
            left: -240px;
        }

        .sidebar.show {
            left: 0;
        }

        .content {
            margin-left: 0;
        }

        /* fallback for cards */
        .stats-row {
            flex-wrap: wrap;
        }
    }
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

    <div class="brand">
        <img src="{{ asset('images/DO-LOGO.png') }}" alt="DO LOGO">
        <span>Reclassification</span>
    </div>

    <!-- MENU -->
    <div class="sidebar-menu">
        <a href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.applicants') }}">
            <i class="bi bi-people"></i> <span>Applicants</span>
        </a>

        <!-- DO NOT CHANGE THIS LINE (AS REQUESTED) -->
        <hr style="border-color:#444;">

        <a href="{{ route('admin.settings') }}">
            <i class="bi bi-gear"></i> <span>Settings</span>
        </a>
    </div>

    <!-- FOOTER -->
<div class="sidebar-footer">

    @php
    $name = auth()->user()->name ?? 'Admin';
    $words = explode(' ', $name);
    $initials = strtoupper(substr($words[0],0,1) . (isset($words[1]) ? substr($words[1],0,1) : ''));
@endphp

<div class="user-card sidebar-user d-flex align-items-center">

    <!-- AVATAR -->
    <div class="avatar-circle">
        {{ $initials }}
    </div>

    <!-- USER INFO -->
    <div class="user-text ms-2">
        <div class="admin-name">
            {{ $name }}
        </div>
        <small class="admin-role">Administrator</small>
    </div>

</div>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button class="btn btn-sm btn-danger btn-block mt-2">
        <i class="bi bi-box-arrow-right"></i>
        <span class="logout-text">Logout</span>
    </button>
</form>
    </div>
</div>
</div>

<!-- CONTENT -->
<div class="content" id="content">

    <div class="topbar d-flex justify-content-between align-items-center">

    <!-- LEFT: TOGGLE + TITLE -->
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-sm btn-dark" id="toggleSidebar">
            <i class="bi bi-list"></i>
        </button>

        <h6 class="mb-0 fw-bold" id="pageTitle">
            @yield('page-title', 'Dashboard')
        </h6>
    </div>

    <!-- RIGHT: DATE & TIME -->
    <div class="text-end small">
    <div>Philippine Standard Time:</div>
    <div>
        <strong id="pstDateTime"></strong>
    </div>
</div>
</div>


    <div class="mt-3">
        @yield('content')
    </div>

</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const toggleBtn = document.getElementById('toggleSidebar');
const body = document.body;
const sidebar = document.getElementById('sidebar');

toggleBtn.addEventListener('click', function () {
    body.classList.toggle('sidebar-collapsed');
    sidebar.classList.toggle('show');

    setTimeout(() => {
        if (typeof table !== "undefined") {
            table.columns.adjust();
        }
        window.dispatchEvent(new Event('resize'));
    }, 300);
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let theme = localStorage.getItem('theme') || 'light';
    if(theme === 'dark'){
        document.body.classList.add('dark-mode');
    }
});
</script>
<script>
  function updateDateTime() {
    const now = new Date();

    const options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: '2-digit',
        hour: 'numeric',
        minute: '2-digit',
        second: '2-digit',
        hour12: true
    };

    const formatted = now.toLocaleString('en-US', options);

    document.getElementById('pstDateTime').textContent = formatted;
}

updateDateTime();
setInterval(updateDateTime, 1000);
</script>

@stack('scripts')

</body>
</html>