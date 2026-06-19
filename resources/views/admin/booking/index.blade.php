@extends('layouts.admin')

@section('title', 'Manajemen Booking')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Booking</div>
                <h2 class="page-title">Manajemen Booking Pemasangan</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Daftar Booking</div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Paket</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td>#{{ $booking->id }}</td>
                            <td>{{ $booking->customer->user->name ?? '-' }}</td>
                            <td>{{ $booking->paket->nama_paket ?? '-' }}</td>
                            <td>{{ $booking->tanggal_booking->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ \App\Models\Booking::getStatusBadge($booking->status) }}">
                                    {{ \App\Models\Booking::getStatusLabel($booking->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.bookings.show', $booking->id) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada booking</td>
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