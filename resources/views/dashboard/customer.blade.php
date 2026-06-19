@extends('layouts.customer')

@section('title', 'Customer Dashboard')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Selamat Datang</div>
                <h2 class="page-title">Dashboard Customer</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <span class="d-none d-sm-inline">
                        <a href="#" class="btn btn-white">
                            <i class="fas fa-calendar me-2"></i>{{ date('d F Y') }}
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Welcome Alert -->
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <i class="fas fa-wifi fa-2x me-3"></i>
                </div>
                <div>
                    <h4 class="alert-title">Halo, {{ $user->name }}!</h4>
                    <div class="text-secondary">
                        Selamat datang di ISP Booking System. Silakan booking paket internet untuk pemasangan di rumah
                        Anda.
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <!-- Statistics Cards -->
        <div class="row row-deck row-cards">
            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Booking</div>
                            <div class="ms-auto lh-1">
                                <i class="fas fa-calendar-check fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="h1 mb-3">{{ $totalBookings ?? 0 }}</div>
                        <div class="d-flex mb-2">
                            <a href="{{ route('customer.booking.index') }}" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-list me-2"></i>Lihat History
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Booking Aktif</div>
                            <div class="ms-auto lh-1">
                                <i class="fas fa-spinner fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="h1 mb-3">{{ $pendingBookings ?? 0 }}</div>
                        <div class="d-flex mb-2">
                            <a href="{{ route('customer.booking.index') }}" class="btn btn-sm btn-warning w-100">
                                <i class="fas fa-eye me-2"></i>Lihat Status
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Tagihan</div>
                            <div class="ms-auto lh-1">
                                <i class="fas fa-credit-card fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="h1 mb-3">{{ $totalUnpaid ?? 'Rp 0' }}</div>
                        <div class="d-flex mb-2">
                            <a href="{{ route('customer.payments.index') }}" class="btn btn-sm btn-success w-100">
                                <i class="fas fa-money-bill me-2"></i>Bayar Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <a href="{{ route('customer.booking.create') }}"
                                    class="btn btn-outline-primary w-100 py-3">
                                    <i class="fas fa-wifi fa-2x mb-2 d-block"></i>
                                    Booking Baru
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('customer.booking.index') }}" class="btn btn-outline-info w-100 py-3">
                                    <i class="fas fa-history fa-2x mb-2 d-block"></i>
                                    History Booking
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('customer.payments.index') }}"
                                    class="btn btn-outline-success w-100 py-3">
                                    <i class="fas fa-credit-card fa-2x mb-2 d-block"></i>
                                    Kelola Pembayaran
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Booking Terbaru Anda</h3>
                        <div class="card-actions">
                            <a href="{{ route('customer.booking.index') }}" class="btn btn-sm btn-primary">
                                Lihat Semua <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Tanggal Booking</th>
                                    <th>Paket</th>
                                    <th>Alamat</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($myBookings as $booking)
                                <tr>
                                    <td>{{ $booking->tanggal_booking->format('d/m/Y') }}</td>
                                    <td>{{ $booking->paket->nama_paket }}<br>
                                        <small class="text-muted">{{ $booking->paket->speed }}</small>
                                    </td>
                                    <td>{{ Str::limit($booking->alamat_pasang, 40) }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ \App\Models\Booking::getStatusBadge($booking->status) }}">
                                            {{ \App\Models\Booking::getStatusLabel($booking->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('customer.booking.show', $booking->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        Belum ada booking. Silakan buat booking baru.
                                        <div class="mt-3">
                                            <a href="{{ route('customer.booking.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Booking Sekarang
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection