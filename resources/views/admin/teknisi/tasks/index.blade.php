@extends('layouts.teknisi')

@section('title', 'Daftar Tugas')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Tugas</div>
                <h2 class="page-title">Daftar Tugas Pemasangan</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Selamat datang, Teknisi! Berikut adalah daftar tugas pemasangan yang harus Anda kerjakan.
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="h1 text-primary">{{ $statistics['assigned'] }}</div>
                        <div class="text-secondary">Tugas Aktif</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="h1 text-success">{{ $statistics['completed'] }}</div>
                        <div class="text-secondary">Tugas Selesai</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Tasks -->
        @if($assignedTasks->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Tugas Aktif</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Paket</th>
                            <th>Alamat</th>
                            <th>Invoice</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignedTasks as $task)
                        <tr>
                            <td>#{{ $task->id }}</td>
                            <td>{{ $task->customer->user->name ?? '-' }}</td>
                            <td>{{ $task->paket->nama_paket ?? '-' }}</td>
                            <td>{{ Str::limit($task->alamat_pasang, 40) }}</td>
                            <td>{{ $task->invoice_number ?? '-' }}</td>
                            <td>
                                <a href="{{ route('teknisi.tasks.show', $task->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit me-1"></i> Kerjakan
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- No Tasks -->
        @if($assignedTasks->count() == 0)
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-check-circle fa-4x text-success mb-3 d-block"></i>
                <h4>Tidak Ada Tugas</h4>
                <p class="text-muted">Semua tugas telah selesai. Istirahat dulu ya!</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection