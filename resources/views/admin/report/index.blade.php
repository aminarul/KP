@extends('layouts.admin')

@section('title', 'Dashboard Report')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Report</div>
                <h2 class="page-title">Dashboard Report & Statistik</h2>
            </div>
            <div class="col-auto ms-auto">
                <div class="btn-list">
                    <a href="{{ route('admin.report.booking') }}" class="btn btn-primary">
                        <i class="fas fa-file-alt me-2"></i>Booking Report
                    </a>
                    <a href="{{ route('admin.report.payment') }}" class="btn btn-success">
                        <i class="fas fa-money-bill me-2"></i>Payment Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Summary Cards -->
        <div class="row row-deck row-cards mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Pendapatan</div>
                            <div class="ms-auto lh-1">
                                <i class="fas fa-dollar-sign fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="h1 mb-3">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Booking</div>
                            <div class="ms-auto lh-1">
                                <i class="fas fa-calendar-check fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="h1 mb-3">{{ $totalBookings }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Customer Aktif</div>
                            <div class="ms-auto lh-1">
                                <i class="fas fa-users fa-2x text-info"></i>
                            </div>
                        </div>
                        <div class="h1 mb-3">{{ $activeCustomers }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Booking Selesai</div>
                            <div class="ms-auto lh-1">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="h1 mb-3">{{ $completedBookings }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pendapatan per Bulan</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Booking per Bulan</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="bookingChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Status Booking</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Paket Terlaris</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Paket</th>
                                    <th>Speed</th>
                                    <th>Total Booking</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topPackages as $package)
                                <tr>
                                    <td>{{ $package->nama_paket }}</td>
                                    <td>{{ $package->speed }}</td>
                                    <td>{{ $package->bookings_count ?? 0 }} booking</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Teknisi Teraktif</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Nama Teknisi</th>
                                    <th>Wilayah</th>
                                    <th>Total Pemasangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topTeknisis as $teknisi)
                                <tr>
                                    <td>{{ $teknisi->user->name ?? '-' }}</td>
                                    <td>{{ $teknisi->wilayah }}</td>
                                    <td>{{ $teknisi->bookings_count ?? 0 }} pemasangan</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: @json(array_column($monthlyRevenue, 'month')),
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: @json(array_column($monthlyRevenue, 'revenue')),
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});

// Booking Chart
const bookingCtx = document.getElementById('bookingChart').getContext('2d');
new Chart(bookingCtx, {
    type: 'line',
    data: {
        labels: @json(array_column($monthlyBookings, 'month')),
        datasets: [{
            label: 'Jumlah Booking',
            data: @json(array_column($monthlyBookings, 'count')),
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2,
            fill: true
        }]
    },
    options: {
        responsive: true
    }
});

// Status Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: @json(array_column($statusData, 'status')),
        datasets: [{
            data: @json(array_column($statusData, 'count')),
            backgroundColor: ['#ffc107', '#0d6efd', '#fd7e14', '#198754'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endsection