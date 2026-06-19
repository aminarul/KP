@extends('layouts.admin')

@section('title', 'Report Pembayaran')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Report</div>
                <h2 class="page-title">Report Pembayaran</h2>
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
                        <select name="payment_status" class="form-select">
                            <option value="">Semua</option>
                            <option value="paid" {{ $paymentStatus == 'paid' ? 'selected' : '' }}>Lunas</option>
                            <option value="pending" {{ $paymentStatus == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="unpaid" {{ $paymentStatus == 'unpaid' ? 'selected' : '' }}>Belum Bayar
                            </option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="h3 text-success">Rp {{ number_format($summary['total_paid'], 0, ',', '.') }}</div>
                        <div class="text-secondary">Total Pendapatan</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="h3">{{ $summary['total_transactions'] }}</div>
                        <div class="text-secondary">Total Transaksi</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="h3">Rp {{ number_format($summary['avg_transaction'], 0, ',', '.') }}</div>
                        <div class="text-secondary">Rata-rata Transaksi</div>
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
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Paket</th>
                            <th>Jumlah</th>
                            <th>Tgl Bayar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->invoice_number ?? '-' }}</td>
                            <td>{{ $payment->customer->user->name ?? '-' }}</td>
                            <td>{{ $payment->paket->nama_paket ?? '-' }}</td>
                            <td>Rp {{ number_format($payment->paket->harga ?? 0, 0, ',', '.') }}</td>
                            <td>{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : '-' }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ \App\Models\Booking::getPaymentStatusBadge($payment->payment_status) }}">
                                    {{ \App\Models\Booking::getPaymentStatusLabel($payment->payment_status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data pembayaran</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection