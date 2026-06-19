<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ISP Booking - @yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
    /* Custom CSS untuk sidebar sticky */
    .app-wrapper {
        display: flex;
        min-height: 100vh;
    }

    /* Sidebar kiri - sticky */
    .app-sidebar {
        position: sticky;
        top: 0;
        height: 100vh;
        width: 260px;
        background: #f8fafc;
        border-right: 1px solid #e9ecef;
        flex-shrink: 0;
        overflow-y: auto;
        z-index: 1000;
        transition: transform 0.3s ease;
    }

    /* Untuk mobile - sidebar tersembunyi */
    @media (max-width: 768px) {
        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            transform: translateX(-100%);
            z-index: 1050;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .app-sidebar.show {
            transform: translateX(0);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }

        .sidebar-overlay.show {
            display: block;
        }
    }

    /* Main content area */
    .app-content {
        flex: 1;
        min-width: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
    }

    /* Navbar di dalam content area */
    .app-navbar {
        position: sticky;
        top: 0;
        z-index: 100;
        background: white;
        border-bottom: 1px solid #e9ecef;
    }

    /* Page content */
    .page-content {
        flex: 1;
        padding: 20px;
    }

    /* Sidebar navigation styles */
    .sidebar-nav {
        padding: 20px 0;
    }

    .sidebar-brand {
        padding: 20px 24px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
    }

    .sidebar-brand h1 {
        font-size: 20px;
        margin: 0;
    }

    .sidebar-nav .nav-item {
        margin: 2px 12px;
    }

    .sidebar-nav .nav-link {
        display: flex;
        align-items: center;
        padding: 10px 16px;
        border-radius: 8px;
        color: #495057;
        text-decoration: none;
        transition: all 0.2s;
    }

    .sidebar-nav .nav-link:hover {
        background: #e9ecef;
        color: #1a1a1a;
    }

    .sidebar-nav .nav-item.active .nav-link {
        background: #206bc4;
        color: white;
    }

    .sidebar-nav .nav-link i {
        width: 20px;
        margin-right: 12px;
        text-align: center;
    }

    /* Scrollbar styling untuk sidebar */
    .app-sidebar::-webkit-scrollbar {
        width: 4px;
    }

    .app-sidebar::-webkit-scrollbar-track {
        background: transparent;
    }

    .app-sidebar::-webkit-scrollbar-thumb {
        background: #c1c7cd;
        border-radius: 2px;
    }

    .app-sidebar::-webkit-scrollbar-thumb:hover {
        background: #a8b0b8;
    }

    /* Tombol toggle mobile */
    .sidebar-toggle {
        display: none;
        background: none;
        border: none;
        font-size: 24px;
        padding: 8px;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .sidebar-toggle {
            display: block;
        }
    }
    </style>

    @stack('styles')
</head>

<body>
    <div class="app-wrapper">
        <!-- Overlay untuk mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- SIDEBAR KIRI - STICKY -->
        <aside class="app-sidebar" id="appSidebar">
            <div class="sidebar-brand">
                <i class="fas fa-globe text-primary me-2"></i>
                <h1 class="fw-bold">ISP Booking</h1>
            </div>

            <nav class="sidebar-nav">
                <ul class="nav flex-column">
                    <!-- Dashboard -->
                    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- Paket Internet -->
                    <li class="nav-item {{ request()->routeIs('admin.paket.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.paket.index') }}">
                            <i class="fas fa-wifi"></i>
                            <span>Paket Internet</span>
                        </a>
                    </li>

                    <!-- Teknisi -->
                    <li class="nav-item {{ request()->routeIs('admin.teknisi.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.teknisi.index') }}">
                            <i class="fas fa-wrench"></i>
                            <span>Teknisi</span>
                        </a>
                    </li>

                    <!-- Customer -->
                    <li class="nav-item {{ request()->routeIs('admin.customer.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.customer.index') }}">
                            <i class="fas fa-users"></i>
                            <span>Customer</span>
                        </a>
                    </li>

                    <!-- Coverage Area -->
                    <li class="nav-item {{ request()->routeIs('admin.coverage.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.coverage.index') }}">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Coverage Area</span>
                        </a>
                    </li>

                    <!-- Manajemen Booking -->
                    <li class="nav-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.bookings.index') }}">
                            <i class="fas fa-calendar-check"></i>
                            <span>Manajemen Booking</span>
                        </a>
                    </li>

                    <!-- Report & Statistik -->
                    <li class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.reports.index') }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Report & Statistik</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="app-content">
            <!-- Navbar -->
            <nav class="app-navbar">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between py-2">
                        <div>
                            <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                                <i class="fas fa-bars"></i>
                            </button>
                        </div>

                        <div class="d-flex align-items-center">
                            <!-- Notifikasi Bell -->
                            <div class="dropdown me-3">
                                <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                                    id="notificationBell">
                                    <i class="fas fa-bell fa-lg"></i>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="badge bg-red ms-1"
                                        id="notificationCount">{{ auth()->user()->unreadNotifications->count() }}</span>
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-card" style="width: 350px;">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Notifikasi</h3>
                                            <div class="card-actions">
                                                <form method="POST" action="{{ route('notifications.mark-all-read') }}"
                                                    id="markAllReadForm" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-link" id="markAllRead"
                                                        style="text-decoration: none; padding: 0;">
                                                        Tandai semua dibaca
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="list-group list-group-flush"
                                            style="max-height: 400px; overflow-y: auto;">
                                            @forelse(auth()->user()->latestNotifications as $notif)
                                            <a href="{{ route('notifications.read', $notif->id) }}"
                                                class="list-group-item list-group-item-action {{ $notif->read_at ? '' : 'bg-light' }}">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        @if(isset($notif->data['type']))
                                                        @if($notif->data['type'] == 'booking_approved')
                                                        <i class="fas fa-check-circle text-success fa-lg"></i>
                                                        @elseif($notif->data['type'] == 'payment_confirmed')
                                                        <i class="fas fa-credit-card text-primary fa-lg"></i>
                                                        @elseif($notif->data['type'] == 'teknisi_assigned')
                                                        <i class="fas fa-wrench text-warning fa-lg"></i>
                                                        @elseif($notif->data['type'] == 'new_payment')
                                                        <i class="fas fa-money-bill text-info fa-lg"></i>
                                                        @elseif($notif->data['type'] == 'installation_completed')
                                                        <i class="fas fa-check-double text-success fa-lg"></i>
                                                        @else
                                                        <i class="fas fa-bell text-secondary fa-lg"></i>
                                                        @endif
                                                        @else
                                                        <i class="fas fa-bell text-secondary fa-lg"></i>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <div class="fw-bold">{{ $notif->data['title'] ?? 'Notifikasi' }}
                                                        </div>
                                                        <div class="text-secondary small">
                                                            {{ Str::limit($notif->data['message'] ?? '', 60) }}</div>
                                                        <div class="text-muted small mt-1">
                                                            {{ $notif->created_at->diffForHumans() }}</div>
                                                    </div>
                                                </div>
                                            </a>
                                            @empty
                                            <div class="text-center py-3 text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                                Tidak ada notifikasi
                                            </div>
                                            @endforelse
                                            <a href="{{ route('notifications.index') }}"
                                                class="list-group-item list-group-item-action text-center">
                                                Lihat semua notifikasi
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- User Dropdown -->
                            <div class="dropdown">
                                <a href="#" class="d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown">
                                    <span class="avatar avatar-sm bg-primary rounded-circle">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <div class="d-none d-xl-block ps-2">
                                        <div>{{ auth()->user()->name }}</div>
                                        <div class="mt-1 small text-secondary">{{ ucfirst(auth()->user()->role) }}</div>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="page-content">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="footer footer-transparent d-print-none py-3 border-top">
                <div class="container-fluid">
                    <div class="row text-center align-items-center">
                        <div class="col-12">
                            <div class="text-secondary">
                                Copyright © {{ date('Y') }} ISP Booking System. All rights reserved.
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function() {
        // ==============================
        // TOGGLE SIDEBAR MOBILE
        // ==============================
        $('#sidebarToggle').on('click', function() {
            $('#appSidebar').toggleClass('show');
            $('#sidebarOverlay').toggleClass('show');
        });

        $('#sidebarOverlay').on('click', function() {
            $('#appSidebar').removeClass('show');
            $('#sidebarOverlay').removeClass('show');
        });

        // Close sidebar saat link diklik (mobile)
        $('.sidebar-nav .nav-link').on('click', function() {
            if (window.innerWidth <= 768) {
                $('#appSidebar').removeClass('show');
                $('#sidebarOverlay').removeClass('show');
            }
        });

        // ==============================
        // MARK ALL NOTIFICATIONS READ
        // ==============================
        $('#markAllReadForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('.list-group-item.bg-light').removeClass('bg-light');
                        $('#notificationCount').fadeOut('slow', function() {
                            $(this).remove();
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Error marking all notifications as read');
                }
            });
        });

        // ==============================
        // POLLING NOTIFICATIONS
        // ==============================
        setInterval(function() {
            $.ajax({
                url: "{{ route('notifications.unread-count') }}",
                method: 'GET',
                success: function(response) {
                    var count = response.count;
                    var $bell = $('#notificationBell');

                    if (count > 0) {
                        if ($bell.find('.badge').length) {
                            $bell.find('.badge').text(count);
                        } else {
                            $bell.append(
                                '<span class="badge bg-red ms-1" id="notificationCount">' +
                                count + '</span>');
                        }
                    } else {
                        $bell.find('.badge').remove();
                    }
                }
            });
        }, 30000);
    });
    </script>

    @stack('scripts')
</body>

</html>