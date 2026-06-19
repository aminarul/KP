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
                <div class="card">
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
                                <td width="35%"><strong>Tanggal Booking</strong></td>
                                <td>: {{ $booking->tanggal_booking->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Paket Internet</strong></td>
                                <td>: {{ $booking->paket->nama_paket }} ({{ $booking->paket->speed }})</td>
                            </tr>
                            <tr>
                                <td><strong>Harga Paket</strong></td>
                                <td>: {{ $booking->paket->harga_formatted }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat Pemasangan</strong></td>
                                <td>: {{ $booking->alamat_pasang }}</td>
                            </tr>
                            @if($booking->maps_link)
                            <tr>
                                <td><strong>Google Maps</strong></td>
                                <td>: <a href="{{ $booking->maps_link }}" target="_blank">Lihat Lokasi <i
                                            class="fas fa-external-link-alt"></i></a></td>
                            </tr>
                            @endif
                            @if($booking->catatan)
                            <tr>
                                <td><strong>Catatan</strong></td>
                                <td>: {{ $booking->catatan }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Tanggal Booking Dibuat</strong></td>
                                <td>: {{ $booking->created_at->format('d F Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                @if($booking->teknisi)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Teknisi</h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="avatar avatar-xl bg-primary mb-3">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                        <h4>{{ $booking->teknisi->user->name }}</h4>
                        <p class="text-muted">Kode: {{ $booking->teknisi->kode_teknisi }}</p>
                        <p><i class="fas fa-phone me-2"></i>{{ $booking->teknisi->user->phone }}</p>
                        <p><i class="fas fa-map-marker-alt me-2"></i>{{ $booking->teknisi->wilayah }}</p>
                    </div>
                </div>
                @endif

                @if(in_array($booking->status, ['pending', 'approved']))
                <div class="card mt-3">
                    <div class="card-body text-center">
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