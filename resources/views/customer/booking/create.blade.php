@extends('layouts.customer')

@section('title', 'Booking Pemasangan Internet')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Booking</div>
                <h2 class="page-title">Form Booking Pemasangan Internet</h2>
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
                        <h3 class="card-title">Data Pemasangan</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('customer.booking.store') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label required">Pilih Paket Internet</label>
                                <select name="paket_id" class="form-select @error('paket_id') is-invalid @enderror"
                                    required>
                                    <option value="">-- Pilih Paket --</option>
                                    @foreach($pakets as $paket)
                                    <option value="{{ $paket->id }}"
                                        {{ old('paket_id') == $paket->id ? 'selected' : '' }}>
                                        {{ $paket->nama_paket }} - {{ $paket->speed }} ({{ $paket->harga_formatted }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('paket_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Tanggal Pemasangan</label>
                                <input type="date" name="tanggal_booking"
                                    class="form-control @error('tanggal_booking') is-invalid @enderror"
                                    value="{{ old('tanggal_booking', date('Y-m-d', strtotime('+2 days'))) }}" required>
                                @error('tanggal_booking')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Alamat Pemasangan</label>
                                <textarea name="alamat_pasang"
                                    class="form-control @error('alamat_pasang') is-invalid @enderror" rows="3" required
                                    placeholder="Jl. Contoh No. 123, RT/RW, Kelurahan, Kecamatan, Kota">{{ old('alamat_pasang', $customer->alamat ?? '') }}</textarea>
                                @error('alamat_pasang')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Link Google Maps (Opsional)</label>
                                <input type="url" name="maps_link"
                                    class="form-control @error('maps_link') is-invalid @enderror"
                                    value="{{ old('maps_link') }}" placeholder="https://maps.google.com/?q=...">
                                <small class="form-hint">Share link lokasi rumah Anda untuk memudahkan teknisi</small>
                                @error('maps_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Catatan Tambahan</label>
                                <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror"
                                    rows="2"
                                    placeholder="Informasi tambahan untuk teknisi...">{{ old('catatan') }}</textarea>
                                @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>
                            <h4>Dokumen Pendukung</h4>
                            <p class="text-muted">Upload foto KTP dan foto rumah untuk mempercepat proses verifikasi.
                            </p>

                            <div class="mb-3">
                                <label class="form-label">Upload Foto KTP</label>
                                <input type="file" name="foto_ktp"
                                    class="form-control @error('foto_ktp') is-invalid @enderror" accept="image/*">
                                <small class="form-hint">Format: JPG, PNG (Max 2MB)</small>
                                @error('foto_ktp')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Upload Foto Rumah</label>
                                <input type="file" name="foto_rumah"
                                    class="form-control @error('foto_rumah') is-invalid @enderror" accept="image/*">
                                <small class="form-hint">Format: JPG, PNG (Max 2MB)</small>
                                @error('foto_rumah')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Submit Booking
                                </button>
                                <a href="{{ route('customer.booking.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Proses Booking</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Alur Booking:</strong>
                            <ol class="mt-2 mb-0">
                                <li>Customer booking paket</li>
                                <li>Admin approve booking</li>
                                <li>Admin assign teknisi</li>
                                <li>Teknisi datang pemasangan</li>
                                <li>Pembayaran & aktivasi</li>
                            </ol>
                        </div>

                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-clock me-2"></i>
                            <strong>Estimasi Pemasangan:</strong><br>
                            Pemasangan akan dilakukan dalam 1-3 hari kerja setelah booking disetujui.
                        </div>

                        <div class="alert alert-success mt-3">
                            <i class="fas fa-phone me-2"></i>
                            <strong>Butuh Bantuan?</strong><br>
                            Hubungi customer service kami di:<br>
                            <strong>WhatsApp: 0812-3456-7890</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection