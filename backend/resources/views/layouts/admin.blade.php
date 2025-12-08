<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body class="admin-body">
    <div class="admin-shell">
        <div class="admin-wrapper">
            <aside class="admin-sidebar">
                <div class="admin-logo">
                    <h2>Locyse</h2>
                    <span>Admin Panel</span>
                </div>
                <nav class="admin-menu">
                    <a href="{{ route('admin.scan-barcode.index') }}" class="admin-menu-item @if(request()->routeIs('admin.scan-barcode.*')) active @endif">
                        <i class="menu-icon bi bi-qr-code"></i>
                        <span>Scan Barcode</span>
                    </a>
                    <a href="{{ route('admin.attendance.index') }}" class="admin-menu-item @if(request()->routeIs('admin.attendance.*')) active @endif">
                        <i class="menu-icon bi bi-check-circle"></i>
                        <span>Attendance</span>
                    </a>
                </nav>
            </aside>
            <main class="admin-content">
                <header class="admin-header">
                    <div class="admin-header-left">
                        <p class="admin-header-subtitle">Primary</p>
                        <h1 class="admin-header-title">@yield('page_title', 'Dashboard')</h1>
                    </div>
                    <div class="admin-header-right">
                        <div class="admin-search">
                            <input type="text" placeholder="Search" />
                        </div>
                        <div class="admin-avatar">LA</div>
                    </div>
                </header>
                <section class="admin-main">
                    @yield('content')
                </section>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
