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
        @if($bookings->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3 d-block"></i>
                <h4>Belum Ada Booking</h4>
                <p class="text-muted">Anda belum membuat booking pemasangan internet.</p>
                <a href="{{ route('customer.booking.create') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-plus me-2"></i>Buat Booking Sekarang
                </a>
            </div>
        </div>
        @else
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>ID Booking</th>
                            <th>Tanggal</th>
                            <th>Paket</th>
                            <th>Alamat</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td>#{{ $booking->id }}</td>
                            <td>{{ $booking->tanggal_booking->format('d/m/Y') }}</td>
                            <td>
                                {{ $booking->paket->nama_paket }}<br>
                                <small class="text-muted">{{ $booking->paket->speed }}</small>
                            </td>
                            <td>{{ Str::limit($booking->alamat_pasang, 40) }}</td>
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
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $bookings->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection