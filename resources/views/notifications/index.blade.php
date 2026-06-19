@extends('layouts.admin')

@section('title', 'Semua Notifikasi')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Komunikasi</div>
                <h2 class="page-title">Semua Notifikasi</h2>
            </div>
            <div class="col-auto ms-auto">
                <!-- PERBAIKAN 1: Ubah link menjadi form POST -->
                <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check-double me-2"></i>Tandai Semua Dibaca
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th style="width: 5%">Icon</th>
                            <th style="width: 55%">Notifikasi</th>
                            <th style="width: 20%">Waktu</th>
                            <th style="width: 10%">Status</th>
                            <th style="width: 10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notifications as $notif)
                        <tr class="{{ $notif->read_at ? '' : 'table-active' }}">
                            <td>
                                @if(isset($notif->data['icon']))
                                <i
                                    class="fas {{ $notif->data['icon'] }} text-{{ $notif->data['color'] ?? 'secondary' }} fa-2x"></i>
                                @elseif(isset($notif->data['type']))
                                @if($notif->data['type'] == 'booking_approved')
                                <i class="fas fa-check-circle text-success fa-2x"></i>
                                @elseif($notif->data['type'] == 'payment_confirmed')
                                <i class="fas fa-credit-card text-primary fa-2x"></i>
                                @elseif($notif->data['type'] == 'teknisi_assigned')
                                <i class="fas fa-wrench text-warning fa-2x"></i>
                                @elseif($notif->data['type'] == 'new_payment')
                                <i class="fas fa-money-bill text-info fa-2x"></i>
                                @elseif($notif->data['type'] == 'installation_completed')
                                <i class="fas fa-check-double text-success fa-2x"></i>
                                @else
                                <i class="fas fa-bell text-secondary fa-2x"></i>
                                @endif
                                @else
                                <i class="fas fa-bell text-secondary fa-2x"></i>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ $notif->data['title'] ?? 'Notifikasi' }}</div>
                                <div class="text-secondary small">{{ $notif->data['message'] ?? '' }}</div>
                                @if(isset($notif->data['booking_id']))
                                <div class="mt-1">
                                    <span class="badge bg-secondary">Booking #{{ $notif->data['booking_id'] }}</span>
                                </div>
                                @endif
                            </td>
                            <td>
                                <div>{{ $notif->created_at->format('d F Y') }}</div>
                                <div class="text-secondary small">{{ $notif->created_at->format('H:i') }}</div>
                            </td>
                            <td>
                                @if($notif->read_at)
                                <span class="badge bg-secondary">Dibaca</span>
                                @else
                                <span class="badge bg-primary">Baru</span>
                                @endif
                            </td>
                            <td>
                                <!-- PERBAIKAN 2: Tambahkan link untuk baca notifikasi -->
                                <a href="{{ route('notifications.read', $notif->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x mb-3 d-block text-muted"></i>
                                <h4 class="text-muted">Belum ada notifikasi</h4>
                                <p class="text-secondary">Notifikasi akan muncul di sini ketika ada aktivitas</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($notifications->hasPages())
            <div class="card-footer">
                {{ $notifications->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection