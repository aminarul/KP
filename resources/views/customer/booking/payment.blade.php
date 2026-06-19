@extends('layouts.customer')

@section('title', 'Upload Pembayaran')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Pembayaran</div>
                <h2 class="page-title">Upload Bukti Pembayaran</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Tagihan</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Invoice</th>
                                <td><strong>{{ $booking->invoice_number }}</strong></td>
                            </tr>
                            <tr>
                                <th>Paket</th>
                                <td>{{ $booking->paket->nama_paket }} ({{ $booking->paket->speed }})</td>
                            </tr>
                            <tr>
                                <th>Jumlah Tagihan</th>
                                <td><span class="text-danger h3">{{ $booking->harga_formatted }}</span></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td><span class="badge bg-danger">Belum Dibayar</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Upload Bukti Transfer</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('customer.booking.upload-payment', $booking->id) }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Rekening Tujuan:</strong><br>
                                Bank BCA: 123-456-7890 a/n PT ISP Net Indonesia
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Bukti Transfer</label>
                                <input type="file" name="bukti_transfer" class="form-control" accept="image/*" required>
                                <small class="form-hint">Upload screenshot/foto bukti transfer (Max 2MB)</small>
                            </div>

                            <div class="alert alert-warning">
                                <i class="fas fa-clock me-2"></i>
                                Pembayaran akan diverifikasi dalam 1x24 jam.
                            </div>

                            <div class="form-footer">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-upload me-2"></i>Upload & Kirim
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection