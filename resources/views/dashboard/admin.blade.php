@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Overview</div>
                <h2 class="page-title">Dashboard Admin</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <span class="d-none d-sm-inline">
                        <a href="#" class="btn btn-white">
                            <i class="fas fa-calendar me-2"></i>Today: {{ date('d/m/Y') }}
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Statistics Cards -->
        <div class="row row-deck row-cards">
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Customer</div>
                            <div class="ms-auto lh-1">
                                <i class="fas fa-users fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="h1 mb-3">{{ $totalCustomers }}</div>
                        <div class="d-flex mb-2">
                            <a href="{{ route('admin.customer.index') }}" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-list me-2"></i>Lihat Customer
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Teknisi</div>
                            <div class="ms-auto lh-1">
                                <i class="fas fa-wrench fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="h1 mb-3">{{ $totalTeknisis }}</div>
                        <div class="d-flex mb-2">
                            <a href="{{ route('admin.teknisi.index') }}" class="btn btn-sm btn-success w-100">
                                <i class="fas fa-list me-2"></i>Lihat Teknisi
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Paket</div>
                            <div class="ms-auto lh-1">
                                <i class="fas fa-wifi fa-2x text-info"></i>
                            </div>
                        </div>
                        <div class="h1 mb-3">{{ $totalPakets }}</div>
                        <div class="d-flex mb-2">
                            <a href="{{ route('admin.paket.index') }}" class="btn btn-sm btn-info w-100">
                                <i class="fas fa-list me-2"></i>Lihat Paket
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Coverage Area</div>
                            <div class="ms-auto lh-1">
                                <i class="fas fa-map-marker-alt fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="h1 mb-3">{{ $totalCoverage }}</div>
                        <div class="d-flex mb-2">
                            <a href="{{ route('admin.coverage.index') }}" class="btn btn-sm btn-warning w-100">
                                <i class="fas fa-list me-2"></i>Lihat Wilayah
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Data Tables -->
        <div class="row row-deck row-cards mt-2">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Customer Terbaru</h3>
                        <div class="card-actions">
                            <a href="{{ route('admin.customer.index') }}" class="btn btn-sm btn-primary">
                                Lihat Semua <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>HP</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentCustomers as $customer)
                                <tr>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->phone ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $customer->is_active ? 'success' : 'danger' }}">
                                            {{ $customer->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada customer</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Paket Internet Terbaru</h3>
                        <div class="card-actions">
                            <a href="{{ route('admin.paket.index') }}" class="btn btn-sm btn-primary">
                                Lihat Semua <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Nama Paket</th>
                                    <th>Speed</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPakets as $paket)
                                <tr>
                                    <td>{{ $paket->nama_paket }}</td>
                                    <td>{{ $paket->speed }}</td>
                                    <td>{{ $paket->harga_formatted }}</td>
                                    <td>
                                        <span class="badge bg-{{ $paket->status === 'aktif' ? 'success' : 'danger' }}">
                                            {{ $paket->status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada paket internet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <a href="{{ route('admin.paket.create') }}" class="btn btn-outline-primary w-100 py-3">
                                    <i class="fas fa-plus-circle fa-2x mb-2 d-block"></i>
                                    Tambah Paket
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.teknisi.create') }}"
                                    class="btn btn-outline-success w-100 py-3">
                                    <i class="fas fa-user-plus fa-2x mb-2 d-block"></i>
                                    Tambah Teknisi
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.coverage.create') }}" class="btn btn-outline-info w-100 py-3">
                                    <i class="fas fa-map-marker-alt fa-2x mb-2 d-block"></i>
                                    Tambah Coverage
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="#" class="btn btn-outline-warning w-100 py-3">
                                    <i class="fas fa-chart-line fa-2x mb-2 d-block"></i>
                                    Laporan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection