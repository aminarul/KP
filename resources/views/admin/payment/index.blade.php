@extends('layouts.admin')

@section('title', 'Manajemen Pembayaran')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Keuangan</div>
                <h2 class="page-title">Manajemen Pembayaran</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="h2 text-primary">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
                        <div class="text-secondary">Total Pendapatan</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="h2 text-warning">{{ $statusCounts['pending'] ?? 0 }}</div>
                        <div class="text-secondary">Menunggu Verifikasi</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="h2 text-danger">{{ $statusCounts['unpaid'] ?? 0 }}</div>
                        <div class="text-secondary">Belum Dibayar</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item"><a class="nav-link {{ !$status ? 'active' : '' }}"
                            href="{{ route('admin.payments.index') }}">Semua</a></li>
                    <li class="nav-item"><a class="nav-link {{ $status == 'pending' ? 'active' : '' }}"
                            href="{{ route('admin.payments.index', ['status' => 'pending']) }}">Menunggu</a></li>
                    <li class="nav-item"><a class="nav-link {{ $status == 'paid' ? 'active' : '' }}"
                            href="{{ route('admin.payments.index', ['status' => 'paid']) }}">Lunas</a></li>
                </ul>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Paket</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->invoice_number }}</td>
                            <td>{{ $payment->booking->customer->user->name ?? '-' }}</td>
                            <td>{{ $payment->booking->paket->nama_paket ?? '-' }}</td>
                            <td>{{ $payment->amount_formatted }}</td>
                            <td><span
                                    class="badge bg-{{ \App\Models\Payment::getStatusBadge($payment->status) }}">{{ \App\Models\Payment::getStatusLabel($payment->status) }}</span>
                            </td>
                            <td><a href="{{ route('admin.payments.show', $payment->id) }}"
                                    class="btn btn-sm btn-primary">Detail</a></td>
                        </tr>
                        @empty<tr>
                            <td colspan="6" class="text-center">Tidak ada data</td>
                        </tr>@endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection