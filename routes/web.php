<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\PaketInternetController;
use App\Http\Controllers\Admin\TeknisiController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CoverageAreaController;
use App\Http\Controllers\Admin\BookingManageController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Teknisi\TaskController;


// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Auth routes
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout')->middleware('auth');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
    
    // Master Data
    Route::resource('paket', PaketInternetController::class);
    Route::resource('teknisi', TeknisiController::class);
    Route::resource('customer', CustomerController::class)->only(['index', 'show', 'destroy']);
    Route::post('/customer/{id}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('customer.toggle-status');
    Route::resource('coverage', CoverageAreaController::class);
    Route::post('/coverage/{id}/toggle-status', [CoverageAreaController::class, 'toggleStatus'])->name('coverage.toggle-status');
    
    // Booking Management
    Route::get('/bookings', [BookingManageController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}', [BookingManageController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{id}/approve', [BookingManageController::class, 'approve'])->name('bookings.approve');
    Route::post('/bookings/{id}/reject', [BookingManageController::class, 'reject'])->name('bookings.reject');
    Route::post('/bookings/{id}/assign-teknisi', [BookingManageController::class, 'assignTeknisi'])->name('bookings.assign-teknisi');

    // Payment Management
    Route::get('/payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{id}', [App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{id}/verify', [App\Http\Controllers\Admin\PaymentController::class, 'verify'])->name('payments.verify');
    Route::post('/payments/{id}/reject', [App\Http\Controllers\Admin\PaymentController::class, 'reject'])->name('payments.reject');
    Route::post('/booking/{id}/generate-invoice', [App\Http\Controllers\Admin\PaymentController::class, 'generateInvoice'])->name('bookings.generate-invoice');
    
    // Payment Confirmation (manual dari booking)
    Route::post('/bookings/{id}/confirm-payment', [BookingManageController::class, 'confirmPayment'])->name('bookings.confirm-payment');
    Route::post('/bookings/{id}/reject-payment', [BookingManageController::class, 'rejectPayment'])->name('bookings.reject-payment');

    // Report routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/booking', [ReportController::class, 'bookingReport'])->name('report.booking');
    Route::get('/reports/payment', [ReportController::class, 'paymentReport'])->name('report.payment');
});

// Customer routes
Route::middleware(['auth', 'customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'customerDashboard'])->name('dashboard');
    
    // Booking routes
    Route::get('/bookings', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::get('/booking/{id}/success', [BookingController::class, 'success'])->name('booking.success');
    Route::post('/booking/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    
    // Customer Payment routes
    Route::get('/booking/{id}/payment', [BookingController::class, 'paymentForm'])->name('booking.payment');
    Route::post('/booking/{id}/upload-payment', [BookingController::class, 'uploadPayment'])->name('booking.upload-payment');
    
    // Payment history (opsional)
    Route::get('/payments', [App\Http\Controllers\Customer\PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payment/{id}/upload', [App\Http\Controllers\Customer\PaymentController::class, 'uploadForm'])->name('payment.upload');
    Route::post('/payment/{id}/upload', [App\Http\Controllers\Customer\PaymentController::class, 'upload'])->name('payment.upload.store');
});

// Teknisi routes
Route::middleware(['auth', 'teknisi'])->prefix('teknisi')->name('teknisi.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'teknisiDashboard'])->name('dashboard');
    
    // Task Management
    Route::get('/tasks', [App\Http\Controllers\Teknisi\TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{id}', [App\Http\Controllers\Teknisi\TaskController::class, 'show'])->name('tasks.show');
    Route::post('/tasks/{id}/start', [App\Http\Controllers\Teknisi\TaskController::class, 'startTask'])->name('tasks.start');
    Route::post('/tasks/{id}/complete', [App\Http\Controllers\Teknisi\TaskController::class, 'completeTask'])->name('tasks.complete');
    Route::post('/tasks/{id}/cancel', [App\Http\Controllers\Teknisi\TaskController::class, 'cancelTask'])->name('tasks.cancel');
});
// Notification routes (untuk semua role yang sudah login)
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
});

// Home redirect
Route::get('/', function () {
    if (auth()->check()) {
        return match(auth()->user()->role) {
            'admin' => redirect('/admin/dashboard'),
            'teknisi' => redirect('/teknisi/dashboard'),
            default => redirect('/customer/dashboard'),
        };
    }
    return redirect('/login');
});