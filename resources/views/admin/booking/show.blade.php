@extends('layouts.admin')

@section('title', 'Detail Booking #' . $booking->id)

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Booking</div>
                <h2 class="page-title">Detail Booking #{{ $booking->id }}</h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-lg-8">
                <!-- Customer Info -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Customer</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Nama:</strong> {{ $booking->customer->user->name ?? '-' }}<br>
                                <strong>Email:</strong> {{ $booking->customer->user->email ?? '-' }}<br>
                                <strong>HP:</strong> {{ $booking->customer->user->phone ?? '-' }}<br>
                                <strong>NIK:</strong> {{ $booking->customer->nik ?? '-' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Alamat:</strong> {{ $booking->customer->alamat ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dokumen Customer (Foto KTP & Rumah) -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Dokumen Customer</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 text-center">
                                <strong>Foto KTP</strong><br>
                                @if($booking->customer->foto_ktp)
                                <a href="{{ Storage::url($booking->customer->foto_ktp) }}" target="_blank">
                                    <img src="{{ Storage::url($booking->customer->foto_ktp) }}" alt="Foto KTP"
                                        class="img-fluid rounded mt-2"
                                        style="max-height: 200px; border: 1px solid #ddd;">
                                </a>
                                <br>
                                <a href="{{ Storage::url($booking->customer->foto_ktp) }}" target="_blank"
                                    class="btn btn-sm btn-primary mt-2">
                                    <i class="fas fa-download me-1"></i> Lihat Full
                                </a>
                                @else
                                <span class="text-muted">Belum upload</span>
                                @endif
                            </div>
                            <div class="col-md-6 text-center">
                                <strong>Foto Rumah</strong><br>
                                @if($booking->customer->foto_rumah)
                                <a href="{{ Storage::url($booking->customer->foto_rumah) }}" target="_blank">
                                    <img src="{{ Storage::url($booking->customer->foto_rumah) }}" alt="Foto Rumah"
                                        class="img-fluid rounded mt-2"
                                        style="max-height: 200px; border: 1px solid #ddd;">
                                </a>
                                <br>
                                <a href="{{ Storage::url($booking->customer->foto_rumah) }}" target="_blank"
                                    class="btn btn-sm btn-primary mt-2">
                                    <i class="fas fa-download me-1"></i> Lihat Full
                                </a>
                                @else
                                <span class="text-muted">Belum upload</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Info -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Booking</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th width="35%">Paket</th>
                                <td>{{ $booking->paket->nama_paket }} ({{ $booking->paket->speed }})</td>
                            </tr>
                            <tr>
                                <th>Harga</th>
                                <td class="text-danger">{{ $booking->paket->harga_formatted }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Pemasangan</th>
                                <td>{{ $booking->tanggal_booking->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $booking->alamat_pasang }}</td>
                            </tr>
                            @if($booking->maps_link)
                            <tr>
                                <th>Google Maps</th>
                                <td><a href="{{ $booking->maps_link }}" target="_blank">Lihat Lokasi <i
                                            class="fas fa-external-link-alt"></i></a></td>
                            </tr>
                            @endif
                            <tr>
                                <th>Catatan Customer</th>
                                <td>{{ $booking->catatan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Status Booking</th>
                                <td><span
                                        class="badge bg-{{ \App\Models\Booking::getStatusBadge($booking->status) }}">{{ \App\Models\Booking::getStatusLabel($booking->status) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Status Pembayaran</th>
                                <td><span
                                        class="badge bg-{{ \App\Models\Booking::getPaymentStatusBadge($booking->payment_status) }}">{{ \App\Models\Booking::getPaymentStatusLabel($booking->payment_status) }}</span>
                                </td>
                            </tr>
                            @if($booking->invoice_number)
                            <tr>
                                <th>No. Invoice</th>
                                <td>{{ $booking->invoice_number }}</td>
                            </tr>
                            @endif
                            @if($booking->paid_at)
                            <tr>
                                <th>Tanggal Bayar</th>
                                <td>{{ $booking->paid_at->format('d F Y H:i') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- Data Pemasangan Teknisi (untuk booking selesai) -->
                @if($booking->status == 'selesai')
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title text-white">
                            <i class="fas fa-check-circle me-2"></i>Data Pemasangan Teknisi
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 text-center">
                                <strong>Foto Modem Terpasang</strong><br>
                                @if($booking->foto_modem)
                                <a href="{{ Storage::url($booking->foto_modem) }}" target="_blank">
                                    <img src="{{ Storage::url($booking->foto_modem) }}" alt="Foto Modem"
                                        class="img-fluid rounded mt-2"
                                        style="max-height: 250px; border: 1px solid #ddd;">
                                </a>
                                <br>
                                <a href="{{ Storage::url($booking->foto_modem) }}" target="_blank"
                                    class="btn btn-sm btn-primary mt-2">
                                    <i class="fas fa-download me-1"></i> Lihat Full
                                </a>
                                @else
                                <span class="text-muted">Belum ada foto modem</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%"><strong>Type / Merk Modem</strong></td>
                                        <td>: {{ $booking->type_modem ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Serial Number ONT</strong></td>
                                        <td>: <code>{{ $booking->sn_ont ?? '-' }}</code></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Teknisi Pemasang</strong></td>
                                        <td>: {{ $booking->teknisi->user->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Selesai</strong></td>
                                        <td>: {{ $booking->updated_at->format('d F Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Keterangan Teknisi</strong></td>
                                        <td>: {{ $booking->keterangan_pemasangan ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Bukti Transfer -->
                @if($booking->bukti_transfer)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Bukti Transfer Customer</h3>
                    </div>
                    <div class="card-body text-center">
                        <a href="{{ Storage::url($booking->bukti_transfer) }}" target="_blank">
                            <img src="{{ Storage::url($booking->bukti_transfer) }}" alt="Bukti Transfer"
                                class="img-fluid rounded" style="max-height: 300px; border: 1px solid #ddd;">
                        </a>
                        <br>
                        <a href="{{ Storage::url($booking->bukti_transfer) }}" target="_blank"
                            class="btn btn-sm btn-primary mt-2">
                            <i class="fas fa-download me-1"></i> Lihat Full
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <!-- Action Buttons - Approve -->
                @if($booking->status == 'pending')
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title text-white">Approval Booking</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <form method="POST" action="{{ route('admin.bookings.approve', $booking->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check-circle me-2"></i>Setujui Booking
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.bookings.reject', $booking->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('Yakin ingin menolak?')">
                                    <i class="fas fa-times-circle me-2"></i>Tolak Booking
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Verifikasi Pembayaran -->
                @if($booking->status == 'approved' && $booking->payment_status == 'pending')
                <div class="card mb-3">
                    <div class="card-header bg-warning">
                        <h3 class="card-title">Verifikasi Pembayaran</h3>
                    </div>
                    <div class="card-body">
                        @if($booking->bukti_transfer)
                        <div class="mb-3 text-center">
                            <a href="{{ Storage::url($booking->bukti_transfer) }}" target="_blank">
                                <img src="{{ Storage::url($booking->bukti_transfer) }}" alt="Bukti Transfer"
                                    class="img-fluid rounded" style="max-height: 150px;">
                            </a>
                        </div>
                        @endif
                        <div class="d-grid gap-2">
                            <form method="POST" action="{{ route('admin.bookings.confirm-payment', $booking->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check-circle me-2"></i>Konfirmasi Pembayaran
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.bookings.reject-payment', $booking->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-times-circle me-2"></i>Tolak Pembayaran
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Assign Teknisi -->
                @if($booking->status == 'approved' && $booking->payment_status == 'paid' && !$booking->teknisi_id)
                <div class="card mb-3">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title text-white">Assign Teknisi</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.bookings.assign-teknisi', $booking->id) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Pilih Teknisi</label>
                                <select name="teknisi_id" class="form-select" required>
                                    <option value="">-- Pilih Teknisi --</option>
                                    @foreach($teknisis as $teknisi)
                                    <option value="{{ $teknisi->id }}">{{ $teknisi->user->name }} -
                                        {{ $teknisi->wilayah }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-user-check me-2"></i>Assign & Mulai Pemasangan
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                <!-- Informasi Teknisi -->
                @if($booking->teknisi)
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title text-white">Teknisi Bertugas</h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="avatar avatar-xl bg-primary mb-3">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                        <h4>{{ $booking->teknisi->user->name ?? '-' }}</h4>
                        <p class="text-muted">Kode: {{ $booking->teknisi->kode_teknisi ?? '-' }}</p>
                        <p><i class="fas fa-phone me-2"></i>{{ $booking->teknisi->user->phone ?? '-' }}</p>
                        <p><i class="fas fa-map-marker-alt me-2"></i>{{ $booking->teknisi->wilayah ?? '-' }}</p>
                        @if($booking->status == 'on_progress')
                        <span class="badge bg-warning">Sedang Pemasangan</span>
                        @elseif($booking->status == 'selesai')
                        <span class="badge bg-success">Pemasangan Selesai</span>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Status Ringkasan -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Status Ringkasan</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                @if($booking->status != 'pending')
                                <i class="fas fa-check-circle text-success me-2"></i>
                                @else
                                <i class="fas fa-clock text-warning me-2"></i>
                                @endif
                                Booking Disetujui
                            </li>
                            <li class="mb-2">
                                @if($booking->payment_status == 'paid')
                                <i class="fas fa-check-circle text-success me-2"></i>
                                @elseif($booking->payment_status == 'pending')
                                <i class="fas fa-clock text-warning me-2"></i>
                                @else
                                <i class="fas fa-times-circle text-danger me-2"></i>
                                @endif
                                Pembayaran Dikonfirmasi
                            </li>
                            <li class="mb-2">
                                @if($booking->teknisi_id)
                                <i class="fas fa-check-circle text-success me-2"></i>
                                @else
                                <i class="fas fa-clock text-warning me-2"></i>
                                @endif
                                Teknisi Diassign
                            </li>
                            <li class="mb-2">
                                @if($booking->status == 'selesai')
                                <i class="fas fa-check-circle text-success me-2"></i>
                                @elseif($booking->status == 'on_progress')
                                <i class="fas fa-spinner fa-pulse text-warning me-2"></i>
                                @else
                                <i class="fas fa-clock text-warning me-2"></i>
                                @endif
                                Pemasangan Selesai
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection