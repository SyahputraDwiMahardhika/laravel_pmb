<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PMB Online')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .navbar-brand { font-weight: 700; }
        .sidebar { min-height: calc(100vh - 56px); background: #1e293b; }
        .sidebar a { color: #cbd5e1; padding: .65rem 1rem; display: block; border-radius: .375rem; text-decoration: none; }
        .sidebar a:hover, .sidebar a.active { background: #334155; color: #fff; }
        .card-stat { border: none; border-radius: .75rem; box-shadow: 0 2px 6px rgba(0,0,0,.06); }
        .badge-status-Menunggu { background:#f59e0b; }
        .badge-status-Diverifikasi { background:#3b82f6; }
        .badge-status-Diterima { background:#22c55e; }
        .badge-status-Ditolak { background:#ef4444; }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="{{ route('informasi-pmb') }}"><i class="fa-solid fa-graduation-cap"></i> PMB Online</a>
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('informasi-pmb') }}" class="text-light text-decoration-none small">Informasi PMB</a>
        @auth
            <span class="text-light small">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button class="btn btn-sm btn-outline-light" type="submit">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light">Login</a>
        @endauth
    </div>
</nav>

<div class="d-flex">
    @hasSection('sidebar')
        <div class="sidebar p-3" style="width:230px;">
            @yield('sidebar')
        </div>
    @endif

    <div class="flex-grow-1 p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/pmb.js') }}"></script>
@stack('scripts')
</body>
</html>
