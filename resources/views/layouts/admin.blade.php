<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <!-- Bootstrap 4 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <style>
        body {
            background: #f4f6f9;
            overflow-x: hidden;
        }

        /* SIDEBAR */
        .sidebar {
            height: 100vh;
            width: 240px;
            position: fixed;
            top: 0;
            left: 0;
            background: #1e1e2d;
            color: #fff;
            transition: all 0.3s;
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
            font-size: 20px;
            font-weight: bold;
            padding: 15px 20px;
            background: #111;
        }

        /* CONTENT */
        .content {
            margin-left: 240px;
            padding: 20px;
            transition: all 0.3s;
        }

        /* NAVBAR */
        .topbar {
            background: #fff;
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
        }

        /* COLLAPSED */
        .sidebar-collapsed .sidebar {
            width: 70px;
        }

        .sidebar-collapsed .sidebar a span {
            display: none;
        }

        .sidebar-collapsed .content {
            margin-left: 70px;
        }
        /* DARK MODE */
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

        body.dark-mode a {
            color: #ccc;
        }
        /* applicant datatable */
        #applicantsTable {
        font-size: 13px;
        }

        #applicantsTable th,
        #applicantsTable td {
            padding: 6px 8px !important;
            vertical-align: middle;
        }

        .dataTables_wrapper .dataTables_filter input {
            height: 30px;
            font-size: 13px;
        }

        .dataTables_wrapper .dataTables_length select {
            height: 30px;
            font-size: 13px;
        }

        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            font-size: 12px;
        }

        .badge {
            font-size: 11px;
            padding: 4px 6px;
        }
        /* MOBILE */
        @media(max-width: 768px){
            .sidebar {
                left: -240px;
            }

            .sidebar.show {
                left: 0;
            }

            .content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <div class="brand">⚙️ RFTP</div>

    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
    </a>

    <a href="{{ route('admin.applicants') }}" class="{{ request()->routeIs('admin.applicants') ? 'active' : '' }}">
        <i class="bi bi-people"></i> <span>Applicants</span>
    </a>

    <hr style="border-color:#444;">

<a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
    <i class="bi bi-gear"></i> <span>Settings</span>
</a>
</div>

<!-- CONTENT -->
<div class="content" id="content">

    <!-- TOPBAR -->
    <div class="topbar d-flex justify-content-between align-items-center">

        <!-- TOGGLE -->
        <button class="btn btn-sm btn-dark" id="toggleSidebar">
            <i class="bi bi-list"></i>
        </button>

        <!-- USER -->
        <div class="dropdown">
            <a href="#" class="dropdown-toggle text-dark" data-toggle="dropdown">
                👤 {{ auth()->user()->name ?? 'Admin' }}
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#">Profile</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="dropdown-item text-danger">Logout</button>
                </form>
            </div>
        </div>

    </div>

    <!-- PAGE CONTENT -->
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

    // 🔥 FIX ALIGNMENT (ITO LANG IMPORTANTE)
    setTimeout(() => {
        if (table) {
            table.columns.adjust();
        }
    }, 300);

});
</script>
<script>
    // AUTO APPLY THEME ON LOAD
    document.addEventListener("DOMContentLoaded", function () {
        let theme = localStorage.getItem('theme') || 'light';

        if(theme === 'dark'){
            document.body.classList.add('dark-mode');
        }
    });
</script>

@stack('scripts')

</body>
</html>