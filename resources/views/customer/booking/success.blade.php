@extends('layouts.customer')

@section('title', 'Booking Berhasil')

@section('content')
<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="text-center mb-4">
            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
        </div>

        <div class="card card-md">
            <div class="card-body text-center">
                <h2 class="mb-3">Booking Berhasil!</h2>
                <p class="text-muted mb-4">
                    Booking pemasangan internet Anda telah kami terima.<br>
                    Booking ID: <strong>#{{ $booking->id }}</strong>
                </p>

                <div class="alert alert-info">
                    <i class="fas fa-clock me-2"></i>
                    Status saat ini: <strong>Pending</strong><br>
                    Admin akan segera memproses booking Anda.
                </div>

                <div class="mt-4">
                    <a href="{{ route('customer.booking.show', $booking->id) }}" class="btn btn-primary">
                        <i class="fas fa-eye me-2"></i>Lihat Detail Booking
                    </a>
                    <a href="{{ route('customer.booking.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list me-2"></i>Lihat Semua Booking
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection