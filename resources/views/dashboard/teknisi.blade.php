@extends('layouts.teknisi')

@section('title', 'Dashboard Teknisi')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Selamat Datang</div>
                <h2 class="page-title">Dashboard Teknisi</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <i class="fas fa-wrench fa-2x me-3"></i>
                </div>
                <div>
                    <h4 class="alert-title">Halo, {{ $user->name }}!</h4>
                    <div class="text-secondary">
                        Anda memiliki {{ $statistics['assigned'] ?? 0 }} tugas baru dan
                        {{ $statistics['on_progress'] ?? 0 }} tugas sedang dikerjakan.
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <div class="row row-deck row-cards">
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Tugas</div>
                            <div class="ms-auto lh-1">
                                <i class="fas fa-tasks fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="h1 mb-3">{{ $statistics['total'] ?? 0 }}</div>
                        <a href="{{ route('teknisi.tasks.index') }}" class="btn btn-sm btn-primary w-100">
                            Lihat Semua
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Tugas Baru</div>
                            <div class="ms-auto lh-1">
                                <i class="fas fa-clock fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="h1 mb-3">{{ $statistics['assigned'] ?? 0 }}</div>
                        <a href="{{ route('teknisi.tasks.index') }}" class="btn btn-sm btn-warning w-100">
                            Ambil Tugas
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Sedang Dikerjakan</div>
                            <div class="ms-auto lh-1">
                                <i class="fas fa-spinner fa-2x text-info"></i>
                            </div>
                        </div>
                        <div class="h1 mb-3">{{ $statistics['on_progress'] ?? 0 }}</div>
                        <a href="{{ route('teknisi.tasks.index') }}" class="btn btn-sm btn-info w-100">
                            Lanjutkan
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Selesai</div>
                            <div class="ms-auto lh-1">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="h1 mb-3">{{ $statistics['completed'] ?? 0 }}</div>
                        <a href="{{ route('teknisi.tasks.index') }}" class="btn btn-sm btn-success w-100">
                            Riwayat
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Tugas Hari Ini -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tugas Hari Ini</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>ID Booking</th>
                                    <th>Customer</th>
                                    <th>Paket</th>
                                    <th>Alamat</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignedTasks->merge($progressTasks) as $task)
                                <tr>
                                    <td>#{{ $task->id }}</td>
                                    <td>{{ $task->customer->user->name ?? '-' }}</td>
                                    <td>{{ $task->paket->nama_paket ?? '-' }}</td>
                                    <td>{{ Str::limit($task->alamat_pasang, 40) }}</td>
                                    <td>
                                        <span class="badge bg-{{ \App\Models\Booking::getStatusBadge($task->status) }}">
                                            {{ \App\Models\Booking::getStatusLabel($task->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('teknisi.tasks.show', $task->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="fas fa-check-circle fa-2x mb-2 d-block"></i>
                                        Tidak ada tugas untuk saat ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endsection