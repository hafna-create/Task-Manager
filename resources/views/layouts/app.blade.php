<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | Task Manager</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --violet-50:  #f5f3ff;
            --violet-100: #ede9fe;
            --violet-200: #ddd6fe;
            --violet-500: #8b5cf6;
            --violet-600: #7c3aed;
            --violet-700: #6d28d9;
            --violet-900: #4c1d95;
            --ink:        #1f1535;

            --bs-primary: #7c3aed;
            --bs-primary-rgb: 124, 58, 237;
            --bs-link-color: #6d28d9;
            --bs-link-hover-color: #4c1d95;
            --bs-body-color: #2d2540;
            --bs-body-font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
            --bs-border-radius: .6rem;
        }

        body {
            background: linear-gradient(180deg, #f5f3ff 0%, #ffffff 320px) no-repeat, #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main { flex: 1; }

        /* ---------- Navbar ---------- */
        .app-navbar {
            background: linear-gradient(135deg, #4c1d95 0%, #6d28d9 60%, #7c3aed 100%);
            box-shadow: 0 4px 18px rgba(76, 29, 149, .25);
            padding: .75rem 0;
        }
        .app-navbar .navbar-brand {
            font-weight: 700;
            letter-spacing: .3px;
            display: flex;
            align-items: center;
            gap: .6rem;
        }
        .brand-mark {
            width: 32px; height: 32px;
            border-radius: 9px;
            background: #fff;
            color: var(--violet-700);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .95rem;
        }
        .app-navbar .nav-link {
            color: rgba(255, 255, 255, .8);
            font-weight: 500;
            padding: .45rem .95rem;
            border-radius: 999px;
            margin: 0 .15rem;
            transition: all .2s;
        }
        .app-navbar .nav-link:hover { color: #fff; background: rgba(255, 255, 255, .12); }
        .app-navbar .nav-link.active { color: #fff; background: rgba(255, 255, 255, .22); }
        .app-navbar .btn-outline-light {
            border-radius: 999px;
            padding: .35rem 1rem;
            border-color: rgba(255, 255, 255, .55);
        }
        .app-navbar .btn-outline-light:hover { background: #fff; color: var(--violet-700); }

        /* ---------- Headings ---------- */
        h4, h5 { color: var(--violet-900); font-weight: 600; }

        /* ---------- Cards ---------- */
        .card {
            border: 1px solid var(--violet-100);
            border-radius: 1rem;
            box-shadow: 0 6px 24px rgba(109, 40, 217, .07);
            overflow: hidden;
        }
        .card.shadow-sm { box-shadow: 0 6px 24px rgba(109, 40, 217, .07) !important; }
        .card-header {
            background: #fff;
            color: var(--violet-900);
            font-weight: 600;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--violet-100);
        }
        .card-body { padding: 1.5rem; }
        .card h2 { color: var(--violet-700); font-weight: 700; }

        /* ---------- Tables ---------- */
        .table { --bs-table-hover-bg: var(--violet-50); vertical-align: middle; }
        .table thead th {
            background: var(--violet-50);
            color: var(--violet-900);
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            font-weight: 600;
            border-bottom: 1px solid var(--violet-200);
            padding: .9rem 1rem;
        }
        .table td { padding: .85rem 1rem; border-color: var(--violet-100); }

        /* ---------- Buttons ---------- */
        .btn { border-radius: .6rem; font-weight: 500; }
        .btn-primary {
            --bs-btn-bg: #7c3aed;
            --bs-btn-border-color: #7c3aed;
            --bs-btn-hover-bg: #6d28d9;
            --bs-btn-hover-border-color: #6d28d9;
            --bs-btn-active-bg: #5b21b6;
            --bs-btn-active-border-color: #5b21b6;
            --bs-btn-disabled-bg: #7c3aed;
            --bs-btn-disabled-border-color: #7c3aed;
            --bs-btn-focus-shadow-rgb: 124, 58, 237;
            box-shadow: 0 4px 12px rgba(124, 58, 237, .3);
        }
        .btn-outline-primary {
            --bs-btn-color: #6d28d9;
            --bs-btn-border-color: #c4b5fd;
            --bs-btn-hover-bg: #7c3aed;
            --bs-btn-hover-border-color: #7c3aed;
            --bs-btn-active-bg: #6d28d9;
            --bs-btn-active-border-color: #6d28d9;
        }
        .btn-outline-secondary {
            --bs-btn-color: #5b21b6;
            --bs-btn-border-color: #ddd6fe;
            --bs-btn-hover-bg: #ede9fe;
            --bs-btn-hover-color: #4c1d95;
            --bs-btn-hover-border-color: #c4b5fd;
            --bs-btn-active-bg: #ddd6fe;
            --bs-btn-active-color: #4c1d95;
        }
        .btn-link { color: var(--violet-700); text-decoration: none; }
        .btn-link:hover { color: var(--violet-900); text-decoration: underline; }

        /* ---------- Badges ---------- */
        .badge {
            border-radius: 999px;
            padding: .45em .85em;
            font-weight: 500;
            letter-spacing: .02em;
        }
        .badge.bg-warning, .badge.bg-info { color: var(--ink) !important; }

        /* ---------- Forms ---------- */
        .form-label { font-weight: 500; color: var(--violet-900); font-size: .9rem; }
        .form-control, .form-select { border-color: var(--violet-200); padding: .6rem .85rem; }
        .form-control:focus, .form-select:focus {
            border-color: var(--violet-500);
            box-shadow: 0 0 0 .22rem rgba(124, 58, 237, .18);
        }
        .form-check-input:checked { background-color: var(--violet-600); border-color: var(--violet-600); }
        .form-check-input:focus { border-color: var(--violet-500); box-shadow: 0 0 0 .22rem rgba(124, 58, 237, .18); }

        /* ---------- Alerts ---------- */
        .alert { border: 0; border-left: 4px solid; border-radius: .75rem; box-shadow: 0 2px 10px rgba(0, 0, 0, .04); }
        .alert-success { border-left-color: #198754; }
        .alert-danger  { border-left-color: #dc3545; }

        /* ---------- Pagination ---------- */
        .pagination {
            --bs-pagination-color: #6d28d9;
            --bs-pagination-hover-color: #4c1d95;
            --bs-pagination-hover-bg: #ede9fe;
            --bs-pagination-focus-color: #4c1d95;
            --bs-pagination-focus-bg: #ede9fe;
            --bs-pagination-active-bg: #7c3aed;
            --bs-pagination-active-border-color: #7c3aed;
            --bs-pagination-border-color: #ddd6fe;
        }

        /* ---------- Footer ---------- */
        .app-footer {
            text-align: center;
            padding: 1.25rem 0;
            color: #8b7fa8;
            font-size: .85rem;
            border-top: 1px solid var(--violet-100);
            background: #fff;
        }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark app-navbar mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">
            <span class="brand-mark">T</span> TaskManager
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                @auth('admin')
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}">Projects</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}" href="{{ route('admin.staff.index') }}">Staff</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}" href="{{ route('admin.tasks.index') }}">Tasks</a></li>
                @endauth
                @auth('staff')
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('staff.dashboard', 'staff.tasks.*') ? 'active' : '' }}" href="{{ route('staff.dashboard') }}">My Tasks</a></li>
                @endauth
            </ul>

            <div class="d-flex">
                @auth('admin')
                    <form method="POST" action="{{ route('admin.logout') }}">@csrf
                        <button class="btn btn-outline-light btn-sm">Logout ({{ auth('admin')->user()->name }})</button>
                    </form>
                @endauth
                @auth('staff')
                    <form method="POST" action="{{ route('staff.logout') }}">@csrf
                        <button class="btn btn-outline-light btn-sm">Logout ({{ auth('staff')->user()->name }})</button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</nav>

<main class="container pb-5">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    @yield('content')
</main>

<footer class="app-footer">&copy; {{ date('Y') }} Task Manager. All rights reserved.</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>