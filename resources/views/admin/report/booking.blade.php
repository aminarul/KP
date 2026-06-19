@extends('layouts.admin')

@section('title', 'Report Booking')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Report</div>
                <h2 class="page-title">Report Booking Pemasangan</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Filter -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua</option>
                            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Disetujui</option>
                            <option value="on_progress" {{ $status == 'on_progress' ? 'selected' : '' }}>Pemasangan
                            </option>
                            <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="h3">{{ $summary['total'] }}</div>
                        <div class="text-secondary">Total Booking</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="h3 text-success">{{ $summary['completed'] }}</div>
                        <div class="text-secondary">Selesai</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="h3 text-warning">{{ $summary['pending'] }}</div>
                        <div class="text-secondary">Pending</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="h3 text-success">Rp {{ number_format($summary['revenue'], 0, ',', '.') }}</div>
                        <div class="text-secondary">Pendapatan (Lunas)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Paket</th>
                            <th>Harga</th>
                            <th>Tanggal Booking</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td>#{{ $booking->id }}</td>
                            <td>{{ $booking->customer->user->name ?? '-' }}</td>
                            <td>{{ $booking->paket->nama_paket ?? '-' }}</td>
                            <td class="text-danger">Rp {{ number_format($booking->paket->harga ?? 0, 0, ',', '.') }}
                            </td>
                            <td>{{ $booking->created_at->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ \App\Models\Booking::getStatusBadge($booking->status) }}">
                                    {{ \App\Models\Booking::getStatusLabel($booking->status) }}
                                </span>
                            </td>
                            <td>
                                <span
                                    class="badge bg-{{ \App\Models\Booking::getPaymentStatusBadge($booking->payment_status) }}">
                                    {{ \App\Models\Booking::getPaymentStatusLabel($booking->payment_status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data</td>
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