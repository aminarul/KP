@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="text-center mb-4">
            <a href="#" class="navbar-brand navbar-brand-autodark">
                <i class="fas fa-globe text-primary" style="font-size: 2.5rem;"></i>
                <h2 class="mt-2">ISP Booking System</h2>
            </a>
        </div>

        <div class="card card-md">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Login ke Akun Anda</h2>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible">
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            placeholder="admin@gmail.com" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">
                            Password
                            <span class="form-label-description">
                                <a href="#">Forgot password?</a>
                            </span>
                        </label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="Password"
                            required>
                    </div>

                    <div class="mb-2">
                        <label class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input">
                            <span class="form-check-label">Remember me</span>
                        </label>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Sign in</button>
                    </div>
                </form>
            </div>
            <div class="hr-text">or</div>
            <div class="card-body text-center">
                <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">
                    Create New Account
                </a>
            </div>
        </div>

        <div class="text-center text-secondary mt-3">
            Demo Admin: admin@gmail.com / password
        </div>
    </div>
</div>
@endsection