@extends('layouts.customer')

@section('title', 'Detail Booking #' . $booking->id)

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Booking</div>
                <h2 class="page-title">Detail Booking #{{ $booking->id }}</h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('customer.booking.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-lg-8">
                <!-- Informasi Booking -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Booking</h3>
                        <div class="card-actions">
                            <span class="badge bg-{{ \App\Models\Booking::getStatusBadge($booking->status) }} fs-4">
                                {{ \App\Models\Booking::getStatusLabel($booking->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="35%"><strong>Paket Internet</strong></td>
                                <td>: {{ $booking->paket->nama_paket }} ({{ $booking->paket->speed }})</td>
                            </tr>
                            <tr>
                                <td><strong>Harga Paket</strong></td>
                                <td>: {{ $booking->paket->harga_formatted }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Pemasangan</strong></td>
                                <td>: {{ $booking->tanggal_booking->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat Pemasangan</strong></td>
                                <td>: {{ $booking->alamat_pasang }}</td>
                            </tr>
                            @if($booking->maps_link)<tr>
                                <td><strong>Google Maps</strong></td>
                                <td>: <a href="{{ $booking->maps_link }}" target="_blank">Lihat Lokasi</a></td>
                            </tr>@endif
                            @if($booking->catatan)<tr>
                                <td><strong>Catatan</strong></td>
                                <td>: {{ $booking->catatan }}</td>
                            </tr>@endif
                            <tr>
                                <td><strong>Status Pembayaran</strong></td>
                                <td>:
                                    <span
                                        class="badge bg-{{ \App\Models\Booking::getPaymentStatusBadge($booking->payment_status) }}">
                                        {{ \App\Models\Booking::getPaymentStatusLabel($booking->payment_status) }}
                                    </span>
                                </td>
                            </tr>
                            @if($booking->invoice_number)
                            <tr>
                                <td><strong>No. Invoice</strong></td>
                                <td>: {{ $booking->invoice_number }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Tombol Bayar -->
                @if($booking->status == 'approved' && $booking->payment_status == 'unpaid')
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title text-white">Pembayaran</h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="h2 text-success mb-3">{{ $booking->harga_formatted }}</div>
                        <p class="text-muted">Total yang harus dibayar</p>
                        <a href="{{ route('customer.booking.payment', $booking->id) }}"
                            class="btn btn-success w-100 py-3">
                            <i class="fas fa-credit-card fa-2x mb-2 d-block"></i>
                            Bayar Sekarang
                        </a>
                    </div>
                </div>
                @endif

                @if($booking->payment_status == 'pending')
                <div class="card mb-3">
                    <div class="card-header bg-warning">
                        <h3 class="card-title">Menunggu Verifikasi</h3>
                    </div>
                    <div class="card-body text-center">
                        <i class="fas fa-clock fa-3x text-warning mb-3 d-block"></i>
                        <p>Bukti pembayaran Anda sedang diverifikasi admin.</p>
                    </div>
                </div>
                @endif

                @if($booking->payment_status == 'paid')
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title text-white">Pembayaran Lunas</h3>
                    </div>
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle fa-3x text-success mb-3 d-block"></i>
                        <p>Pembayaran telah dikonfirmasi.</p>
                        @if($booking->status == 'on_progress')
                        <p class="text-info">Teknisi sedang dalam perjalanan!</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Tombol Cancel -->
                @if(in_array($booking->status, ['pending', 'approved']) && $booking->payment_status == 'unpaid')
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('customer.booking.cancel', $booking->id) }}"
                            onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-times me-2"></i>Batalkan Booking
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection