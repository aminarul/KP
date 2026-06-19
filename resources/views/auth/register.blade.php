@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="text-center mb-4">
            <i class="fas fa-globe text-primary" style="font-size: 2.5rem;"></i>
            <h2 class="mt-2">Daftar Akun Baru</h2>
        </div>

        <div class="card card-md">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Registrasi Customer</h2>

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor HP</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                        <small class="form-hint">Minimal 6 karakter</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Daftar Sekarang</button>
                    </div>
                </form>
            </div>
            <div class="card-body text-center">
                <a href="{{ route('login') }}" class="text-muted">Sudah punya akun? Login disini</a>
            </div>
        </div>
    </div>
</div>
@endsection