@extends('layouts.customer')

@section('title', 'History Booking Saya')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Booking</div>
                <h2 class="page-title">History Booking Saya</h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('customer.booking.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Booking Baru
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>No Booking</th>
                            <th>Tanggal</th>
                            <th>Paket</th>
                            <th>Alamat</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td>#{{ $booking->id }}</td>
                            <td>{{ $booking->tanggal_booking->format('d/m/Y') }}</td>
                            <td>{{ $booking->paket->nama_paket }}<br>
                                <small class="text-muted">{{ $booking->paket->speed }}</small>
                            </td>
                            <td>{{ Str::limit($booking->alamat_pasang, 50) }}</td>
                            <td>
                                <span class="badge bg-{{ \App\Models\Booking::getStatusBadge($booking->status) }}">
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
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                <p>Belum ada booking. Silakan buat booking baru.</p>
                                <a href="{{ route('customer.booking.create') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus me-2"></i>Booking Sekarang
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection