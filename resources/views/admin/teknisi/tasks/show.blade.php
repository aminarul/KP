@extends('layouts.teknisi')

@section('title', 'Detail Tugas #' . $task->id)

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Tugas</div>
                <h2 class="page-title">Detail Tugas #{{ $task->id }}</h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('teknisi.tasks.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-lg-7">
                <!-- Customer Info -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Customer</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Nama:</strong> {{ $task->customer->user->name ?? '-' }}<br>
                                <strong>Email:</strong> {{ $task->customer->user->email ?? '-' }}<br>
                                <strong>HP:</strong> {{ $task->customer->user->phone ?? '-' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Alamat:</strong> {{ $task->customer->alamat ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Info -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Detail Pemasangan</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th width="35%">Paket</th>
                                <td>{{ $task->paket->nama_paket ?? '-' }} ({{ $task->paket->speed ?? '-' }})</td>
                            </tr>
                            <tr>
                                <th>Tanggal Pemasangan</th>
                                <td>{{ $task->tanggal_booking->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <th>Alamat Lengkap</th>
                                <td>{{ $task->alamat_pasang }}</td>
                            </tr>
                            @if($task->maps_link)
                            <tr>
                                <th>Google Maps</th>
                                <td><a href="{{ $task->maps_link }}" target="_blank">Lihat Lokasi <i
                                            class="fas fa-external-link-alt"></i></a></td>
                            </tr>
                            @endif
                            <tr>
                                <th>Catatan Customer</th>
                                <td>{{ $task->catatan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>No. Invoice</th>
                                <td>{{ $task->invoice_number ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <!-- Form Selesaikan Pemasangan -->
                @if($task->status == 'on_progress' && $task->payment_status == 'paid')
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title text-white">Selesaikan Pemasangan</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('teknisi.tasks.complete', $task->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label required">Foto Modem Terpasang</label>
                                <input type="file" name="foto_modem" class="form-control" accept="image/*" required>
                                <small class="form-hint">Upload foto modem setelah terpasang (Max 5MB)</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Type / Merk Modem</label>
                                <input type="text" name="type_modem" class="form-control"
                                    placeholder="Contoh: FiberHome HG6245D" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Serial Number ONT</label>
                                <input type="text" name="sn_ont" class="form-control" placeholder="Contoh: ALCLF4A12B3C"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Keterangan Pemasangan</label>
                                <textarea name="keterangan_pemasangan" class="form-control" rows="3"
                                    placeholder="Catatan penting selama pemasangan..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check-circle me-2"></i>Selesaikan Pemasangan
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                <!-- Task Status Info -->
                @if($task->status == 'selesai')
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title text-white">Data Modem Terpasang</h3>
                    </div>
                    <div class="card-body">
                        @if($task->foto_modem)
                        <div class="text-center mb-3">
                            <img src="{{ Storage::url($task->foto_modem) }}" alt="Foto Modem" class="img-fluid rounded"
                                style="max-height: 200px;">
                        </div>
                        @endif
                        <strong>Type Modem:</strong> {{ $task->type_modem ?? '-' }}<br>
                        <strong>SN ONT:</strong> {{ $task->sn_ont ?? '-' }}<br>
                        <strong>Keterangan:</strong> {{ $task->keterangan_pemasangan ?? '-' }}<br>
                        <strong>Selesai:</strong> {{ $task->updated_at->format('d F Y H:i') }}
                    </div>
                </div>
                @endif

                @if($task->status != 'on_progress' && $task->status != 'selesai')
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-info-circle fa-3x text-info mb-3 d-block"></i>
                        <p>Tugas ini belum dalam status pemasangan.</p>
                        <p class="text-muted">Status: <strong>{{ ucfirst($task->status) }}</strong></p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection