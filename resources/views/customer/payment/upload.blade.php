@extends('layouts.customer')

@section('title', 'Upload Bukti Transfer')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Pembayaran</div>
                <h2 class="page-title">Upload Bukti Transfer</h2>
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
                                <td><strong>{{ $payment->invoice_number }}</strong></td>
                            </tr>
                            <tr>
                                <th>Paket</th>
                                <td>{{ $payment->booking->paket->nama_paket }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Tagihan</th>
                                <td><span class="text-danger">{{ $payment->amount_formatted }}</span></td>
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
                        <form method="POST" action="{{ route('customer.payment.upload.store', $payment->id) }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label required">Metode Pembayaran</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Bukti Transfer</label>
                                <input type="file" name="bukti_transfer" class="form-control" accept="image/*" required>
                                <small class="form-hint">Upload screenshot/foto bukti transfer (Max 2MB)</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="catatan" class="form-control" rows="3"
                                    placeholder="Contoh: Transfer dari BCA a/n Customer"></textarea>
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