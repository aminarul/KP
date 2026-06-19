@extends('layouts.customer')

@section('title', 'Daftar Pembayaran')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Keuangan</div>
                <h2 class="page-title">Daftar Pembayaran</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Summary -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="h2 text-danger">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</div>
                        <div class="text-secondary">Total Tagihan Belum Dibayar</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="h2 text-success">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
                        <div class="text-secondary">Total Pembayaran Lunas</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment List -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Pembayaran</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Paket</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tgl Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td><strong>{{ $payment->invoice_number }}</strong></td>
                            <td>{{ $payment->booking->paket->nama_paket ?? '-' }}</td>
                            <td>{{ $payment->amount_formatted }}</td>
                            <td>
                                <span class="badge bg-{{ \App\Models\Payment::getStatusBadge($payment->status) }}">
                                    {{ \App\Models\Payment::getStatusLabel($payment->status) }}
                                </span>
                            </td>
                            <td>{{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : '-' }}</td>
                            <td>
                                @if($payment->status == 'unpaid')
                                <a href="{{ route('customer.payment.upload', $payment->id) }}"
                                    class="btn btn-sm btn-success">
                                    <i class="fas fa-upload me-1"></i> Bayar
                                </a>
                                @elseif($payment->status == 'pending')
                                <span class="badge bg-warning">Menunggu Verifikasi</span>
                                @elseif($payment->status == 'paid')
                                <span class="badge bg-success">Lunas</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data pembayaran</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $payments->links() }}
            </div>
        </div>

        <!-- Informasi Bank -->
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Informasi Pembayaran</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Silakan transfer ke rekening berikut:</strong>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <strong>Bank BCA</strong><br>
                            No. Rekening: 123-456-7890<br>
                            Atas Nama: PT ISP Net Indonesia
                        </div>
                        <div class="col-md-4">
                            <strong>Bank Mandiri</strong><br>
                            No. Rekening: 987-654-3210<br>
                            Atas Nama: PT ISP Net Indonesia
                        </div>
                        <div class="col-md-4">
                            <strong>QRIS</strong><br>
                            Scan QR Code untuk pembayaran cepat
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection