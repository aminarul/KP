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
    /* ============================================
                   LAYOUT UTAMA: FLEX SIDEBAR + CONTENT
                   ============================================ */
    .app-wrapper {
        display: flex;
        min-height: 100vh;
    }

    /* ============================================
                   SIDEBAR KIRI - STICKY
                   ============================================ */
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

    /* Brand / Logo di sidebar */
    .sidebar-brand {
        padding: 20px 24px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sidebar-brand h1 {
        font-size: 20px;
        margin: 0;
    }

    /* Navigasi di sidebar */
    .sidebar-nav {
        padding: 16px 12px;
    }

    .sidebar-nav .nav-item {
        margin: 2px 0;
    }

    .sidebar-nav .nav-link {
        display: flex;
        align-items: center;
        padding: 10px 16px;
        border-radius: 8px;
        color: #495057;
        text-decoration: none;
        transition: all 0.2s;
        gap: 12px;
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
        text-align: center;
        font-size: 16px;
    }

    /* ============================================
                   MAIN CONTENT AREA
                   ============================================ */
    .app-content {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
    }

    /* Navbar atas (sticky) */
    .app-navbar {
        position: sticky;
        top: 0;
        z-index: 100;
        background: white;
        border-bottom: 1px solid #e9ecef;
        padding: 8px 20px;
    }

    /* Isi halaman */
    .page-content {
        flex: 1;
        padding: 24px 20px;
    }

    /* Footer */
    .app-footer {
        padding: 16px 20px;
        border-top: 1px solid #e9ecef;
        background: white;
    }

    /* ============================================
                   RESPONSIVE MOBILE
                   ============================================ */
    @media (max-width: 768px) {
        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            transform: translateX(-100%);
            z-index: 1050;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            width: 280px;
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

        .sidebar-toggle {
            display: block !important;
            background: none;
            border: none;
            font-size: 22px;
            padding: 4px 8px;
            cursor: pointer;
            color: #495057;
        }

        .app-navbar .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }
    }

    @media (min-width: 769px) {
        .sidebar-toggle {
            display: none !important;
        }
    }

    /* ============================================
                   TOMBOL TOGGLE SIDEBAR (mobile)
                   ============================================ */
    .sidebar-toggle {
        display: none;
    }

    /* ============================================
                   UTILITY: Avatar & Notifikasi
                   ============================================ */
    .avatar-sm {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .nav-link-icon {
        position: relative;
        color: #495057;
        padding: 6px 10px;
        border-radius: 6px;
        transition: background 0.2s;
    }

    .nav-link-icon:hover {
        background: #f1f3f5;
    }

    .badge-dot {
        position: absolute;
        top: 2px;
        right: 4px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #e53935;
        border: 2px solid white;
    }
    </style>

    @stack('styles')
</head>

<body>
    <div class="app-wrapper">

        <!-- ==========================================
        OVERLAY UNTUK MOBILE
        ========================================== -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- ==========================================
        SIDEBAR KIRI - STICKY
        ========================================== -->
        <aside class="app-sidebar" id="appSidebar">
            <!-- Brand -->
            <div class="sidebar-brand">
                <i class="fas fa-globe text-primary" style="font-size: 24px;"></i>
                <h1 class="fw-bold">ISP Booking</h1>
            </div>

            <!-- Navigasi -->
            <nav class="sidebar-nav">
                <ul class="nav flex-column">
                    <!-- Dashboard -->
                    <li class="nav-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('customer.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- Booking Baru -->
                    <li class="nav-item {{ request()->routeIs('customer.booking.create') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('customer.booking.create') }}">
                            <i class="fas fa-wifi"></i>
                            <span>Booking Baru</span>
                        </a>
                    </li>

                    <!-- History Booking -->
                    <li class="nav-item {{ request()->routeIs('customer.booking.index') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('customer.booking.index') }}">
                            <i class="fas fa-history"></i>
                            <span>History Booking</span>
                        </a>
                    </li>

                    <!-- Pembayaran -->
                    <li class="nav-item {{ request()->routeIs('customer.payments.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('customer.payments.index') }}">
                            <i class="fas fa-credit-card"></i>
                            <span>Pembayaran</span>
                        </a>
                    </li>

                    <!-- Profil Saya -->
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-user-circle"></i>
                            <span>Profil Saya</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- ==========================================
        MAIN CONTENT
        ========================================== -->
        <main class="app-content">

            <!-- Navbar Atas (Sticky) -->
            <nav class="app-navbar">
                <div class="navbar-content">
                    <!-- Kiri: Tombol toggle mobile + judul -->
                    <div class="d-flex align-items-center gap-2">
                        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                            <i class="fas fa-bars"></i>
                        </button>
                        <span class="fw-semibold d-md-none">ISP Booking</span>
                    </div>

                    <!-- Kanan: Notifikasi + User -->
                    <div class="d-flex align-items-center gap-3">

                        <!-- NOTIFIKASI BELL -->
                        <div class="dropdown">
                            <a href="#" class="nav-link-icon" data-bs-toggle="dropdown" tabindex="-1"
                                id="notificationBell">
                                <i class="fas fa-bell fa-lg"></i>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="badge bg-red ms-1" id="notificationCount">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                                @endif
                            </a>

                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-card" style="width: 360px;">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Notifikasi</h3>
                                        <div class="card-actions">
                                            <form method="POST" action="{{ route('notifications.mark-all-read') }}"
                                                id="markAllReadForm" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-link"
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
                                                    @if(isset($notif->data['icon']))
                                                    <i
                                                        class="fas {{ $notif->data['icon'] }} text-{{ $notif->data['color'] ?? 'secondary' }} fa-lg"></i>
                                                    @elseif(isset($notif->data['type']))
                                                    @if($notif->data['type'] == 'booking_approved')
                                                    <i class="fas fa-check-circle text-success fa-lg"></i>
                                                    @elseif($notif->data['type'] == 'payment_confirmed')
                                                    <i class="fas fa-credit-card text-primary fa-lg"></i>
                                                    @elseif($notif->data['type'] == 'teknisi_assigned')
                                                    <i class="fas fa-wrench text-warning fa-lg"></i>
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
                                                        {{ Str::limit($notif->data['message'] ?? '', 60) }}
                                                    </div>
                                                    <div class="text-muted small mt-1">
                                                        {{ $notif->created_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        @empty
                                        <div class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                            Tidak ada notifikasi
                                        </div>
                                        @endforelse
                                        <a href="{{ route('notifications.index') }}"
                                            class="list-group-item list-group-item-action text-center fw-semibold">
                                            Lihat semua notifikasi
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- USER DROPDOWN -->
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center gap-2 text-decoration-none text-reset"
                                data-bs-toggle="dropdown">
                                <span
                                    class="avatar avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-user text-white" style="font-size: 14px;"></i>
                                </span>
                                <div class="d-none d-lg-block">
                                    <div class="fw-semibold" style="font-size: 14px;">{{ auth()->user()->name }}</div>
                                    <div class="text-secondary" style="font-size: 12px;">Customer</div>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <a href="#" class="dropdown-item">
                                    <i class="fas fa-user-circle me-2"></i>Profil Saya
                                </a>
                                <a href="#" class="dropdown-item">
                                    <i class="fas fa-cog me-2"></i>Pengaturan
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </nav>

            <!-- ==========================================
            PAGE CONTENT
            ========================================== -->
            <div class="page-content">
                <!-- Flash Messages -->
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

            <!-- ==========================================
            FOOTER
            ========================================== -->
            <footer class="app-footer">
                <div class="container-fluid">
                    <div class="row text-center">
                        <div class="col-12">
                            <span class="text-secondary">
                                Copyright &copy; {{ date('Y') }} ISP Booking System. All rights reserved.
                            </span>
                        </div>
                    </div>
                </div>
            </footer>

        </main>
    </div>

    <!-- ==========================================
    SCRIPTS
    ========================================== -->
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
            $('body').toggleClass('overflow-hidden');
        });

        $('#sidebarOverlay').on('click', function() {
            $('#appSidebar').removeClass('show');
            $('#sidebarOverlay').removeClass('show');
            $('body').removeClass('overflow-hidden');
        });

        // Tutup sidebar saat link diklik (mobile)
        $('.sidebar-nav .nav-link').on('click', function() {
            if (window.innerWidth <= 768) {
                $('#appSidebar').removeClass('show');
                $('#sidebarOverlay').removeClass('show');
                $('body').removeClass('overflow-hidden');
            }
        });

        // Tutup sidebar saat resize ke desktop
        $(window).on('resize', function() {
            if (window.innerWidth > 768) {
                $('#appSidebar').removeClass('show');
                $('#sidebarOverlay').removeClass('show');
                $('body').removeClass('overflow-hidden');
            }
        });

        // ==============================
        // MARK ALL NOTIFICATIONS READ (AJAX)
        // ==============================
        $('#markAllReadForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        // Hapus background unread
                        $('.list-group-item.bg-light').removeClass('bg-light');
                        // Sembunyikan badge notifikasi
                        $('#notificationCount').fadeOut('slow', function() {
                            $(this).remove();
                        });
                        // Tampilkan pesan sukses (opsional)
                        if (response.message) {
                            console.log(response.message);
                        }
                    }
                },
                error: function(xhr) {
                    console.error('Gagal menandai semua notifikasi sebagai dibaca');
                }
            });
        });

        // ==============================
        // POLLING NOTIFIKASI (30 detik)
        // ==============================
        function refreshNotificationCount() {
            $.ajax({
                url: "{{ route('notifications.unread-count') }}",
                method: 'GET',
                success: function(response) {
                    var count = response.count || 0;
                    var $bell = $('#notificationBell');

                    // Hapus badge lama jika ada
                    $bell.find('.badge').remove();

                    if (count > 0) {
                        $bell.append('<span class="badge bg-red ms-1" id="notificationCount">' +
                            count + '</span>');
                    }
                },
                error: function() {
                    // Silent error - tidak perlu menampilkan pesan
                }
            });
        }

        // Jalankan pertama kali setelah 5 detik, lalu setiap 30 detik
        setTimeout(refreshNotificationCount, 5000);
        setInterval(refreshNotificationCount, 30000);

    });
    </script>

    @stack('scripts')
</body>

</html>